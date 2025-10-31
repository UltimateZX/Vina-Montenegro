<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // ¡Importante!

class OrderController extends Controller
{
    /**
     * Muestra el historial de pedidos (con filtros, búsqueda y paginación).
     */
    public function index(Request $request)
    {
        // Empezamos la consulta con un JOIN para poder buscar por el nombre del usuario
        $query = Pedido::join('usuarios', 'pedidos.usuario_id', '=', 'usuarios.id')
                       ->select('pedidos.*', 'usuarios.nombre_completo');

        // LÓGICA DE BÚSQUEDA
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('pedidos.id', 'LIKE', "%{$search}%")
                  ->orWhere('usuarios.nombre_completo', 'LIKE', "%{$search}%");
            });
        }

        // LÓGICA DE FILTRO POR ESTADO
        if ($request->filled('estado')) {
            $query->where('pedidos.estado', $request->input('estado'));
        } else {
             $query->where('pedidos.estado', '!=', 'pendiente_validacion')
                   ->where('pedidos.estado', '!=', 'pendiente_pago');
        }

        // LÓGICA DE ORDENAMIENTO
        $sort_by = $request->input('sort_by', 'fecha_pedido');
        $order = $request->input('order', 'desc');
        if (!in_array($sort_by, ['id', 'nombre_completo', 'monto_total', 'estado', 'fecha_pedido'])) {
            $sort_by = 'fecha_pedido';
        }
        $query->orderBy($sort_by, $order);

        // LÓGICA DE PAGINACIÓN
        $pedidos = $query->paginate(10);

        return view('admin.pedidos.index', [
            'pedidos' => $pedidos,
            'sort_by' => $sort_by,
            'order' => $order
        ]);
    }

    /**
     * ¡ESTE ES EL MÉTODO QUE FALTABA!
     * Muestra el detalle de un pedido histórico.
     */
    public function show(Pedido $pedido)
    {
        // Cargamos todas las relaciones que la vista necesita
        $pedido->load('usuario', 'pago', 'detalles_pedido.producto');
        
        return view('admin.pedidos.show', [
            'pedido' => $pedido
        ]);
    }

    /**
     * Marca un pedido como 'completado'.
     */
    public function complete(Pedido $pedido)
    {
        if ($pedido->estado == 'procesando') {
            $pedido->estado = 'completado';
            $pedido->save();
            return back()->with('success', '¡Pedido #' . $pedido->id . ' marcado como completado!');
        }
        
        return back()->with('error', 'Este pedido no se puede marcar como completado.');
    }

    /**
     * Borra permanentemente un pedido y sus archivos.
     */
    public function destroy(Pedido $pedido)
    {
        try {
            if ($pedido->pago && $pedido->pago->url_voucher) {
                $oldPath = str_replace('/storage/', '', $pedido->pago->url_voucher);
                Storage::disk('public')->delete($oldPath);
            }
            if ($pedido->pago) {
                $pedido->pago->delete();
            }
            $pedido->detalles_pedido()->delete();
            $pedido->delete();
            
            return back()->with('success', '¡Pedido #' . $pedido->id . ' eliminado permanentemente!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al borrar el pedido: ' . $e->getMessage());
        }
    }
}