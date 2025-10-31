@extends('layouts.app')

@section('content')

<style>
    .tienda-container {
        display: grid;
        grid-template-columns: 240px 1fr; 
        gap: 20px;
        align-items: flex-start;
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
    }

    .producto-card img { width: 100%; height: 150px; object-fit: contain; border-radius: 4px; }
    .producto-card h3 { font-size: 1.1em; margin: 10px 0; }
    .producto-card .precio { font-size: 1.2em; color: #b42a6a; font-weight: bold; }
    .producto-card form button {
        background: #eee; border: 1px solid #ccc; padding: 8px; cursor: pointer; border-radius: 4px; width: 100%;
    }
</style>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; text-align: center; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

<div class="tienda-container">
    
    <aside class="tienda-sidebar">
        <h3>Viña Montenegro</h3>
        
        <p>
            Somos una viña dedicada a la producción de los mejores vinos y piscos de la región. </br>
            El Sistema E-commerce para la Vinícola Antonio Montenegro es una plataforma web que
            moderniza los procesos de venta tradicionales, permitiendo a los usuarios registrarse,
            visualizar el catálogo de productos, realizar compras desde cualquier dispositivo y efectuar
            transacciones seguras mediante Yape con validación OTP, y al administrador gestionar inventario,
            pedidos y generar reportes de ventas
        </p>
        </aside>
    
    <main class="tienda-catalogo">
        <h2>Catálogo de Productos</h2>
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
                        <button type="submit">Añadir al Carrito</button>
                    </form>
                </div>
            @endforeach
        </div>
    </main>
    
</div>

@endsection