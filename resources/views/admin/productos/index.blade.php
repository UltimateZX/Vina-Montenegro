@extends('layouts.admin')

@section('content')

<style>
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
    .admin-table th {
        background-color: #f2f2f2;
    }
    .admin-table tr:nth-child(even) { background-color: #f9f9f9; }
    .admin-table tr:hover { background-color: #f1f1f1; }
    .admin-table .actions a {
        margin-right: 10px;
        text-decoration: none;
        color: #007bff;
    }
    .admin-header a {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }
</style>

<div class="admin-header">
    <h1>Gestión de Productos</h1>
    <a href="{{ route('admin.productos.create') }}">Añadir Nuevo Producto</a>
</div>

<div style="background: white; padding: 20px; border-radius: 8px;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
            <tr>
                <td>{{ $producto->id }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>$ {{ number_format($producto->precio, 2) }}</td>
                <td>{{ $producto->stock }}</td>
                <td class="actions">
                    <a href="{{ route('admin.productos.edit', $producto->id) }}">Editar</a>
                    <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="POST" style="display: inline-block;">
    @csrf
    @method('DELETE')

    <button type="submit" style="color: #dc3545; background: none; border: none; cursor: pointer; text-decoration: underline;"
            onclick="return confirm('¿Estás seguro de que quieres eliminar este producto? Esta acción no se puede deshacer.')">
        Eliminar
    </button>
</form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection