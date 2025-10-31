@extends('layouts.admin')

@section('content')
<style>
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    .admin-table th { background-color: #f2f2f2; }
    .admin-table tr:hover { background-color: #f1f1f1; }

    /* Estilos para los mensajes de éxito/error */
    .alert {
        padding: 15px; margin-bottom: 20px; border: 1px solid transparent;
        border-radius: 4px;
    }
    .alert-success {
        background-color: #d4edda; color: #155724; border-color: #c3e6cb;
    }
    .alert-danger {
        background-color: #f8d7da; color: #721c24; border-color: #f5c6cb;
    }

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
        white-space: nowrap; 
    }

    /* Colores específicos */
    .btn-make-admin { background-color: #28a745; } /* Verde */
    .btn-make-client { background-color: #ffc107; color: #333; } /* Amarillo */
    .btn-delete { background-color: #dc3545; } /* Rojo */
</style>

<div class="admin-header">
    <h1>Gestionar Usuarios</h1>
</div>

<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
    <form action="{{ route('admin.usuarios.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">

        <input type="text" name="search" placeholder="Buscar por nombre o email..." 
               value="{{ request('search') }}" 
               style="padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">

        <select name="role" style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="">Todos los Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Sólo Admin</option>
            <option value="cliente" {{ request('role') == 'cliente' ? 'selected' : '' }}>Sólo Clientes</option>
        </select>

        <button type="submit" style="padding: 10px 15px; border: none; background: #007bff; color: white; border-radius: 4px; cursor: pointer;">Buscar</button>
        <a href="{{ route('admin.usuarios.index') }}" style="padding: 10px 15px; text-decoration: none; background: #6c757d; color: white; border-radius: 4px;">Limpiar</a>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div style="background: white; padding: 20px; border-radius: 8px;">
    <table class="admin-table">
        @php
    $link_id = route('admin.usuarios.index', array_merge(request()->query(), ['sort_by' => 'id', 'order' => ($sort_by == 'id' && $order == 'asc') ? 'desc' : 'asc']));
    $link_nombre = route('admin.usuarios.index', array_merge(request()->query(), ['sort_by' => 'nombre_completo', 'order' => ($sort_by == 'nombre_completo' && $order == 'asc') ? 'desc' : 'asc']));
    $link_email = route('admin.usuarios.index', array_merge(request()->query(), ['sort_by' => 'email', 'order' => ($sort_by == 'email' && $order == 'asc') ? 'desc' : 'asc']));
    $link_rol = route('admin.usuarios.index', array_merge(request()->query(), ['sort_by' => 'rol', 'order' => ($sort_by == 'rol' && $order == 'asc') ? 'desc' : 'asc']));
@endphp

<thead>
    <tr>
        <th>
            <a href="{{ $link_id }}" style="text-decoration: none; color: black;">
                ID
                @if ($sort_by == 'id')
                    <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                @endif
            </a>
        </th>
        <th>
            <a href="{{ $link_nombre }}" style="text-decoration: none; color: black;">
                Cliente
                @if ($sort_by == 'nombre_completo')
                    <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                @endif
            </a>
        </th>
        <th>
            <a href="{{ $link_email }}" style="text-decoration: none; color: black;">
                Email
                @if ($sort_by == 'email')
                    <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                @endif
            </a>
        </th>
        <th>
            <a href="{{ $link_rol }}" style="text-decoration: none; color: black;">
                Rol Actual
                @if ($sort_by == 'rol')
                    <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                @endif
            </a>
        </th>
        <th>Acción</th> </tr>
</thead>
        <tbody>
            @foreach ($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>
                <a href="{{ route('admin.usuarios.show', $usuario) }}" style="font-weight: bold; text-decoration: none;">
                {{ $usuario->nombre_completo }}
                </a>
                </td>
                <td>{{ $usuario->email }}</td>
                <td>
                    <strong style="color: {{ $usuario->rol == 'admin' ? '#dc3545' : '#007bff' }};">
                        {{ ucfirst($usuario->rol) }}
                    </strong>
                </td>
                <td>
                @if ($usuario->id === Auth::id())
                    <span style="color: #6c757d;">(Tu usuario)</span>
                @else
                    <div class="actions-container">

                        <form action="{{ route('admin.usuarios.toggleRole', $usuario) }}" method="POST">
                            @csrf
                            @if ($usuario->rol == 'admin')
                                <button type="submit" class="btn-action btn-make-client">Hacer Cliente</button>
                            @else
                                <button type="submit" class="btn-action btn-make-admin">Hacer Admin</button>
                            @endif
                        </form>

                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete"
                                    onclick="return confirm('¿Estás seguro de que quieres ELIMINAR a este usuario? ...')">
                                Borrar
                            </button>
                        </form>
                    </div>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>
    </table>
</div>
<div style="margin-top: 20px;">
    {{ $usuarios->appends(request()->query())->links() }}
</div>

@endsection
