<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository; 
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto; // Asegúrate de importar el modelo para búsquedas directas si es necesario

class CartController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $repository)
    {
        $this->productRepository = $repository;
    }

    /**
     * Añade un producto al carrito (guardado en la Sesión).
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:productos,id'
        ]);

        $productId = $request->input('product_id');
        $quantity = 1; 

        // Buscamos el producto
        $producto = $this->productRepository->find($productId);

        // --- 1. VALIDACIÓN DE STOCK (AL AGREGAR) ---
        $cart = session()->get('cart', []);
        
        // Cantidad que ya tengo en el carrito de este producto
        $currentQuantity = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;

        // Si (lo que tengo + lo que quiero agregar) es mayor al stock real
        if (($currentQuantity + $quantity) > $producto->stock) {
            return redirect()->back()->with('error', '¡Stock insuficiente! Solo quedan ' . $producto->stock . ' unidades.');
        }
        // -------------------------------------------

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                "nombre" => $producto->nombre,
                "quantity" => $quantity,
                "precio" => $producto->precio,
                "url_imagen" => $producto->url_imagen
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', '¡Producto añadido al carrito!');
    }

    public function index()
    {
        $cartItems = session()->get('cart', []);
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['precio'] * $item['quantity'];
        }

        return view('cart.index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    /**
     * Aumenta la cantidad de un producto en el carrito.
     */
    public function increase(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        // Necesitamos buscar el producto para saber su stock actual
        // Usamos el repositorio o el modelo directamente
        $producto = $this->productRepository->find($productId);

        if (isset($cart[$productId])) {
            
            // --- 2. VALIDACIÓN DE STOCK (AL AUMENTAR CON EL +) ---
            if (($cart[$productId]['quantity'] + 1) > $producto->stock) {
                // Usamos back() para quedarnos en la misma página (ideal para el mini-carrito)
                return redirect()->back()->with('error', 'Has alcanzado el límite de stock disponible.');
            }
            // -----------------------------------------------------

            $cart[$productId]['quantity']++;
            session()->put('cart', $cart);
        }

        // CAMBIO: Usamos back() en lugar de route('cart.index')
        return redirect()->back()->with('success', 'Cantidad actualizada.');
    }

    /**
     * Reduce la cantidad de un producto en el carrito.
     */
    public function decrease(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']--;

            if ($cart[$productId]['quantity'] <= 0) {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
        }

        // CAMBIO: Usamos back() para no sacarte de la página actual
        return redirect()->back()->with('success', 'Cantidad actualizada.');
    }

    /**
     * Elimina un producto completo del carrito.
     */
    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        // CAMBIO: Usamos back()
        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }

    // ... El resto de tus funciones (checkout, placeOrder) se quedan igual ...
    // (Solo asegúrate de no haber borrado placeOrder si estaba en este archivo)
    
    public function checkout()
    {
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect(route('home'))->with('error', 'Tu carrito está vacío.');
        }
        return view('checkout.index');
    }

    public function placeOrder(Request $request)
    {
        // ... (Tu lógica de placeOrder que me pasaste antes estaba bien, pégala aquí si no la tienes) ...
        // Recuerda incluir la lógica de transacción y descuento de stock
        
        $request->validate([
            'nombre_receptor' => 'required|string|max:100',
            'telefono_contacto' => 'required|string|max:20',
            'direccion_envio' => 'required|string|max:255',
        ]);

        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect(route('home'));
        }
        
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['precio'] * $item['quantity'];
        }

        try {
            DB::beginTransaction();

            $pedido = Pedido::create([
                'usuario_id' => Auth::id(),
                'monto_total' => $total,
                'direccion_envio' => $request->direccion_envio,
                'telefono_contacto' => $request->telefono_contacto,
                'nombre_receptor' => $request->nombre_receptor,
                'estado' => 'pendiente_pago',
                'fecha_pedido' => now(),
            ]);

            foreach ($cartItems as $id => $item) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $id,
                    'cantidad' => $item['quantity'],
                    'precio_unitario' => $item['precio'],
                ]);
                
                // Descontar stock
                $producto = Producto::find($id);
                if ($producto) {
                    // Doble chequeo por seguridad (aunque ya validamos en el carrito)
                    if ($producto->stock < $item['quantity']) {
                        throw new \Exception("Stock insuficiente para " . $producto->nombre);
                    }
                    $producto->stock -= $item['quantity'];
                    $producto->save();
                }
            }

            session()->forget('cart');
            DB::commit();

            return redirect(route('payment.index', ['pedido' => $pedido->id]));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('checkout.index'))
                    ->with('error', 'Error al procesar tu pedido: ' . $e->getMessage());
        }
    }
}