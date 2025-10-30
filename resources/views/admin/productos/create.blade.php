@extends('layouts.admin')

@section('content')

<style>
    .form-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box; /* Importante para el padding */
    }
    .form-group textarea {
        height: 100px;
        resize: vertical;
    }
    .form-actions button {
        background-color: #28a745;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
    }
    .form-actions a {
        background-color: #6c757d;
        color: white;
        padding: 12px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 1em;
        margin-left: 10px;
    }
    /* Estilos para errores de validación */
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
        margin-bottom: 20px;
    }
</style>

<div class="admin-header">
    <h1>Añadir Nuevo Producto</h1>
</div>

<div class="form-container">

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Ups!</strong> Hubo algunos problemas con tu entrada.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion">{{ old('descripcion') }}</textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio') }}" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock (Cantidad)</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock') }}" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría</label>
            <select name="categoria_id" id="categoria_id" required>
                <option value="">Selecciona una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del Producto</label>
            <input type="file" name="imagen" id="imagen" required>
        </div>

        <div class="form-actions">
            <button type="submit">Guardar Producto</button>
            <a href="{{ route('admin.productos.index') }}">Cancelar</a>
        </div>
    </form>
</div>
@endsection