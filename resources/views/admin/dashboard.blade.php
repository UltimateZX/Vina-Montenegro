@extends('layouts.admin')

@section('content')

<style>
    /* Estilos para las tarjetas */
    .kpi-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .kpi-card {
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .kpi-card .value {
        font-size: 2.5em;
        font-weight: bold;
        color: #b42a6a;
    }
    .kpi-card .title {
        font-size: 1em;
        color: #555;
        margin-top: 5px;
    }
    .kpi-card .link {
        margin-top: 15px;
        font-size: 0.9em;
        text-decoration: none;
        color: #007bff;
    }

    /* Estilos para las tablas */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .admin-table th, .admin-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    .admin-table th { background-color: #f2f2f2; }
    .admin-table tr:hover { background-color: #f1f1f1; }
    
    /* Estilo para Stock Bajo */
    .stock-low {
        color: #dc3545;
        font-weight: bold;
    }
    .btn-edit {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 0.9em;
    }
    
    /* Estilos para los Estados de Pedidos */
    .status-procesando { color: #007bff; font-weight: bold; }
    .status-cancelado { color: #dc3545; font-weight: bold; }
    .status-completado { color: #28a745; font-weight: bold; }
    .status-pendiente_validacion { color: #ffc107; font-weight: bold; }
    .status-pendiente_pago { color: #6c757d; font-weight: bold; }
    .btn-detail {
        background-color: #6c757d;
        color: white;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 0.9em;
    }
</style>

<div class="admin-header">
    <h1>Dashboard</h1>
</div>

<div class="kpi-container">
    
    <div class="kpi-card">
        <div class="value">S/ {{ number_format($ingresosTotales, 2) }}</div>
        <div class="title">Ingresos Totales (Aprobados)</div>
        <div class="link">
            <a href="{{ route('admin.pedidos.index') }}">Ver Historial &rarr;</a>
        </div>
    </div>
    
    <div class="kpi-card">
        <div class="value">{{ $pedidosPendientes }}</div>
        <div class="title">Pedidos Pendientes de Validación</div>
        <div class="link">
            <a href="{{ route('admin.pagos.index') }}">Ir a Validar &rarr;</a>
        </div>
    </div>
    
    <div class="kpi-card">
        <div class="value">{{ $nuevosClientes }}</div>
        <div class="title">Nuevos Clientes (Hoy)</div>
        <div class="link">
            <a href="{{ route('admin.usuarios.index') }}">Gestionar Usuarios &rarr;</a>
        </div>
    </div>
    
</div>

<div style="background: white; padding: 20px; border-radius: 8px; margin-top: 30px;">
    <h2 style="margin-top: 0;">Alerta: Productos con Bajo Stock (10 o menos)</h2>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Stock Restante</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productosBajoStock as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td class="stock-low">{{ $producto->stock }}</td>
                    <td>
                        <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn-edit">
                            Editar (Reponer Stock)
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">¡Excelente! No hay productos con bajo stock.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="background: white; padding: 20px; border-radius: 8px; margin-top: 30px;">
    <h2 style="margin-top: 0;">Última Actividad (Pedidos Recientes)</h2>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Monto Total</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ultimosPedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>{{ $pedido->usuario->nombre_completo }}</td>
                    <td>S/ {{ number_format($pedido->monto_total, 2) }}</td>
                    <td>
                        <span class="status-{{ $pedido->estado }}">
                            {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn-detail">
                            Ver Detalles
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aún no se han realizado pedidos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection