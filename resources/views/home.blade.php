@extends('layouts.app')

@section('content')

<style>

</style>

@if(session('success'))
    <div style="max-width: 1200px; margin: 0 auto 15px auto; background: #d4edda; color: #155724; padding: 15px; text-align: center; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

<div class="tienda-container">

    <aside class="tienda-sidebar">
        <h3>Viña Montenegro</h3>
        <p style="font-size: 0.9em; color: #555;">
            Somos una viña dedicada a la producción de los mejores vinos y piscos de la región.
            Descubre nuestra variedad de productos y disfruta de la auténtica experiencia vinícola peruana.
        </p>

        <h3 class="border-bottom pb-2 mt-3">Categorías</h3>
        <div class="list-group">
            <a href="{{ route('home') }}"
               class="list-group-item {{ !$categoriaActual ? 'active' : '' }}">
                Ver Todas
            </a>
            @foreach ($categorias as $categoria)
                <a href="{{ route('home', ['categoria_id' => $categoria->id]) }}"
                   class="list-group-item {{ $categoriaActual == $categoria->id ? 'active' : '' }}">
                    {{ $categoria->nombre }}
                </a>
            @endforeach
        </div>

        <h3 class="border-bottom pb-2 mt-4">Contacto</h3>
        <div class="list-group">
            <a href="https://wa.me/51904881991" target="_blank" class="list-group-item">
                WhatsApp
            </a>
            <a href="#" class="list-group-item">
                Facebook
            </a>
        </div>
    </aside>

    <main class="tienda-catalogo">
        <h2>Catálogo de Productos</h2>

        @if($productos->isEmpty())
            <div style="background: #fff; padding: 20px; border-radius: 8px; text-align: center; color: #555;">
                No se encontraron productos que coincidan con tu búsqueda.
            </div>
        @else
            <div class="galeria">
                @foreach ($productos as $producto)
                    <div class="producto-card">
                        <img src="{{ asset($producto->url_imagen) }}" alt="{{ $producto->nombre }}">
                        <div class="card-content">
                            <h3>{{ $producto->nombre }}</h3>
                            <p class="precio">S/ {{ number_format($producto->precio, 2) }}</p>
                            <small>{{ $producto->descripcion }}</small>
                        </div>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $producto->id }}">
                            <button type="submit">Añadir al Carrito</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

</div>

@endsection
