<style>
    .mini-cart {
        width: 380px;
        background: #fdfdfd;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .mini-cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding: 20px;
    }
    .mini-cart-header h3 { margin: 0; }
    .mini-cart-header .close-btn {
        background: none; border: none; font-size: 2em;
        line-height: 1; cursor: pointer; padding: 0; color: #888;
    }
    .mini-cart-items {
        flex-grow: 1; /* Ocupa el espacio */
        overflow-y: auto; /* ¡AÑADE EL SCROLL! */
        padding: 20px;
    }
    .mini-cart-item { display: flex; gap: 10px; margin-bottom: 15px; align-items: center; }
    .mini-cart-item img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
    .mini-cart-item .info { flex-grow: 1; }
    .mini-cart-item .info h5 { margin: 0 0 5px 0; font-size: 0.9em; }
    .mini-cart-item .info span { color: #b42a6a; font-weight: bold; font-size: 0.9em; }
    
    .mini-cart-footer {
        border-top: 1px solid #eee;
        padding: 20px;
    }
    .mini-cart-total { font-size: 1.2em; font-weight: bold; text-align: right; margin-bottom: 20px; }
    .btn-checkout {
        display: block; width: 100%; padding: 15px; background: #b42a6a; color: white;
        text-decoration: none; text-align: center; border-radius: 5px; font-weight: bold;
    }
</style>

<aside class="mini-cart">
    
    <div class="mini-cart-header">
        <h3>Tu Carrito</h3>
        <button class="close-btn" onclick="toggleMiniCart()">&times;</button>
    </div>
    
    @php
        $cartItems = session()->get('cart', []);
        $total = 0;
    @endphp

    <div class="mini-cart-items">
        @if(empty($cartItems))
            <p>Tu carrito está vacío.</p>
        @else
            @foreach($cartItems as $id => $item)
                @php $total += $item['precio'] * $item['quantity']; @endphp
                <div class="mini-cart-item">
                    <img src="{{ asset($item['url_imagen']) }}" alt="{{ $item['nombre'] }}">
                    <div class="info">
                        <h5>{{ $item['nombre'] }}</h5>
                        <span>{{ $item['quantity'] }} x S/ {{ number_format($item['precio'], 2) }}</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @if(!empty($cartItems))
        <div class="mini-cart-footer">
            <div class="mini-cart-total">
                Total: S/ {{ number_format($total, 2) }}
            </div>
            <a href="{{ route('cart.index') }}" class="btn-checkout">Ver Carrito Completo</a>
        </div>
    @endif
</aside>