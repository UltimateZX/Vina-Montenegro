@extends('layouts.admin')

@section('content')
<style>
    /* --- Estilos Base --- */
    .details-container { display: grid; gap: 20px; }
    
    .info-card {
        background: white; padding: 25px; border-radius: 8px; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .info-card h3 { margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px; color: #333; font-size: 1.3em; }
    .info-card p { margin: 10px 0; font-size: 1em; color: #555; line-height: 1.6; }
    .info-card strong { color: #333; }

    /* Tabla de Pedidos */
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .admin-table th, .admin-table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
    .admin-table th { background-color: #f9f9f9; color: #555; font-weight: 600; font-size: 0.9em; }
    
    .btn-detail {
        background-color: #6c757d; color: white; padding: 6px 12px;
        text-decoration: none; border-radius: 4px; font-size: 0.85em; font-weight: bold;
        display: inline-block;
    }

    /* Estados */
    .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: bold; }
    .status-procesando { background: #e7f1ff; color: #007bff; }
    .status-cancelado { background: #fce8ea; color: #dc3545; }
    .status-completado { background: #e6f4ea; color: #28a745; }
    .status-pendiente_validacion { background: #fdf3e5; color: #e67e22; }

    /* --- 📱 MÓVIL --- */
    @media (max-width: 768px) {
        .admin-table thead { display: none; }
        .admin-table, .admin-table tbody, .admin-table tr, .admin-table td { display: block; width: 100%; }
        
        .admin-table tr {
            border: 1px solid #eee; border-radius: 8px; margin-bottom: 15px; padding: 15px;
            background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        
        .admin-table td {
            padding: 8px 0; border: none; border-bottom: 1px solid #f9f9f9;
            display: flex; justify-content: space-between; align-items: center; text-align: right;
        }
        .admin-table td:last-child { border-bottom: none; padding-top: 12px; justify-content: flex-end; }
        
        .admin-table td::before {
            content: attr(data-label); font-weight: bold; color: #777; margin-right: 10px;
        }
    }
</style>

<div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h1 style="margin: 0; font-size: 1.8em;">Detalle del Usuario</h1>
    <a href="{{ route('admin.usuarios.index') }}" style="padding: 8px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">&larr; Volver</a>
</div>

<div class="details-container">
    
    <!-- Tarjeta de Información -->
    <div class="info-card">
        <h3>Datos Personales</h3>
        <p><strong>Nombre:</strong> {{ $usuario->nombre_completo }}</p>
        <p><strong>Email:</strong> <a href="mailto:{{ $usuario->email }}" style="color: #007bff;">{{ $usuario->email }}</a></p>
        <p><strong>Rol:</strong> 
            <span class="status-badge {{ $usuario->rol == 'admin' ? 'status-cancelado' : 'status-procesando' }}">
                {{ ucfirst($usuario->rol) }}
            </span>
        </p>
        <p><strong>Miembro desde:</strong> {{ \Carbon\Carbon::parse($usuario->fecha_registro)->format('d/m/Y') }}</p>
    </div>

    <!-- Historial de Pedidos -->
    <div class="info-card">
        <h3>Historial de Pedidos ({{ $pedidos->count() }})</h3>

        @if($pedidos->isEmpty())
            <div style="text-align: center; padding: 20px; color: #777;">
                <p>Este usuario aún no ha realizado ningún pedido.</p>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Monto Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                    <tr>
                        <td data-label="ID"><strong>#{{ $pedido->id }}</strong></td>
                        <td data-label="Monto" style="font-weight: bold; color: #b42a6a;">S/ {{ number_format($pedido->monto_total, 2) }}</td>
                        <td data-label="Estado">
                            <span class="status-badge status-{{ $pedido->estado }}">
                                {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                            </span>
                        </td>
                        <td data-label="Fecha">{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn-detail">Ver Detalles</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
</div>
@endsection