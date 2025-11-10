@extends('layouts.app')

@section('content')

<style>
    .cart-page-container {
        max-width: 1100px;
        margin: 30px auto; /* Despega del header */
        display: grid;
        /* Dos columnas: 2/3 para la lista, 1/3 para el resumen */
        grid-template-columns: 2fr 1fr; 
        gap: 30px;
        padding: 0 20px;
    }

    /* Estilo de "Tarjeta" para el contenido */
    .cart-card {
        background: #fff;
        border-radius: 8px;
        padding: 25px 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .cart-card h2 {
        margin-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    
    /* Estilos para la lista de items */
    .cart-items-list {
        border-collapse: collapse;
        width: 100%;
    }
    .cart-items-list thead th {
        text-align: left;
        padding-bottom: 15px;
        color: #555;
        border-bottom: 2px solid #f0f0f0;
    }
    .cart-item td {
        padding: 20px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .cart-item-product {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .cart-item-product img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        border: 1px solid #eee;
        border-radius: 4px;
    }
    .cart-item-product h4 {
        margin: 0;
        font-size: 1.1em;
    }
    .cart-item-product small {
        color: #555;
        font-size: 0.9em;
    }

    /* Estilos para los botones +/- */
    .cart-item-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .cart-item-controls form { margin: 0; }
    .cart-item-controls .qty-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #ccc;
        background: #f5f5f5;
        cursor: pointer;
        font-size: 1.2em;
    }
    .cart-item-controls .qty-display {
        padding: 0 5px;
        font-weight: bold;
        font-size: 1.2em;
    }
    .cart-item-controls .delete-link {
        background: none;
        border: none;
        color: #b42a6a; /* Color fucsia */
        text-decoration: underline;
        cursor: pointer;
        font-size: 0.9em;
        margin-left: 15px;
    }
    
    .cart-item-subtotal {
        font-size: 1.1em;
        font-weight: bold;
        text-align: right;
    }
    
    /* Estilos para el Resumen (derecha) */
    .cart-summary .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 1.1em;
        margin-bottom: 15px;
    }
    .cart-summary .summary-row span:last-child {
        font-weight: bold;
    }
    .cart-summary .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 1.6em;
        font-weight: bold;
        color: #b42a6a; /* Color fucsia */
        border-top: 2px solid #eee;
        padding-top: 15px;
        margin-top: 15px;
    }
    
    /* Estilos para los botones principales */
    .btn-checkout {
        display: block;
        width: 100%;
        max-width: 300px;
        margin: 0 auto 10px auto;
        padding: 15px;
        background: #b42a6a;
        color: white;
        text-decoration: none;
        text-align: center;
        border: none;
        border-radius: 5px;
        font-size: 1.1em;
        cursor: pointer;
        margin-bottom: 10px;
    }
    .btn-secondary {
        display: block;
        width: 100%;
        max-width: 300px;
        margin: 0 auto 10px auto;
        padding: 15px;
        background: #6c757d;
        color: white;
        text-decoration: none;
        text-align: center;
        border: none;
        border-radius: 5px;
        font-size: 1.1em;
        cursor: pointer;
    }
    
</style>

<div class="cart-page-container">
    
    <div class="cart-card">
        <h2>Tu Carrito de Compras</h2>
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; text-align: center; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif

        @if(empty($cartItems))
            <p>Tu carrito está vacío.</p>
        @else
            <table class="cart-items-list">
                <thead>
                    <tr>
                        <th style="width: 40%;">Producto</th>
                        <th style="width: 35%;">Cantidad</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $id => $item)
                        <tr class="cart-item">
                            <td>
                                <div class="cart-item-product">
                                    <img src="{{ asset($item['url_imagen']) }}" alt="{{ $item['nombre'] }}">
                                    <div class="cart-item-info">
                                        <h4>{{ $item['nombre'] }}</h4>
                                        <small>Precio: S/ {{ number_format($item['precio'], 2) }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="cart-item-controls">
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
                                    
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button type="submit" class="delete-link">Eliminar</button>
                                    </form>
                                </div>
                            </td>

                            <td class="cart-item-subtotal">
                                S/ {{ number_format($item['precio'] * $item['quantity'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="cart-summary">
        <div class="cart-card">
            <h2>Resumen del Pedido</h2>
            
            @if(!empty($cartItems))
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>S/ {{ number_format($total, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Envío:</span>
                    <span>Gratis</span>
                </div>
                <div class="total-row">
                    <span>Total:</span>
                    <span>S/ {{ number_format($total, 2) }}</span>
                </div>
                
                <a href="{{ route('checkout.index') }}" class="btn-checkout" style="margin-top: 20px;">
                    Proceder al pago
                </a>
            @else
                <p>Añade productos para ver el resumen.</p>
            @endif
            
            <a href="{{ route('home') }}" class="btn-secondary" style="margin-top: 10px;">
                Seguir comprando
            </a>
        </div>
    </div>
</div>
@endsection