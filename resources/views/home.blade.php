@extends('layouts.app')

@section('content')

<style>
    .tienda-container {
        display: grid;
        /* ¡ARREGLADO! La barra lateral ahora mide 300px */
        grid-template-columns: 300px 1fr; 
        gap: 30px;
        align-items: flex-start;
        
        /* ¡ARREGLADO! Quitamos el 'max-width' y 'margin' para que 
           el contenido se pegue a los bordes de la página. */
        padding: 20px; /* Añadimos padding para que no se pegue del todo */
    }
    
    .tienda-sidebar {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .tienda-sidebar h3 {
        margin-top: 0;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
        font-size: 1.25em;
    }
    .tienda-sidebar .list-group-item {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #333;
        border: 1px solid #eee;
        margin-bottom: -1px;
    }
    .tienda-sidebar .list-group-item:first-child { border-top-left-radius: 4px; border-top-right-radius: 4px; }
    .tienda-sidebar .list-group-item:last-child { border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; }
    .tienda-sidebar .list-group-item:hover { background-color: #f9f9f9; }
    .tienda-sidebar .list-group-item.active {
        background-color: #b42a6a;
        color: white;
        border-color: #b42a6a;
    }
    
    .tienda-catalogo h2 {
        text-align: center;
        margin-top: 0;
    }

    .galeria { 
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px; 
    }
    
    .producto-card {
        border: 1px solid #ddd; 
        border-radius: 8px; 
        background: #fff;
        padding: 15px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }
    .producto-card .card-content {
        flex-grow: 1;
    }
    .producto-card img { 
        width: 100%; 
        /* ¡ARREGLADO! Imágenes más grandes */
        height: 250px; 
        object-fit: contain;
        border-radius: 4px; 
    }
    .producto-card h3 { font-size: 1.1em; margin: 10px 0; }
    .producto-card .precio { font-size: 1.2em; color: #b42a6a; font-weight: bold; }
    .producto-card form button {
        background: #eee; border: 1px solid #ccc; padding: 10px; cursor: pointer; 
        border-radius: 4px; width: 100%; margin-top: 15px;
    }
    .producto-card form button:hover { background: #ddd; }
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