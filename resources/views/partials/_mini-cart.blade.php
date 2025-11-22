<style>
    .mini-cart-content {
        width: 100%; 
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
    
    /* ITEM DEL CARRITO */
    .mini-cart-item { 
        display: flex; gap: 10px; margin-bottom: 15px; 
        border-bottom: 1px solid #eee; padding-bottom: 10px; 
        align-items: flex-start; /* Alinear arriba */
    }
    .mini-cart-item img { 
        width: 60px; height: 60px; object-fit: contain; 
        border-radius: 4px; border: 1px solid #eee; 
    }
    
    .mini-cart-item .info { flex-grow: 1; }
    .mini-cart-item .info h5 { margin: 0 0 5px 0; font-size: 0.95em; color: #333; }
    .mini-cart-item .price { color: #b42a6a; font-weight: bold; font-size: 0.9em; display: block; margin-bottom: 5px;}
    
    /* CONTROLES (Botones +/-) */
    .mini-controls {
        display: flex; align-items: center; gap: 5px;
    }
    .mini-controls form { margin: 0; }
    
    .mini-qty-btn {
        width: 24px; height: 24px; border: 1px solid #ccc; background: #fff;
        cursor: pointer; font-size: 1em; border-radius: 3px; color: #555;
        display: flex; align-items: center; justify-content: center; padding: 0;
    }
    .mini-qty-btn:hover { background: #f0f0f0; }
    
    .mini-qty-display { font-size: 0.9em; font-weight: bold; padding: 0 5px; min-width: 20px; text-align: center; }
    
    /* Botón Eliminar (Basura) */
    .btn-remove-item {
        background: none; border: none; cursor: pointer; font-size: 1.2em;
        color: #dc3545; padding: 5px; margin-left: auto; /* Empuja a la derecha */
        opacity: 0.7; transition: opacity 0.2s;
    }
    .btn-remove-item:hover { opacity: 1; }
    
    /* Footer */
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
            <div style="text-align: center; padding-top: 50px; color: #999;">
                <p style="font-size: 3em; margin-bottom: 10px;">🛒</p>
                <p>Tu carrito está vacío.</p>
            </div>
        @else
            @foreach($cartItems as $id => $item)
                @php $total += $item['precio'] * $item['quantity']; @endphp
                
                <div class="mini-cart-item">
                    <!-- Imagen (Base64) -->
                    <img src="{{ $item['url_imagen'] }}" alt="{{ $item['nombre'] }}">
                    
                    <div class="info">
                        <h5>{{ $item['nombre'] }}</h5>
                        <span class="price">S/ {{ number_format($item['precio'] * $item['quantity'], 2) }}</span>
                        
                        <div class="mini-controls">
                            <!-- Disminuir -->
                            <form action="{{ route('cart.decrease') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $id }}">
                                <button type="submit" class="mini-qty-btn">-</button>
                            </form>
                            
                            <span class="mini-qty-display">{{ $item['quantity'] }}</span>
                            
                            <!-- Aumentar -->
                            <form action="{{ route('cart.increase') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $id }}">
                                <button type="submit" class="mini-qty-btn">+</button>
                            </form>
                        </div>
                    </div>

                    <!-- Eliminar -->
                    <form action="{{ route('cart.remove') }}" method="POST" style="margin: 0;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $id }}">
                        <button type="submit" class="btn-remove-item" title="Eliminar">
                            🗑️
                        </button>
                    </form>
                </div>
            @endforeach
        @endif
    </div>

    @if(!empty($cartItems))
        <div class="mini-cart-footer">
            <div class="mini-cart-total">
                Total: S/ {{ number_format($total, 2) }}
            </div>
            <a href="{{ route('checkout.index') }}" class="btn-checkout">Ir a Pagar</a>
        </div>
    @endif
</div>