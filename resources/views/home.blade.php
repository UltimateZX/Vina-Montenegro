@extends('layouts.app')

@section('content')

<style>
    .galeria { display: flex; flex-wrap: wrap; gap: 20px; justify-content: flex-start; }
    .producto-card {
        border: 1px solid #ddd; border-radius: 8px; background: #fff;
        padding: 15px; width: 200px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .producto-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 4px; }
    .producto-card h3 { font-size: 1.1em; margin: 10px 0; }
    .producto-card .precio { font-size: 1.2em; color: #b42a6a; font-weight: bold; }
    .producto-card form button {
        background: #eee; border: 1px solid #ccc; padding: 8px; cursor: pointer; border-radius: 4px; width: 100%;
    }
</style>

<h2 style="text-align: center;">Catálogo de Productos</h2>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; text-align: center; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

<div class="galeria">
    @foreach ($productos as $producto)
        <div class="producto-card">
            <img src="{{ asset($producto->url_imagen) }}" alt="{{ $producto->nombre }}">
            <h3>{{ $producto->nombre }}</h3>
            <p class="precio">$ {{ number_format($producto->precio, 2) }}</p>
            <small>{{ $producto->descripcion }}</small>
            <br><br>

            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $producto->id }}">
                <button type:="submit">Añadir al Carrito</button>
            </form>
        </div>
    @endforeach
</div>

@endsection