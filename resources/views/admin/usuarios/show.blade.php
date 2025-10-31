@extends('layouts.admin')

@section('content')
<style>
    /* Reutilizamos los estilos de la tabla */
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    .admin-table th { background-color: #f2f2f2; }
    .admin-table tr:hover { background-color: #f1f1f1; }

    .details-box { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .details-box h3 { margin-top: 0; }
    .details-box p { margin: 5px 0; }

    /* Estilos para los diferentes estados */
    .status-procesando { color: #007bff; font-weight: bold; }
    .status-cancelado { color: #dc3545; font-weight: bold; }
    .status-completado { color: #28a745; font-weight: bold; }
    .status-pendiente_validacion { color: #ffc107; font-weight: bold; }
    .status-pendiente_pago { color: #6c757d; font-weight: bold; }
    .btn-detail {
        background-color: #6c757d; color: white; padding: 5px 10px;
        text-decoration: none; border-radius: 5px; font-size: 0.9em;
    }
</style>

<div class="admin-header">
    <h1>Detalle del Usuario</h1>
    <a href="{{ route('admin.usuarios.index') }}" style="font-size: 0.9em;">&larr; Volver a la lista de Usuarios</a>
</div>

<div class="details-box">
    <h3>{{ $usuario->nombre_completo }}</h3>
    <p><strong>Email:</strong> {{ $usuario->email }}</p>
    <p><strong>Rol:</strong> {{ ucfirst($usuario->rol) }}</p>
    <p><strong>Miembro desde:</strong> {{ $usuario->fecha_registro }}</p>
</div>

<div class="details-box">
    <h3>Historial de Pedidos de este Usuario</h3>

    @if($pedidos->isEmpty())
        <p>Este usuario aún no ha realizado ningún pedido.</p>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Monto Total</th>
                    <th>Estado</th>
                    <th>Fecha del Pedido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>S/ {{ number_format($pedido->monto_total, 2) }}</td>
                    <td>
                        <span class="status-{{ $pedido->estado }}">
                            {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                        </span>
                    </td>
                    <td>{{ $pedido->fecha_pedido }}</td>
                    <td>
                        <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn-detail">Ver Detalles</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection