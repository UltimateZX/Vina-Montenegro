@extends('layouts.app')

@section('content')

<style>
    .cart-page-container {
        max-width: 1100px;
        margin: 30px auto;
        display: grid;
        /* PC: 2 columnas (Lista | Resumen) */
        grid-template-columns: 2fr 1fr; 
        gap: 30px;
        padding: 0 20px;
    }

    .cart-card {
        background: #fff; border-radius: 8px; padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .cart-card h2 { margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 15px; font-size: 1.4em; }

    /* --- ESTILOS DE LA TABLA (SOLO PC) --- */
    .cart-items-list { width: 100%; border-collapse: collapse; }
    .cart-items-list thead th { text-align: left; padding-bottom: 15px; color: #777; border-bottom: 2px solid #f0f0f0; }
    .cart-item td { padding: 20px 0; border-bottom: 1px solid #f8f9fa; vertical-align: top; }

    .cart-item-product { display: flex; align-items: flex-start; gap: 15px; }
    .cart-item-product img { width: 80px; height: 80px; object-fit: contain; border: 1px solid #eee; border-radius: 6px; }
    .cart-item-info h4 { margin: 0 0 5px 0; font-size: 1.1em; color: #333; }
    .cart-item-info small { color: #777; font-size: 0.9em; }

    .cart-item-controls { display: flex; align-items: center; gap: 5px; margin-top: 5px; }
    .cart-item-controls form { margin: 0; }
    
    .qty-btn { 
        width: 32px; height: 32px; border: 1px solid #ddd; background: #fff; 
        cursor: pointer; font-size: 1.1em; border-radius: 4px; color: #555;
        display: flex; align-items: center; justify-content: center;
    }
    .qty-btn:hover { background: #f0f0f0; }
    .qty-display { width: 30px; text-align: center; font-weight: bold; font-size: 1.1em; }
    
    .delete-link { 
        background: none; border: none; color: #dc3545; font-size: 0.85em; 
        margin-left: 15px; cursor: pointer; text-decoration: underline; 
    }
    
    .cart-item-subtotal { font-size: 1.2em; font-weight: bold; text-align: right; color: #333; }

    /* --- RESUMEN DEL PEDIDO --- */
    .summary-row { display: flex; justify-content: space-between; font-size: 1em; margin-bottom: 12px; color: #555; }
    .summary-row span:last-child { font-weight: 600; color: #333; }
    .total-row { 
        display: flex; justify-content: space-between; font-size: 1.5em; font-weight: bold; 
        color: #b42a6a; border-top: 2px solid #eee; padding-top: 15px; margin-top: 15px; 
    }
    
    .btn-checkout { 
        display: block; width: 100%; padding: 15px; background: #b42a6a; color: white; 
        text-decoration: none; text-align: center; border: none; border-radius: 6px; 
        font-size: 1.1em; font-weight: bold; cursor: pointer; margin-top: 20px;
        transition: background 0.2s;
    }
    .btn-checkout:hover { background: #901e52; }
    
    .btn-secondary { 
        display: block; width: 100%; text-align: center; color: #777; 
        text-decoration: underline; margin-top: 15px; font-size: 0.95em; 
    }

    /* --- 📱 CSS RESPONSIVO (TRANSFORMACIÓN A TARJETAS) --- */
    @media (max-width: 800px) {
        .cart-page-container {
            grid-template-columns: 1fr; /* 1 sola columna */
            padding: 15px;
            gap: 20px;
        }

        /* Ocultar encabezados de tabla */
        .cart-items-list thead { display: none; }
        
        /* Convertir filas en bloques (tarjetas) */
        .cart-items-list, .cart-items-list tbody, .cart-items-list tr, .cart-items-list td {
            display: block;
            width: 100%;
        }
        
        .cart-item {
            background: #fff;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            position: relative;
        }
        
        .cart-item td { padding: 5px 0; border: none; }
        
        /* Reorganizar contenido */
        .cart-item-product {
            margin-bottom: 10px;
        }
        
        .cart-item-controls {
            justify-content: space-between; /* Controles a los lados */
            background: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
        }
        
        .cart-item-subtotal {
            text-align: right;
            margin-top: -35px; /* Subir el precio */
            margin-bottom: 10px;
        }
        
        /* Ajustes visuales móviles */
        .delete-link { 
            color: #dc3545; 
            font-size: 0.9em; 
            text-decoration: none;
            border: 1px solid #dc3545;
            padding: 4px 8px;
            border-radius: 4px;
        }
    }
</style>

<div class="cart-page-container">
    
    <!-- LISTA DE PRODUCTOS -->
    <div class="cart-card">
        <h2>Tu Carrito de Compras</h2>
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 12px; margin-bottom: 20px; text-align: center; border-radius: 6px;">
                {{ session('success') }}
            </div>
        @endif

        @if(empty($cartItems))
            <div style="text-align: center; padding: 40px 0; color: #777;">
                <p style="font-size: 3em; margin: 0;">🛒</p>
                <p>Tu carrito está vacío.</p>
                <a href="{{ route('home') }}" style="color: #b42a6a; font-weight: bold;">Ir al catálogo</a>
            </div>
        @else
            <table class="cart-items-list">
                <thead>
                    <tr>
                        <th style="width: 50%;">Producto</th>
                        <th style="width: 30%;">Cantidad</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $id => $item)
                        <tr class="cart-item">
                            <!-- Columna Producto -->
                            <td>
                                <div class="cart-item-product">
                                    <!-- IMAGEN (Sin asset) -->
                                    <img src="{{ $item['url_imagen'] }}" alt="{{ $item['nombre'] }}">
                                    
                                    <div class="cart-item-info">
                                        <h4>{{ $item['nombre'] }}</h4>
                                        <small>Unitario: S/ {{ number_format($item['precio'], 2) }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Columna Subtotal (En móvil se mueve con CSS) -->
                            <td class="cart-item-subtotal">
                                S/ {{ number_format($item['precio'] * $item['quantity'], 2) }}
                            </td>

                            <!-- Columna Controles -->
                            <td>
                                <div class="cart-item-controls">
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <form action="{{ route('cart.decrease') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $id }}">
                                            <button type="submit" class="qty-btn">-</button>
                                        </form>
                                        
                                        <span class="qty-display">{{ $item['quantity'] }}</span>
                                        
                                        <form action="{{ route('cart.increase') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $id }}">
                                            <button type="submit" class="qty-btn">+</button>
                                        </form>
                                    </div>
                                    
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button type="submit" class="delete-link">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- RESUMEN (Derecha en PC, Abajo en Móvil) -->
    <div class="cart-summary">
        <div class="cart-card">
            <h2>Resumen</h2>
            
            @if(!empty($cartItems))
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>S/ {{ number_format($total, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Envío:</span>
                    <span style="color: #28a745;">Gratis</span>
                </div>
                <div class="total-row">
                    <span>Total:</span>
                    <span>S/ {{ number_format($total, 2) }}</span>
                </div>
                
                <a href="{{ route('checkout.index') }}" class="btn-checkout">
                    Proceder al Pago
                </a>
            @else
                <p style="color: #777; font-size: 0.9em;">Añade productos para ver el total.</p>
            @endif
            
            <a href="{{ route('home') }}" class="btn-secondary">
                Seguir comprando
            </a>
        </div>
    </div>
</div>
@endsection