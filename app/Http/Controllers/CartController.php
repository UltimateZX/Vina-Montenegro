<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository; 
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;

class CartController extends Controller
{
    protected $productRepository;

    // Inyectamos el Repositorio para poder buscar productos
    public function __construct(ProductRepository $repository)
    {
        $this->productRepository = $repository;
    }

    /**
     * Añade un producto al carrito (guardado en la Sesión).
     */
    public function add(Request $request)
    {
        // 1. Validamos que nos envíen un ID de producto
        $request->validate([
            'product_id' => 'required|exists:productos,id'
        ]);

        $productId = $request->input('product_id');
        // Por ahora, la cantidad es 1, luego podemos agregar un input
        $quantity = 1; 

        // 2. Buscamos los detalles del producto en la BD
        $producto = $this->productRepository->find($productId);

        // 3. Obtenemos el carrito actual de la sesión, o un array vacío si no existe
        $cart = session()->get('cart', []);

        // 4. Revisamos si el producto ya está en el carrito
        if (isset($cart[$productId])) {
            // Si ya está, solo aumentamos la cantidad
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // Si es nuevo, lo agregamos al array
            $cart[$productId] = [
                "nombre" => $producto->nombre,
                "quantity" => $quantity,
                "precio" => $producto->precio,
                "url_imagen" => $producto->url_imagen
            ];
        }

        // 5. Guardamos el carrito actualizado de vuelta en la sesión
        session()->put('cart', $cart);

        // 6. Redirigimos al usuario de vuelta a la página anterior
        return redirect()->back()->with('success', '¡Producto añadido al carrito!');
    }

/**
 * Muestra la página del carrito.
 */
public function index()
{
    // 1. Obtenemos el carrito de la sesión
    $cartItems = session()->get('cart', []);

    // 2. Calculamos el total
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['precio'] * $item['quantity'];
    }

    // 3. Enviamos los datos a la nueva vista (que crearemos ahora)
    return view('cart.index', [
        'cartItems' => $cartItems,
        'total' => $total
    ]);
}

// ... (después del método index())

/**
 * Aumenta la cantidad de un producto en el carrito.
 */
public function increase(Request $request)
{
    $productId = $request->input('product_id');
    $cart = session()->get('cart', []);

    if (isset($cart[$productId])) {
        $cart[$productId]['quantity']++;
        session()->put('cart', $cart);
    }

    return redirect()->route('cart.index')->with('success', 'Cantidad actualizada.');
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

        // Si la cantidad llega a 0, eliminamos el producto
        if ($cart[$productId]['quantity'] <= 0) {
            unset($cart[$productId]);
        }
        session()->put('cart', $cart);
    }

    return redirect()->route('cart.index')->with('success', 'Cantidad actualizada.');
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

    return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
}

//validación del checkout
public function checkout()
{
    // Obtenemos el carrito para asegurarnos de que no esté vacío
    $cartItems = session()->get('cart', []);

    // Si el carrito está vacío, no pueden pagar. Lo redirigimos al catálogo.
    if (empty($cartItems)) {
        return redirect(route('home'))->with('error', 'Tu carrito está vacío.');
    }

    // Si hay items, mostramos la vista del formulario
    return view('checkout.index');
}

public function placeOrder(Request $request)
{
    // 1. Validar los datos del formulario de dirección
    $request->validate([
        'nombre_receptor' => 'required|string|max:100',
        'telefono_contacto' => 'required|string|max:20',
        'direccion_envio' => 'required|string|max:255',
    ]);

    // 2. Traemos el carrito y calculamos el total
    $cartItems = session()->get('cart', []);
    if (empty($cartItems)) {
        return redirect(route('home'));
    }
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['precio'] * $item['quantity'];
    }

    // 3. Usamos una Transacción 
    // Esto asegura que si algo falla, no se crea nada a medias.
    try {
        DB::beginTransaction();

        // 4. Crear el Pedido en la tabla 'pedidos'
        $pedido = Pedido::create([
            'usuario_id' => Auth::id(), // ID del usuario logueado
            'monto_total' => $total,
            'direccion_envio' => $request->direccion_envio,
            'telefono_contacto' => $request->telefono_contacto,
            'nombre_receptor' => $request->nombre_receptor,
            'estado' => 'pendiente_pago', // <-- Estado inicial
            'fecha_pedido' => now(),
        ]);

        // 5. Crear cada item en la tabla 'detalles_pedido'
        foreach ($cartItems as $id => $item) {
            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $id,
                'cantidad' => $item['quantity'],
                'precio_unitario' => $item['precio'],
            ]);
            // Aquí también deberíamos descontar el stock del producto
            $producto = Producto::find($id);
                if ($producto) {
                $producto->stock -= $item['quantity']; // Restamos la cantidad
                $producto->save(); // Guardamos el nuevo stock
            }

        }

        // 6. Si todo salió bien, vaciamos el carrito
        session()->forget('cart');

        // 7. Confirmamos la transacción
        DB::commit();

        // 8. Redirigimos al usuario a la PÁGINA DE PAGO
        // Pasamos el ID del pedido para saber cuánto debe pagar
        return redirect(route('payment.index', ['pedido' => $pedido->id]));

    } catch (\Exception $e) {
        // 9. Si algo falló, revertimos todo
        DB::rollBack();
        // Y mandamos al usuario de vuelta con un error
        return redirect(route('checkout.index'))
               ->with('error', 'Error al procesar tu pedido: ' . $e->getMessage());
    }
}

}