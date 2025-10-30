@extends('layouts.app')

@section('content')
<style>
    .cart-page { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
    .cart-item { display: flex; gap: 15px; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px; }
    .cart-item img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; }
    .cart-item .info { flex-grow: 1; }
    .cart-item .info h4 { margin: 0 0 5px 0; }
    .cart-item .info .precio { color: #b42a6a; font-weight: bold; }
    .cart-item .controls { display: flex; align-items: center; gap: 10px; }
    .cart-total { text-align: right; margin-top: 20px; font-size: 1.5em; font-weight: bold; }
    .cart-total .total-label { color: #333; }
    .cart-total .total-amount { color: #b42a6a; }
    .cart-buttons { display: flex; justify-content: flex-end; gap: 15px; margin-top: 20px; }
    .cart-buttons button, .cart-buttons a {
        padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 1em;
    }
    .btn-checkout { background: #b42a6a; color: white; }
    .btn-continue { background: #6c757d; color: white; }
    /* ... (después de los otros estilos) ... */
    .btn-qty {
    width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ccc;
    background: #f0f0f0; cursor: pointer; font-size: 1.2em;
    }
    .btn-remove {
    background: none; border: none; color: #dc3545; cursor: pointer; text-decoration: underline;
    }
</style>

<div class="cart-page">
    <h2>Tu Carrito</h2>

    @if(empty($cartItems))
        <p>Tu carrito está vacío.</p>
    @else
        @foreach($cartItems as $id => $item)
            <div class="cart-item">
                <img src="{{ asset($item['url_imagen']) }}" alt="{{ $item['nombre'] }}">
                <div class="info">
                    <h4>{{ $item['nombre'] }}</h4>
                    <span class="precio">$ {{ number_format($item['precio'], 2) }}</span>
                </div>
                <div class="controls">
    <form action="{{ route('cart.decrease') }}" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="product_id" value="{{ $id }}">
        <button type="submit" class="btn-qty">-</button>
    </form>

    <span style="padding: 0 10px; font-weight: bold;">{{ $item['quantity'] }}</span>

    <form action="{{ route('cart.increase') }}" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="product_id" value="{{ $id }}">
        <button type="submit" class="btn-qty">+</button>
    </form>

    <form action="{{ route('cart.remove') }}" method="POST" style="margin-left: 20px; display: inline;">
        @csrf
        <input type="hidden" name="product_id" value="{{ $id }}">
        <button type="submit" class="btn-remove">Eliminar</button>
    </form>
</div>
            </div>
        @endforeach

        <div class="cart-total">
                <span class="total-label">Total:</span>
                <span class="total-amount">$ {{ number_format($total, 2) }}</span>
            </div>

            <div class="cart-buttons">
                <a href="{{ route('checkout.index') }}" class="btn-checkout">Proceder al pago</a>
            </div>
        @endif <div class="cart-buttons" style="justify-content: flex-start; margin-top: 30px;">
            <a href="/" class="btn-continue">Seguir comprando</a>
        </div>

    </div>
    @endsection