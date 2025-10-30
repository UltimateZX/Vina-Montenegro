<style>
    .mini-cart {
        width: 380px; /* Un poco más ancho */
        background: #ffffff;
        padding: 20px;
        display: flex;
        flex-direction: column;
        height: 100%; /* Ocupa el 100% de la altura del sidebar */
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
    }
    /* NUEVO: Encabezado del carrito */
    .mini-cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .mini-cart-header h3 { margin: 0; }
    .mini-cart-header .close-btn { /* Estilo del botón 'x' */
        background: none;
        border: none;
        font-size: 2em; /* Más grande */
        line-height: 1;
        cursor: pointer;
        padding: 0;
        color: #888;
    }
    .mini-cart-items { flex-grow: 1; overflow-y: auto; }
    .mini-cart-item { display: flex; gap: 10px; margin-bottom: 15px; align-items: center; }
    .mini-cart-item img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
    .mini-cart-item .info { flex-grow: 1; }
    .mini-cart-item .info h5 { margin: 0 0 5px 0; font-size: 0.9em; }
    .mini-cart-item .info span { color: #b42a6a; font-weight: bold; font-size: 0.9em; }
    .mini-cart-total { font-size: 1.2em; font-weight: bold; text-align: right; margin: 20px 0; }
    .btn-checkout {
        display: block; width: 100%; padding: 15px; background: #b42a6a; color: white;
        text-decoration: none; text-align: center; border-radius: 5px; font-weight: bold;
    }
</style>

<aside class="mini-cart">
    
    <div class="mini-cart-header">
        <h3>Tu Carrito</h3>
        <button id="closeMiniCart" class="close-btn">&times;</button> </div>
    
    @php
        $cartItems = session()->get('cart', []);
        $total = 0;
    @endphp

    @if(empty($cartItems))
        <p>Tu carrito está vacío.</p>
    @else
        <div class="mini-cart-items">
            @foreach($cartItems as $id => $item)
                @php $total += $item['precio'] * $item['quantity']; @endphp
                <div class="mini-cart-item">
                    <img src="{{ asset($item['url_imagen']) }}" alt="{{ $item['nombre'] }}">
                    <div class="info">
                        <h5>{{ $item['nombre'] }}</h5>
                        <span>{{ $item['quantity'] }} x $ {{ number_format($item['precio'], 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mini-cart-total">
            Total: $ {{ number_format($total, 2) }}
        </div>

        <a href="{{ route('cart.index') }}" class="btn-checkout">Ver Carrito Completo</a>
    @endif
</aside>