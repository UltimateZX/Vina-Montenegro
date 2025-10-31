<?php

namespace App\Http\Controllers\Admin;

use App\Models\Producto;
use App\Http\Controllers\Controller;
use App\Models\Pedido;  // <-- ¡Importante!
use App\Models\Usuario; // <-- ¡Importante!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- ¡Importante!

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard del admin con estadísticas.
     */
    public function index()
{
    // 1. Tarjeta: Ingresos Totales
    $ingresosTotales = Pedido::whereIn('estado', ['procesando', 'completado'])
                            ->sum('monto_total');

    // 2. Tarjeta: Pedidos Pendientes
    $pedidosPendientes = Pedido::where('estado', 'pendiente_validacion')
                            ->count();

    // 3. Tarjeta: Nuevos Clientes (Hoy)
    $nuevosClientes = Usuario::whereDate('fecha_registro', today())
                            ->count();

    // 4. Tabla: Productos con Bajo Stock
    $productosBajoStock = Producto::where('stock', '<=', 10)
                                ->orderBy('stock', 'asc')
                                ->get();

    // 5. ¡NUEVO! Feed: Últimos 5 Pedidos
    //    Busca los 5 pedidos más recientes, sin importar su estado
    $ultimosPedidos = Pedido::with('usuario') // Carga la info del cliente
                            ->orderBy('fecha_pedido', 'desc') // Los más nuevos primero
                            ->take(5) // Solo toma 5
                            ->get();

    // 6. Enviamos todos los datos a la vista
    return view('admin.dashboard', [
        'ingresosTotales' => $ingresosTotales,
        'pedidosPendientes' => $pedidosPendientes,
        'nuevosClientes' => $nuevosClientes,
        'productosBajoStock' => $productosBajoStock,
        'ultimosPedidos' => $ultimosPedidos // <-- ¡Añadimos la nueva variable!
    ]);
}
}