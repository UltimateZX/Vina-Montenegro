<?php
namespace App\Http\Controllers;

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
}