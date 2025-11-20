<style>
    .mini-cart-content {
        width: 100%; /* Se adapta al contenedor padre */
        background: #fdfdfd;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .mini-cart-header {
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid #eee; padding: 15px;
        background: #b42a6a; color: white;
    }
    .mini-cart-header h3 { margin: 0; font-size: 1.1em; }
    .mini-cart-header .close-btn {
        background: none; border: none; font-size: 1.5em; color: white; cursor: pointer;
    }
    .mini-cart-items {
        flex-grow: 1; overflow-y: auto; padding: 15px;
    }
    .mini-cart-item { display: flex; gap: 10px; margin-bottom: 15px; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    .mini-cart-item img { width: 50px; height: 50px; object-fit: contain; border-radius: 4px; border: 1px solid #eee; }
    .mini-cart-item .info { flex-grow: 1; }
    .mini-cart-item .info h5 { margin: 0 0 3px 0; font-size: 0.9em; color: #333; }
    .mini-cart-item .info span { color: #b42a6a; font-weight: bold; font-size: 0.85em; }
    
    .mini-cart-footer { border-top: 1px solid #eee; padding: 15px; background: #fff; }
    .mini-cart-total { font-size: 1.1em; font-weight: bold; text-align: right; margin-bottom: 15px; color: #333; }
    .btn-checkout {
        display: block; width: 100%; padding: 12px; background: #b42a6a; color: white;
        text-decoration: none; text-align: center; border-radius: 5px; font-weight: bold;
        transition: background 0.2s;
    }
    .btn-checkout:hover { background: #901e52; }
</style>

<div class="mini-cart-content">
    
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
            <div style="text-align: center; padding-top: 30px; color: #777;">
                <p>🛒</p>
                <p>Tu carrito está vacío.</p>
            </div>
        @else
            @foreach($cartItems as $id => $item)
                @php $total += $item['precio'] * $item['quantity']; @endphp
                <div class="mini-cart-item">
                    <!-- CORRECCIÓN DE IMAGEN (Sin asset) -->
                    <img src="{{ $item['url_imagen'] }}" alt="{{ $item['nombre'] }}">
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
            <a href="{{ route('cart.index') }}" class="btn-checkout">Ir a Pagar</a>
        </div>
    @endif
</div>