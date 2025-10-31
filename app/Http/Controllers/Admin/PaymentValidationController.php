<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PaymentValidationController extends Controller
{
    /**
     * Muestra la lista de pedidos pendientes de validación.
     */
    public function index()
    {
        // Buscamos todos los pedidos que tengan el estado 'pendiente_validacion'
        $pedidosPendientes = Pedido::with('usuario', 'pago')
                                ->where('estado', 'pendiente_validacion')
                                ->orderBy('fecha_pedido', 'asc')
                                ->get();
        
        return view('admin.pagos.index', [
            'pedidos' => $pedidosPendientes
        ]);
    }

    /**
     * Muestra el detalle de un pedido para validarlo (ver voucher).
     */
    public function show(Pedido $pedido)
    {
        // Cargamos las relaciones para asegurarnos de tenerlas
        $pedido->load('usuario', 'pago', 'detalles_pedido.producto');
        
        return view('admin.pagos.show', [
            'pedido' => $pedido
        ]);
    }

    /**
     * Aprueba un pago.
     */
    public function approve(Pedido $pedido)
    {
        // Actualizamos el estado del pedido
        $pedido->estado = 'procesando'; // 'Procesando' = Pago aceptado, listo para enviar
        $pedido->save();
        
        // Actualizamos el estado del pago
        if ($pedido->pago) {
            $pedido->pago->estado_validacion = 'aprobado';
            $pedido->pago->fecha_validacion = now();
            $pedido->pago->save();
        }
        
        return redirect()->route('admin.pagos.index')
                         ->with('success', '¡Pedido #' . $pedido->id . ' aprobado exitosamente!');
    }

    /**
     * Rechaza un pago.
     */
    public function reject(Request $request, Pedido $pedido)
    {
        $request->validate(['notas_admin' => 'required|string|max:255']);
        
        // Actualizamos el estado del pedido
        $pedido->estado = 'cancelado';
        $pedido->save();
        
        // Actualizamos el estado del pago
        if ($pedido->pago) {
            $pedido->pago->estado_validacion = 'rechazado';
            $pedido->pago->fecha_validacion = now();
            $pedido->pago->notas_admin = $request->notas_admin; // Guardamos el motivo
            $pedido->pago->save();
        }
        
        return redirect()->route('admin.pagos.index')
                         ->with('success', '¡Pedido #' . $pedido->id . ' ha sido rechazado!');
    }
}