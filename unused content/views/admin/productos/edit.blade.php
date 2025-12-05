@extends('layouts.admin')

@section('content')

<style>
    .form-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        max-width: 800px;
        margin: 0 auto;
    }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
    
    .form-group input, .form-group textarea, .form-group select {
        width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;
        box-sizing: border-box; font-size: 1em; font-family: inherit;
    }
    .form-group textarea { height: 120px; resize: vertical; }
    
    .form-actions { display: flex; align-items: center; gap: 15px; margin-top: 30px; }
    
    .btn-update {
        background-color: #007bff; color: white; padding: 12px 25px; border: none;
        border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold;
        text-decoration: none; display: inline-block; text-align: center;
    }
    .btn-cancel { color: #6c757d; text-decoration: underline; font-size: 1em; }

    .product-thumbnail {
        width: 100px; height: 100px; object-fit: contain; 
        border: 1px solid #ddd; border-radius: 5px; padding: 5px; background: #fff;
    }

    .alert-danger {
        background-color: #f8d7da; color: #721c24; padding: 15px;
        border-radius: 5px; margin-bottom: 25px; border: 1px solid #f5c6cb;
    }

    .admin-header h1 { font-size: 1.8em; color: #333; margin-bottom: 20px; text-align: center; }

    /* --- 📱 MÓVIL --- */
    @media (max-width: 768px) {
        .form-container { padding: 20px; margin: 10px; }
        .form-actions { flex-direction: column; gap: 15px; }
        .btn-update { width: 100%; }
        .btn-cancel { display: block; padding: 10px; }
    }
</style>

<div class="admin-header">
    <h1>Editar Producto</h1>
</div>

<div class="form-container">

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Error:</strong>
            <ul style="margin: 10px 0 0 20px; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
        @csrf 
        @method('PUT') 
        
        <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion">{{ old('descripcion', $producto->descripcion) }}</textarea>
        </div>

        <!-- Grid para precio y stock -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="precio">Precio (S/)</label>
                <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio', $producto->precio) }}" required>
            </div>
    
            <div class="form-group" style="margin-bottom: 0;">
                <label for="stock">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $producto->stock) }}" required>
            </div>
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
            <label>Imagen Actual</label>
            @if($producto->url_imagen)
                <div style="margin-bottom: 10px;">
                    <img src="{{ $producto->url_imagen }}" alt="{{ $producto->nombre }}" class="product-thumbnail">
                </div>
            @else
                <p style="color: #888; font-style: italic; margin-bottom: 10px;">Sin imagen asignada</p>
            @endif
            
            <label for="imagen" style="margin-top: 15px;">Cambiar Imagen (Opcional)</label>
            <input type="file" name="imagen" id="imagen">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-update">Actualizar Producto</button>
            <a href="{{ route('admin.productos.index') }}" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>
@endsection