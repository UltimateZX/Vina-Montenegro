@extends('layouts.admin')
@section('content')

<style>
    .paginacion {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }
    .paginacion a, .paginacion span {
        padding: 8px 15px;
        text-decoration: none;
        background: #f0f0f0;
        color: #333;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-weight: bold;
    }
    .paginacion span[aria-disabled="true"] {
        background: #fafafa;
        color: #aaa;
        cursor: not-allowed;
    }
    .paginacion a:hover {
        background: #e0e0e0;
    }

    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    .admin-table th { background-color: #f2f2f2; }
    .admin-table tr:hover { background-color: #f1f1f1; }

    .status-procesando { color: #007bff; font-weight: bold; }
    .status-cancelado { color: #dc3545; font-weight: bold; }
    .status-completado { color: #28a745; font-weight: bold; }

    /* --- ¡NUEVOS ESTILOS PARA ORDENAR LOS BOTONES! --- */

    /* Contenedor para alinear los botones */
    .actions-container {
        display: flex;
        gap: 5px; /* Espacio entre botones */
        align-items: center;
    }
    .actions-container form {
        margin: 0; /* Quita el margen por defecto del formulario */
    }

    /* Clase común para todos los botones de acción */
    .btn-action {
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 0.9em;
        border: none;
        cursor: pointer;
        color: white;
        display: inline-block; /* Asegura que el <a> se comporte como botón */
    }
    .btn-detail { background-color: #6c757d; } /* Gris */
    .btn-complete { background-color: #28a745; } /* Verde */
    .btn-delete { background-color: #dc3545; } /* Rojo */
</style>

<div class="admin-header">
    <h1>Historial de Pedidos</h1>
</div>

<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
    <form action="{{ route('admin.pedidos.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">

        <input type="text" name="search" placeholder="Buscar por ID o Cliente..." 
               value="{{ request('search') }}" 
               style="padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">

        <select name="estado" style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="">Todos (Menos Pendientes)</option>
            <option value="procesando" {{ request('estado') == 'procesando' ? 'selected' : '' }}>Procesando</option>
            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
            <option value="pendiente_validacion" {{ request('estado') == 'pendiente_validacion' ? 'selected' : '' }}>Pendiente Validación</option>
        </select>

        <button type="submit" style="padding: 10px 15px; border: none; background: #007bff; color: white; border-radius: 4px; cursor: pointer;">Buscar</button>
        <a href="{{ route('admin.pedidos.index') }}" style="padding: 10px 15px; text-decoration: none; background: #6c757d; color: white; border-radius: 4px;">Limpiar</a>
    </form>
</div>

<div style="background: white; padding: 20px; border-radius: 8px;">
    <table class="admin-table">
        @php
    // Creamos las URLs para ordenar
    $query = request()->query();
    $link_id = route('admin.pedidos.index', array_merge($query, ['sort_by' => 'id', 'order' => ($sort_by == 'id' && $order == 'asc') ? 'desc' : 'asc']));
    $link_cliente = route('admin.pedidos.index', array_merge($query, ['sort_by' => 'nombre_completo', 'order' => ($sort_by == 'nombre_completo' && $order == 'asc') ? 'desc' : 'asc']));
    $link_monto = route('admin.pedidos.index', array_merge($query, ['sort_by' => 'monto_total', 'order' => ($sort_by == 'monto_total' && $order == 'asc') ? 'desc' : 'asc']));
    $link_estado = route('admin.pedidos.index', array_merge($query, ['sort_by' => 'estado', 'order' => ($sort_by == 'estado' && $order == 'asc') ? 'desc' : 'asc']));
    $link_fecha = route('admin.pedidos.index', array_merge($query, ['sort_by' => 'fecha_pedido', 'order' => ($sort_by == 'fecha_pedido' && $order == 'asc') ? 'desc' : 'asc']));
@endphp

<thead>
    <tr>
        <th><a href="{{ $link_id }}" style="text-decoration: none; color: black;">
            ID @if ($sort_by == 'id') <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span> @endif
        </a></th>
        <th><a href="{{ $link_cliente }}" style="text-decoration: none; color: black;">
            Cliente @if ($sort_by == 'nombre_completo') <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span> @endif
        </a></th>
        <th><a href="{{ $link_monto }}" style="text-decoration: none; color: black;">
            Monto Total @if ($sort_by == 'monto_total') <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span> @endif
        </a></th>
        <th><a href="{{ $link_estado }}" style="text-decoration: none; color: black;">
            Estado @if ($sort_by == 'estado') <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span> @endif
        </a></th>
        <th><a href="{{ $link_fecha }}" style="text-decoration: none; color: black;">
            Fecha del Pedido @if ($sort_by == 'fecha_pedido') <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span> @endif
        </a></th>
        <th>Acciones</th>
    </tr>
</thead>
        <tbody>
            @forelse ($pedidos as $pedido)
            <tr>
                <td>{{ $pedido->id }}</td>
                <td>{{ $pedido->nombre_completo }}</td>
                <td>S/ {{ number_format($pedido->monto_total, 2) }}</td>
                <td>
                    <span class="status-{{ $pedido->estado }}">
                        {{ ucfirst($pedido->estado) }}
                    </span>
                </td>
                <td>{{ $pedido->fecha_pedido }}</td>
                <td>
                <div class="actions-container">

                    <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn-action btn-detail">Ver Detalles</a>

                    @if ($pedido->estado == 'procesando')
                        <form action="{{ route('admin.pedidos.complete', $pedido->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-action btn-complete"
                                    onclick="return confirm('¿Marcar este pedido como COMPLETADO?')">
                                Completar
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.pedidos.destroy', $pedido->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete"
                                onclick="return confirm('¡PELIGRO! ¿Estás seguro de que quieres ELIMINAR este pedido? ...')">
                            Borrar
                        </button>
                    </form>
                </div>
            </td>
        </tr>
            @empty
            <tr>
                <td colspan="6">No hay pedidos en el historial.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top: 20px;">
    {{ $pedidos->appends(request()->query())->links() }}
</div>

@endsection