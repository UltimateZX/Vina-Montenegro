@extends('layouts.app')

@section('content')

<style>
    /* --- ESTILOS GENERALES (PC) --- */
    .tienda-container {
        display: grid;
        /* En PC: Barra lateral de 280px y el resto para productos */
        grid-template-columns: 280px 1fr; 
        gap: 30px;
        align-items: flex-start;
        padding: 20px;
        max-width: 1300px;
        margin: 0 auto;
    }
    
    /* Estilos de la Barra Lateral */
    .tienda-sidebar {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        /* Hacemos que la barra se quede quieta al bajar en PC */
        position: sticky;
        top: 20px; 
    }
    
    .tienda-sidebar h3 {
        margin-top: 0;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
        font-size: 1.2em;
        color: #333;
    }

    .list-group-item {
        display: block;
        padding: 12px 15px;
        text-decoration: none;
        color: #555;
        border: 1px solid #eee;
        margin-bottom: -1px; /* Para unir bordes */
        transition: background 0.2s;
    }
    .list-group-item:first-child { border-top-left-radius: 5px; border-top-right-radius: 5px; }
    .list-group-item:last-child { border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; }
    .list-group-item:hover { background-color: #f8f9fa; color: #b42a6a; }
    
    .list-group-item.active {
        background-color: #b42a6a;
        color: white;
        border-color: #b42a6a;
    }
    
    .tienda-catalogo h2 {
        text-align: center;
        margin-top: 0;
        margin-bottom: 25px;
        color: #333;
    }

    /* Galería de Productos (PC: 3 columnas) */
    .galeria { 
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px; 
    }
    
    .producto-card {
        border: 1px solid #eee; 
        border-radius: 10px; 
        background: #fff;
        padding: 15px; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        transition: transform 0.2s;
    }
    .producto-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .producto-card img { 
        width: 100%; 
        height: 220px; 
        object-fit: contain; /* Muestra toda la imagen sin recortar */
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .card-content h3 { font-size: 1.1em; margin: 0 0 5px 0; color: #222; }
    .card-content .precio { font-size: 1.3em; color: #b42a6a; font-weight: bold; margin: 5px 0; }
    .card-content small { color: #777; display: block; margin-bottom: 15px; line-height: 1.4; }

    .producto-card button {
        background: #333; 
        color: white;
        border: none; 
        padding: 12px; 
        cursor: pointer; 
        border-radius: 6px; 
        width: 100%; 
        font-weight: bold;
        transition: background 0.2s;
    }
    .producto-card button:hover { background: #b42a6a; }

    /* --- 📱 CSS RESPONSIVO (CELULARES Y TABLETS) --- */
    @media (max-width: 900px) {
        .tienda-container {
            /* Cambiamos a 1 sola columna vertical */
            grid-template-columns: 1fr; 
            padding: 15px;
            gap: 20px;
        }
        
        .tienda-sidebar {
            /* El sidebar ya no flota, se queda arriba normal */
            position: static; 
            margin-bottom: 10px;
        }
        
        /* Opcional: Ocultar texto de intro en celular para ahorrar espacio */
        .sidebar-intro { display: none; }
    }

    @media (max-width: 600px) {
        /* En celulares pequeños, forzamos a que los productos se vean uno por fila (o dos pequeños) */
        .galeria {
            grid-template-columns: 1fr; /* 1 producto grande por fila */
            gap: 20px;
        }
        
        .producto-card img {
            height: 200px;
        }
    }
</style>

@if(session('success'))
    <div style="max-width: 1200px; margin: 20px auto; background: #d4edda; color: #155724; padding: 15px; text-align: center; border-radius: 5px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

<div class="tienda-container">
    
    <!-- BARRA LATERAL -->
    <aside class="tienda-sidebar">
        <h3>Viña Montenegro</h3>
        <p class="sidebar-intro" style="font-size: 0.9em; color: #555; margin-bottom: 20px;">
            Disfruta de la mejor selección de vinos y piscos peruanos. Calidad y tradición en cada botella.
        </p>

        <h3 style="font-size: 1em; margin-top: 10px; border: none; color: #888; text-transform: uppercase; letter-spacing: 1px;">Filtrar</h3>
        <div class="list-group">
            <a href="{{ route('home') }}" 
               class="list-group-item {{ !$categoriaActual ? 'active' : '' }}">
                🍷 Todo el Catálogo
            </a>
            @foreach ($categorias as $categoria)
                <a href="{{ route('home', ['categoria_id' => $categoria->id]) }}" 
                   class="list-group-item {{ $categoriaActual == $categoria->id ? 'active' : '' }}">
                    {{ $categoria->nombre }}
                </a>
            @endforeach
        </div>
        
        <div style="margin-top: 30px;">
            <a href="https://wa.me/51904881991" target="_blank" 
               style="display: block; text-align: center; background: #25D366; color: white; padding: 10px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                <span style="font-size: 1.2em; vertical-align: middle;">✆</span> Pedir por WhatsApp
            </a>
        </div>
    </aside>
    
    <!-- CATÁLOGO PRINCIPAL -->
    <main class="tienda-catalogo">
        <h2 style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <span>🍇</span> Catálogo de Productos
        </h2>
        
        @if($productos->isEmpty())
            <div style="background: #f8f9fa; padding: 40px; border-radius: 10px; text-align: center; color: #666; border: 2px dashed #ddd;">
                <h3>No se encontraron productos</h3>
                <p>Intenta seleccionar otra categoría o vuelve más tarde.</p>
                <a href="{{ route('home') }}" style="color: #b42a6a; font-weight: bold;">Ver todos los productos</a>
            </div>
        @else
            <div class="galeria">
                @foreach ($productos as $producto)
                    <div class="producto-card">
                        
                        <!-- IMAGEN CORREGIDA (Sin asset) -->
                        <img src="{{ $producto->url_imagen }}" alt="{{ $producto->nombre }}">
                        
                        <div class="card-content">
                            <h3>{{ $producto->nombre }}</h3>
                            <p class="precio">S/ {{ number_format($producto->precio, 2) }}</p>
                            <!-- Limitamos la descripción para que no rompa el diseño -->
                            <small>{{ Str::limit($producto->descripcion, 80) }}</small>
                        </div>
                        
                        <form action="{{ route('cart.add') }}" method="POST" style="margin-top: auto;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $producto->id }}">
                            <button type="submit">
                                Añadir al Carrito 🛒
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
    
</div>

@endsection