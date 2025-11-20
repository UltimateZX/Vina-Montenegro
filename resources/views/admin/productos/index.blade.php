@extends('layouts.admin')

@section('content')

<style>
    /* Tus estilos originales (se mantienen igual) */
    .actions-container .btn {
        min-width: 90px; 
        text-align: center;
    }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .admin-table th, .admin-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
        vertical-align: middle; /* Centra el contenido verticalmente */
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
    
    /* Botones */
    .btn {
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 0.9em;
        text-decoration: none;
        display: inline-block;
    }
    .btn-sm { padding: 5px 10px; font-size: 0.8em; }
    .btn-primary { background-color: #007bff; color: white; }
    .btn-warning { background-color: #ffc107; color: #333; }
    .btn-success { background-color: #28a745; color: white; }
    .gap-2 { gap: 8px; }
    .d-flex { display: flex; }

    /* Estilo para la imagen miniatura */
    .product-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover; /* Recorta la imagen para que sea cuadrada perfecta */
        border-radius: 4px;
        border: 1px solid #ddd;
    }
</style>

<div class="admin-header">
    <h1>Gestión de Productos</h1>
    <a href="{{ route('admin.productos.create') }}">Añadir Nuevo Producto</a>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; text-align: center; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

<div style="background: white; padding: 20px; border-radius: 8px;">
    <table class="admin-table">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Imagen</th> 
                <th>Nombre</th> 
                <th>Precio</th> 
                <th class="text-center">Stock</th> 
                <th>Acciones</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
            <tr>
                <td>{{ $producto->id }}</td>
                
                <!-- CORRECCIÓN DE IMAGEN -->
                <td>
                    @if($producto->url_imagen)
                        <img src="{{ $producto->url_imagen }}" alt="{{ $producto->nombre }}" class="product-thumbnail">
                    @else
                        <span style="color: #999; font-size: 0.8em;">Sin imagen</span>
                    @endif
                </td>

                <td>
                    {{ $producto->nombre }}
                    @if(!$producto->is_active)
                        <span style="font-size: 0.8em; color: #fff; background: #6c757d; padding: 3px 6px; border-radius: 4px; margin-left: 5px;">Archivado</span>
                    @endif
                </td>

                <td>S/ {{ number_format($producto->precio, 2) }}</td>

                <td class="text-center @if($producto->stock <= 10) text-danger fw-bold @endif">
                    {{ $producto->stock }}
                </td>

                <td>
                    <div class="d-flex gap-2 actions-container">

                        <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn btn-sm btn-primary">Editar</a>

                        @if ($producto->is_active)
                            <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-warning"
                                        onclick="return confirm('¿Estás seguro de que quieres DESACTIVAR este producto?')">
                                    Desactivar
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.productos.activate', $producto->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    Activar
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection