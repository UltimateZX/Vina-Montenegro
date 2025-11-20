@extends('layouts.admin')

@section('content')

<style>
    /* --- TARJETAS KPI --- */
    .kpi-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 Columnas por defecto (PC) */
        gap: 20px;
        margin-bottom: 30px;
    }
    .kpi-card {
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .kpi-card:hover { transform: translateY(-3px); }
    
    .kpi-card .value {
        font-size: 2.5em;
        font-weight: bold;
        color: #b42a6a;
        line-height: 1.2;
    }
    .kpi-card .title {
        font-size: 1em;
        color: #555;
        margin-top: 5px;
        font-weight: 500;
    }
    .kpi-card .link {
        margin-top: 15px;
        font-size: 0.9em;
    }
    .kpi-card .link a { text-decoration: none; color: #007bff; font-weight: bold; }

    /* --- TABLAS --- */
    .table-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-top: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .table-responsive {
        width: 100%;
        overflow-x: auto; /* Permite scroll lateral en celular */
        border: 1px solid #eee;
        border-radius: 6px;
    }
    
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px; /* Fuerza el ancho mínimo para activar el scroll */
    }
    .admin-table th, .admin-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    .admin-table th { background-color: #f8f9fa; color: #555; font-weight: 600; font-size: 0.9em; text-transform: uppercase; }
    .admin-table tr:last-child td { border-bottom: none; }
    .admin-table tr:hover { background-color: #fcfcfc; }
    
    /* Estados y Utilidades */
    .stock-low { color: #dc3545; font-weight: bold; background: #fff5f5; padding: 2px 6px; border-radius: 4px; }
    .btn-edit, .btn-detail {
        padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.85em; font-weight: bold; display: inline-block;
    }
    .btn-edit { background-color: #007bff; color: white; }
    .btn-detail { background-color: #6c757d; color: white; }

    .status-procesando { color: #007bff; font-weight: bold; }
    .status-cancelado { color: #dc3545; font-weight: bold; }
    .status-completado { color: #28a745; font-weight: bold; }
    .status-pendiente_validacion { color: #e67e22; font-weight: bold; }
    .status-pendiente_pago { color: #6c757d; font-weight: bold; }

    /* --- 📱 RESPONSIVIDAD --- */
    @media (max-width: 900px) {
        .kpi-container {
            grid-template-columns: repeat(2, 1fr); /* 2 columnas en tablet */
        }
    }
    @media (max-width: 600px) {
        .kpi-container {
            grid-template-columns: 1fr; /* 1 columna en celular */
            gap: 15px;
        }
        .kpi-card { padding: 20px; }
        .kpi-card .value { font-size: 2em; }
        
        .table-card { padding: 15px; margin-top: 20px; }
        .table-card h2 { font-size: 1.2em; margin-bottom: 15px; }
    }
</style>

<div class="admin-header" style="margin-bottom: 25px;">
    <h1 style="margin: 0;">Dashboard</h1>
    <p style="margin: 5px 0 0; color: #777;">Resumen general de tu negocio.</p>
</div>

<!-- TARJETAS KPI -->
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

<!-- TABLA 1: STOCK BAJO -->
<div class="table-card">
    <h2 style="margin-top: 0; color: #dc3545; display: flex; align-items: center; gap: 8px;">
        ⚠️ Alerta: Productos con Bajo Stock (10 o menos)
    </h2>
    
    <div class="table-responsive">
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
                        <td><span class="stock-low">{{ $producto->stock }} un.</span></td>
                        <td>
                            <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn-edit">
                                Reponer Stock
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #28a745; padding: 20px;">
                            ✅ ¡Excelente! Todo el inventario está saludable.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- TABLA 2: ÚLTIMOS PEDIDOS -->
<div class="table-card">
    <h2 style="margin-top: 0; display: flex; align-items: center; gap: 8px;">
        🛒 Última Actividad (Pedidos Recientes)
    </h2>
    
    <div class="table-responsive">
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
                        <td><strong>#{{ $pedido->id }}</strong></td>
                        <td>{{ $pedido->usuario->nombre_completo }}</td>
                        <td style="font-weight: bold;">S/ {{ number_format($pedido->monto_total, 2) }}</td>
                        <td>
                            <span class="status-{{ $pedido->estado }}">
                                {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn-detail">
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px; color: #777;">
                            Aún no se han realizado pedidos recientes.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection