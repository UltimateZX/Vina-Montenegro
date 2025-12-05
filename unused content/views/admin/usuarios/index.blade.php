@extends('layouts.admin')

@section('content')
<style>
    /* --- ESTILOS GENERALES (PC) --- */
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .admin-table th, .admin-table td { border-bottom: 1px solid #eee; padding: 15px; text-align: left; vertical-align: middle; }
    .admin-table th { background-color: #f9f9f9; color: #555; font-weight: 600; text-transform: uppercase; font-size: 0.85em; }
    .admin-table tr:hover { background-color: #fcfcfc; }

    .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; border: 1px solid transparent; }
    .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
    .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }

    .actions-container { display: flex; gap: 8px; align-items: center; }
    .actions-container form { margin: 0; }

    .btn-action {
        padding: 6px 12px; text-decoration: none; border-radius: 5px; font-size: 0.85em;
        border: none; cursor: pointer; color: white; display: inline-block; font-weight: bold;
        transition: opacity 0.2s;
    }
    .btn-action:hover { opacity: 0.9; }
    .btn-make-admin { background-color: #28a745; }
    .btn-make-client { background-color: #ffc107; color: #333; }
    .btn-delete { background-color: #dc3545; }

    /* --- 📱 RESPONSIVIDAD (CELULAR) --- */
    @media (max-width: 768px) {
        /* Formulario de Búsqueda Apilado */
        form[action="{{ route('admin.usuarios.index') }}"] {
            flex-direction: column; align-items: stretch;
        }
        form[action="{{ route('admin.usuarios.index') }}"] input,
        form[action="{{ route('admin.usuarios.index') }}"] select,
        form[action="{{ route('admin.usuarios.index') }}"] button,
        form[action="{{ route('admin.usuarios.index') }}"] a {
            width: 100%; margin-bottom: 10px; box-sizing: border-box;
        }

        /* Tabla -> Tarjetas */
        .admin-table thead { display: none; }
        .admin-table, .admin-table tbody, .admin-table tr, .admin-table td { display: block; width: 100%; }
        
        .admin-table tr {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        
        .admin-table td {
            padding: 8px 0;
            border: none;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
        }
        
        .admin-table td:last-child { 
            border-bottom: none; 
            display: block; 
            margin-top: 10px; 
            padding-top: 10px; 
        }

        /* Etiquetas simuladas */
        .admin-table td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #777;
            margin-right: 10px;
            text-align: left;
        }
        
        /* Botones en móvil */
        .actions-container {
            flex-direction: column;
            width: 100%;
            gap: 10px;
        }
        .actions-container form, .actions-container button { width: 100%; }
        .btn-action { width: 100%; padding: 10px; }
    }
</style>

<div class="admin-header" style="margin-bottom: 20px;">
    <h1 style="margin: 0; font-size: 1.8em; color: #333;">Gestionar Usuarios</h1>
</div>

<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <form action="{{ route('admin.usuarios.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
        <input type="text" name="search" placeholder="Buscar por nombre o email..." 
               value="{{ request('search') }}" 
               style="padding: 10px; flex-grow: 1; border: 1px solid #ccc; border-radius: 4px;">

        <select name="role" style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="">Todos los Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Sólo Admin</option>
            <option value="cliente" {{ request('role') == 'cliente' ? 'selected' : '' }}>Sólo Clientes</option>
        </select>

        <button type="submit" style="padding: 10px 15px; border: none; background: #007bff; color: white; border-radius: 4px; cursor: pointer; font-weight: bold;">Buscar</button>
        <a href="{{ route('admin.usuarios.index') }}" style="padding: 10px 15px; text-decoration: none; background: #6c757d; color: white; border-radius: 4px; text-align: center;">Limpiar</a>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <table class="admin-table">
        @php
            $query = request()->query();
            // Helper para ordenar
            $sort = fn($f) => route('admin.usuarios.index', array_merge($query, ['sort_by' => $f, 'order' => ($sort_by == $f && $order == 'asc') ? 'desc' : 'asc']));
            $arrow = fn($f) => ($sort_by == $f) ? ($order == 'asc' ? '&#9650;' : '&#9660;') : '';
        @endphp

        <thead>
            <tr>
                <th><a href="{{ $sort('id') }}" style="text-decoration: none; color: #333;">ID {!! $arrow('id') !!}</a></th>
                <th><a href="{{ $sort('nombre_completo') }}" style="text-decoration: none; color: #333;">Cliente {!! $arrow('nombre_completo') !!}</a></th>
                <th><a href="{{ $sort('email') }}" style="text-decoration: none; color: #333;">Email {!! $arrow('email') !!}</a></th>
                <th><a href="{{ $sort('rol') }}" style="text-decoration: none; color: #333;">Rol {!! $arrow('rol') !!}</a></th>
                <th>Acción</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
            <tr>
                <td data-label="ID">#{{ $usuario->id }}</td>
                <td data-label="Cliente">
                    <a href="{{ route('admin.usuarios.show', $usuario) }}" style="font-weight: bold; text-decoration: none; color: #007bff;">
                        {{ $usuario->nombre_completo }}
                    </a>
                </td>
                <td data-label="Email" style="word-break: break-all;">{{ $usuario->email }}</td>
                <td data-label="Rol">
                    <span style="
                        padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: bold;
                        background-color: {{ $usuario->rol == 'admin' ? '#fce8ea' : '#e7f1ff' }};
                        color: {{ $usuario->rol == 'admin' ? '#dc3545' : '#007bff' }};
                    ">
                        {{ ucfirst($usuario->rol) }}
                    </span>
                </td>
                <td>
                    @if ($usuario->id === Auth::id())
                        <span style="color: #aaa; font-style: italic;">(Tu usuario)</span>
                    @else
                        <div class="actions-container">
                            <form action="{{ route('admin.usuarios.toggleRole', $usuario) }}" method="POST">
                                @csrf
                                @if ($usuario->rol == 'admin')
                                    <button type="submit" class="btn-action btn-make-client">⬇ Hacer Cliente</button>
                                @else
                                    <button type="submit" class="btn-action btn-make-admin">⬆ Hacer Admin</button>
                                @endif
                            </form>

                            <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" onclick="return confirm('¿Estás seguro de ELIMINAR a este usuario?')">🗑 Borrar</button>
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