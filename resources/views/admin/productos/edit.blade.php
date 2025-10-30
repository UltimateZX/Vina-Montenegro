@extends('layouts.admin')

@section('content')

<style>
    .form-container { background: white; padding: 30px; border-radius: 8px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%; padding: 10px; border: 1px solid #ddd;
        border-radius: 4px; box-sizing: border-box;
    }
    .form-group textarea { height: 100px; resize: vertical; }
    .form-actions button {
        background-color: #007bff; /* Azul para "Actualizar" */
        color: white; padding: 12px 20px; border: none;
        border-radius: 5px; cursor: pointer; font-size: 1em;
    }
    .form-actions a {
        background-color: #6c757d; color: white; padding: 12px 20px;
        text-decoration: none; border-radius: 5px; font-size: 1em; margin-left: 10px;
    }
    .alert-danger {
        background-color: #f8d7da; color: #721c24; padding: 10px;
        border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;
    }
    .current-image {
        max-width: 150px; height: auto; border-radius: 4px; border: 1px solid #ddd;
    }
</style>

<div class="admin-header">
    <h1>Editar Producto: {{ $producto->nombre }}</h1>
</div>

<div class="form-container">

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Ups!</strong> Hubo algunos problemas.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT') <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion">{{ old('descripcion', $producto->descripcion) }}</textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio', $producto->precio) }}" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock (Cantidad)</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $producto->stock) }}" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría</label>
            <select name="categoria_id" id="categoria_id" required>
                <option value="">Selecciona una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Imagen Actual</label><br>
            <img src="{{ asset($producto->url_imagen) }}" alt="{{ $producto->nombre }}" class="current-image">
        </div>

        <div class="form-group">
            <label for="imagen">Subir Nueva Imagen (Opcional)</label>
            <input type="file" name="imagen" id="imagen">
            <small>Si no seleccionas una nueva, se mantendrá la imagen actual.</small>
        </div>

        <div class="form-actions">
            <button type="submit">Actualizar Producto</button>
            <a href="{{ route('admin.productos.index') }}">Cancelar</a>
        </div>
    </form>
</div>
@endsection