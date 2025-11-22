<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPedidoController extends Controller
{
    /**
     * Permite al usuario cancelar su propio pedido.
     */
    public function cancel(Pedido $pedido)
    {
        // 1. SEGURIDAD: Verificar que el pedido pertenezca al usuario logueado
        if ($pedido->usuario_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado.');
        }

        // 2. VALIDAR ESTADO: Solo se puede cancelar si no ha sido procesado aún
        // (Es decir, si está pendiente de pago o pendiente de validación)
        $estadosCancelables = ['pendiente_pago', 'pendiente_validacion'];

        if (!in_array($pedido->estado, $estadosCancelables)) {
            return back()->with('error', 'No se puede cancelar este pedido porque ya está en proceso o completado.');
        }

        // 3. DEVOLVER STOCK (CRUCIAL)
        // Recorremos los productos y los devolvemos al inventario
        foreach ($pedido->detalles_pedido as $detalle) {
            $producto = $detalle->producto;
            if ($producto) {
                $producto->stock += $detalle->cantidad;
                $producto->save();
            }
        }

        // 4. ACTUALIZAR ESTADO
        $pedido->estado = 'cancelado';
        $pedido->save();

        // Opcional: Si había un pago pendiente de validación, lo marcamos como rechazado/cancelado
        if ($pedido->pago) {
            $pedido->pago->estado_validacion = 'rechazado';
            $pedido->pago->notas_admin = 'Cancelado por el usuario';
            $pedido->pago->save();
        }

        return back()->with('success', 'Pedido #' . $pedido->id . ' cancelado exitosamente.');
    }
}