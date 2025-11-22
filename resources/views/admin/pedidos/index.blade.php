@extends('layouts.admin')
@section('content')

<style>
    /* --- Estilos Base (PC) --- */
    .paginacion { display: flex; justify-content: space-between; margin-top: 20px; }
    .paginacion a, .paginacion span { padding: 8px 15px; text-decoration: none; background: #f0f0f0; color: #333; border: 1px solid #ccc; border-radius: 5px; font-weight: bold; }
    .paginacion span[aria-disabled="true"] { background: #fafafa; color: #aaa; cursor: not-allowed; }
    .paginacion a:hover { background: #e0e0e0; }

    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    .admin-table th { background-color: #f2f2f2; }
    .admin-table tr:hover { background-color: #f1f1f1; }

    .status-procesando { color: #007bff; font-weight: bold; }
    .status-cancelado { color: #dc3545; font-weight: bold; }
    .status-completado { color: #28a745; font-weight: bold; }
    .status-pendiente_validacion { color: #e67e22; font-weight: bold; }

    .actions-container { display: flex; gap: 5px; align-items: center; }
    .actions-container form { margin: 0; }
    
    .btn-action { padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 0.9em; border: none; cursor: pointer; color: white; display: inline-block; }
    .btn-detail { background-color: #6c757d; }
    .btn-complete { background-color: #28a745; }
    .btn-delete { background-color: #dc3545; }

    /* --- 📱 RESPONSIVIDAD --- */
    @media (max-width: 768px) {
        /* Formulario de búsqueda vertical */
        form[action="{{ route('admin.pedidos.index') }}"] {
            flex-direction: column;
            align-items: stretch;
        }
        form[action="{{ route('admin.pedidos.index') }}"] input,
        form[action="{{ route('admin.pedidos.index') }}"] select,
        form[action="{{ route('admin.pedidos.index') }}"] button,
        form[action="{{ route('admin.pedidos.index') }}"] a {
            width: 100%;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        /* Tabla transformada en tarjetas */
        .admin-table thead { display: none; }
        .admin-table, .admin-table tbody, .admin-table tr, .admin-table td { display: block; width: 100%; }
        
        .admin-table tr {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #eee;
        }
        
        .admin-table td {
            padding: 10px 0;
            border: none;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
        }
        .admin-table td:last-child { border-bottom: none; display: block; margin-top: 10px; }
        
        .admin-table td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #777;
            margin-right: 10px;
            text-align: left;
        }
        
        /* Botones alineados a la derecha en móvil */
        .actions-container {
            justify-content: flex-end;
            flex-wrap: wrap;
        }
    }
</style>

<div class="admin-header">
    <h1>Historial de Pedidos</h1>
</div>

<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
    <form action="{{ route('admin.pedidos.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
        <input type="text" name="search" placeholder="Buscar por ID o Cliente..." 
               value="{{ request('search') }}" 
               style="padding: 10px; flex-grow: 1; border: 1px solid #ccc; border-radius: 4px;">

        <select name="estado" style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="">Todos (Menos Pendientes)</option>
            <option value="procesando" {{ request('estado') == 'procesando' ? 'selected' : '' }}>Procesando</option>
            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
            <option value="pendiente_validacion" {{ request('estado') == 'pendiente_validacion' ? 'selected' : '' }}>Pendiente Validación</option>
        </select>

        <button type="submit" style="padding: 10px 15px; border: none; background: #007bff; color: white; border-radius: 4px; cursor: pointer;">Buscar</button>
        <a href="{{ route('admin.pedidos.index') }}" style="padding: 10px 15px; text-decoration: none; background: #6c757d; color: white; border-radius: 4px; text-align: center;">Limpiar</a>
    </form>
</div>

<div style="background: white; padding: 20px; border-radius: 8px;">
    <table class="admin-table">
        @php
            $query = request()->query();
            // Links de ordenamiento (Simplificados para brevedad, usan tu lógica)
            $sort = fn($f) => route('admin.pedidos.index', array_merge($query, ['sort_by' => $f, 'order' => ($sort_by == $f && $order == 'asc') ? 'desc' : 'asc']));
            $arrow = fn($f) => ($sort_by == $f) ? ($order == 'asc' ? '&#9650;' : '&#9660;') : '';
        @endphp

        <thead>
            <tr>
                <th><a href="{{ $sort('id') }}" style="color: black; text-decoration: none;">ID {!! $arrow('id') !!}</a></th>
                <th><a href="{{ $sort('nombre_completo') }}" style="color: black; text-decoration: none;">Cliente {!! $arrow('nombre_completo') !!}</a></th>
                <th><a href="{{ $sort('monto_total') }}" style="color: black; text-decoration: none;">Total {!! $arrow('monto_total') !!}</a></th>
                <th><a href="{{ $sort('estado') }}" style="color: black; text-decoration: none;">Estado {!! $arrow('estado') !!}</a></th>
                <th><a href="{{ $sort('fecha_pedido') }}" style="color: black; text-decoration: none;">Fecha {!! $arrow('fecha_pedido') !!}</a></th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $pedido)
            <tr>
                <td data-label="ID">{{ $pedido->id }}</td>
                <td data-label="Cliente">{{ $pedido->nombre_completo }}</td>
                <td data-label="Total">S/ {{ number_format($pedido->monto_total, 2) }}</td>
                <td data-label="Estado">
                    <span class="status-{{ $pedido->estado }}">
                        {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                    </span>
                </td>
                <td data-label="Fecha">{{ $pedido->fecha_pedido }}</td>
                <td>
                    <div class="actions-container">
                        <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn-action btn-detail">Ver</a>

                        @if ($pedido->estado == 'procesando')
                            <form action="{{ route('admin.pedidos.complete', $pedido->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action btn-complete" onclick="return confirm('¿Completar?')">✓</button>
                            </form>
                        @endif

                        <form action="{{ route('admin.pedidos.destroy', $pedido->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('¿Eliminar?')">✕</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No hay pedidos en el historial.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top: 20px;">
    {{ $pedidos->appends(request()->query())->links() }}
</div>

@endsection