<?php
namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Pedido; // Importamos el modelo Pedido
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Muestra la página de pago con el QR de Yape.
     */
    public function index(Pedido $pedido)
    {
        // Seguridad: Verificamos que el pedido le pertenezca al usuario logueado
        if ($pedido->usuario_id !== Auth::id()) {
            abort(403); // Prohibido
        }

        // Si el pedido ya fue pagado, no dejamos que suba otro voucher
        if ($pedido->estado !== 'pendiente_pago') {
            return redirect(route('home'))
                   ->with('error', 'Este pedido ya ha sido procesado.');
        }

        // Enviamos el pedido a la vista
        return view('payment.index', [
            'pedido' => $pedido
        ]);
    }

    public function storeVoucher(Request $request, Pedido $pedido)
{
    // --- Doble chequeo de seguridad ---
    // 1. El pedido debe ser del usuario logueado
    if ($pedido->usuario_id !== Auth::id()) {
        abort(403);
    }
    // 2. El pedido debe estar esperando el pago
    if ($pedido->estado !== 'pendiente_pago') {
        return redirect(route('home'))->with('error', 'Este pedido ya fue procesado.');
    }
    // ---------------------------------

    // 1. Validar que el archivo sea una imagen
    $request->validate([
        'voucher' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ]);

    // 2. Guardar la imagen en 'storage/app/public/vouchers'
    $path = $request->file('voucher')->store('vouchers', 'public');
    // Creamos la URL pública, ej: /storage/vouchers/imagen.jpg
    $urlVoucher = '/storage/' . $path;

    // 3. Crear el registro en la tabla 'pagos'
    Pago::create([
        'pedido_id' => $pedido->id,
        'metodo_pago' => 'Yape',
        'url_voucher' => $urlVoucher,
        'fecha_carga' => now(),
        'estado_validacion' => 'pendiente', // Listo para que el admin lo revise
    ]);

    // 4. Actualizar el estado del pedido
    $pedido->estado = 'pendiente_validacion'; // Cambiamos el estado
    $pedido->save();

    // 5. Redirigir a una página de "Gracias"
    // (Crearemos esta ruta y vista en el siguiente paso)
    return redirect(route('home'))
    ->with('success', '¡Gracias! Tu voucher ha sido enviado para validación.');
}
}