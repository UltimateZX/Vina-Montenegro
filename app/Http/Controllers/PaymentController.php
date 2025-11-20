<?php
namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Pedido $pedido)
    {
        if ($pedido->usuario_id !== Auth::id()) {
            abort(403);
        }

        if ($pedido->estado !== 'pendiente_pago') {
            return redirect(route('home'))
                   ->with('error', 'Este pedido ya ha sido procesado.');
        }

        return view('payment.index', [
            'pedido' => $pedido
        ]);
    }

    public function storeVoucher(Request $request, Pedido $pedido)
    {
        if ($pedido->usuario_id !== Auth::id()) {
            abort(403);
        }
        if ($pedido->estado !== 'pendiente_pago') {
            return redirect(route('home'))->with('error', 'Este pedido ya fue procesado.');
        }

        $request->validate([
            'voucher' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4048' // Aumenté un poco el tamaño por si acaso
        ]);

        $voucherBase64 = null;

        // --- CONVERSIÓN A BASE64 (Igual que en productos) ---
        if ($request->hasFile('voucher')) {
            $file = $request->file('voucher');
            $path = $file->getRealPath();
            $imgData = file_get_contents($path);
            $voucherBase64 = 'data:' . $file->getClientMimeType() . ';base64,' . base64_encode($imgData);
        }

        Pago::create([
            'pedido_id' => $pedido->id,
            'metodo_pago' => 'Yape',
            'url_voucher' => $voucherBase64, // Guardamos el texto gigante aquí
            'fecha_carga' => now(),
            'estado_validacion' => 'pendiente',
        ]);

        $pedido->estado = 'pendiente_validacion';
        $pedido->save();

        return redirect(route('home'))
        ->with('success', '¡Gracias! Tu voucher ha sido enviado para validación.');
    }
}