@extends('layouts.admin')

@section('content')

<style>
    /* --- Estilos Base (PC) --- */
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .admin-table th, .admin-table td { border-bottom: 1px solid #eee; padding: 12px; text-align: left; vertical-align: middle; }
    .admin-table th { background-color: #f9f9f9; color: #555; font-weight: 600; text-transform: uppercase; font-size: 0.85em; }
    .admin-table tr:hover { background-color: #fcfcfc; }

    .product-thumbnail {
        width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;
    }

    .btn { padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 0.85em; text-decoration: none; display: inline-block; transition: opacity 0.2s; }
    .btn:hover { opacity: 0.9; }
    .btn-primary { background-color: #007bff; color: white; }
    .btn-warning { background-color: #ffc107; color: #333; }
    .btn-success { background-color: #28a745; color: white; }
    .btn-add { background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; }

    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .admin-header h1 { margin: 0; font-size: 1.8em; color: #333; }

    /* --- 📱 RESPONSIVIDAD (CELULAR) --- */
    @media (max-width: 768px) {
        .admin-header { flex-direction: column; align-items: stretch; gap: 15px; }
        .btn-add { text-align: center; display: block; }

        /* Ocultar encabezados */
        .admin-table thead { display: none; }
        
        /* Convertir filas en tarjetas */
        .admin-table, .admin-table tbody, .admin-table tr, .admin-table td { display: block; width: 100%; }
        
        .admin-table tr {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            position: relative;
        }
        
        /* Foto centrada arriba */
        .admin-table td:nth-child(2) { /* Columna Imagen */
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 10px;
        }
        .product-thumbnail { width: 80px; height: 80px; }

        .admin-table td {
            padding: 8px 0;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95em;
        }
        
        /* Etiquetas simuladas */
        .admin-table td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #777;
            text-align: left;
        }
        
        /* Ocultar etiqueta de imagen y acciones para diseño limpio */
        .admin-table td:nth-child(2)::before, 
        .admin-table td:last-child::before { display: none; }

        /* Botones abajo ocupando ancho */
        .admin-table td:last-child {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #eee;
            justify-content: flex-end;
        }
    }
</style>

<div class="admin-header">
    <h1>Gestión de Productos</h1>
    <a href="{{ route('admin.productos.create') }}" class="btn-add">+ Nuevo Producto</a>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; text-align: center; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <table class="admin-table">
        <thead>
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
                <td data-label="ID">#{{ $producto->id }}</td>
                
                <td data-label="Imagen">
                    @if($producto->url_imagen)
                        <img src="{{ $producto->url_imagen }}" alt="{{ $producto->nombre }}" class="product-thumbnail">
                    @else
                        <span style="color: #999; font-size: 0.8em;">Sin imagen</span>
                    @endif
                </td>

                <td data-label="Nombre">
                    <strong>{{ $producto->nombre }}</strong>
                    @if(!$producto->is_active)
                        <span style="font-size: 0.7em; color: #fff; background: #6c757d; padding: 2px 5px; border-radius: 3px; margin-left: 5px; vertical-align: middle;">Archivado</span>
                    @endif
                </td>

                <td data-label="Precio" style="color: #b42a6a; font-weight: bold;">S/ {{ number_format($producto->precio, 2) }}</td>

                <td data-label="Stock">
                    @if($producto->stock <= 10)
                        <span style="color: #dc3545; font-weight: bold;">{{ $producto->stock }} (Bajo)</span>
                    @else
                        <span style="color: #28a745; font-weight: bold;">{{ $producto->stock }}</span>
                    @endif
                </td>

                <td>
                    <div style="display: flex; gap: 5px;">
                        <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn btn-primary">Editar</a>

                        @if ($producto->is_active)
                            <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="POST" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-warning" onclick="return confirm('¿Desactivar producto?')">Desactivar</button>
                            </form>
                        @else
                            <form action="{{ route('admin.productos.activate', $producto->id) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn btn-success">Activar</button>
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