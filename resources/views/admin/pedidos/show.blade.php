@extends('layouts.admin')

@section('content')
<style>
    /* PC: Diseño en 2 columnas */
    .validation-layout { display: flex; gap: 30px; align-items: flex-start; }
    .voucher-container { flex: 2; }
    .order-details { flex: 1; }
    
    .voucher-image { 
        width: 100%; 
        max-width: 600px; /* Límite en PC */
        height: auto; 
        border: 2px solid #ddd; border-radius: 8px; object-fit: contain;
    }
    
    .details-box { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .details-box h3 { margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    .details-box p { margin: 8px 0; }
    
    .status-procesando { color: #007bff; font-weight: bold; font-size: 1.1em; }
    .status-cancelado { color: #dc3545; font-weight: bold; font-size: 1.1em; }
    .status-completado { color: #28a745; font-weight: bold; font-size: 1.1em; }
    .status-pendiente_validacion { color: #e67e22; font-weight: bold; font-size: 1.1em; }

    .product-list { list-style: none; padding: 0; margin: 0; }
    .product-list li { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; font-size: 0.95em; }
    .product-list li:last-child { border-bottom: none; }
    .product-list .qty { font-weight: bold; color: #b42a6a; margin-right: 10px; }
    .product-list .name { flex-grow: 1; }
    .product-list .price { color: #555; }

    /* --- 📱 RESPONSIVIDAD --- */
    @media (max-width: 900px) {
        .validation-layout {
            flex-direction: column; /* Apilar columnas */
        }
        .voucher-container, .order-details {
            width: 100%; /* Ocupar todo el ancho */
            flex: none;
        }
        .voucher-image {
            max-width: 100%; /* Imagen fluida */
        }
        .admin-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .admin-header a { width: 100%; text-align: center; box-sizing: border-box; }
    }
</style>

<div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0;">Detalle del Pedido #{{ $pedido->id }}</h1>
    <a href="{{ route('admin.pedidos.index') }}" style="padding: 8px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">&larr; Volver</a>
</div>

<div class="validation-layout">
    <!-- Columna Izquierda: Voucher -->
    <div class="voucher-container details-box">
        <h3>Voucher de Pago</h3>
        @if($pedido->pago)
            <p style="color: #777; font-size: 0.9em; margin-bottom: 10px;">Toca la imagen para ver en tamaño completo.</p>
            <!-- CORRECCIÓN: Sin asset() para Base64 -->
            <a href="{{ $pedido->pago->url_voucher }}" target="_blank">
                <img src="{{ $pedido->pago->url_voucher }}" alt="Voucher" class="voucher-image">
            </a>
        @else
            <div style="padding: 40px; text-align: center; background: #f9f9f9; border-radius: 8px;">
                <p style="color: #999;">🚫 No se encontró voucher para este pedido.</p>
            </div>
        @endif
    </div>
    
    <!-- Columna Derecha: Detalles -->
    <div class="order-details">
        <div class="details-box">
            <h3>Datos del Pedido</h3>
            <p>
                <strong>Estado:</strong>
                <span class="status-{{ $pedido->estado }}">
                    {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                </span>
            </p>
            <p><strong>Cliente:</strong> {{ $pedido->usuario->nombre_completo }}</p>
            <p><strong>Monto:</strong> S/ {{ number_format($pedido->monto_total, 2) }}</p>
            <p><strong>Teléfono:</strong> <a href="tel:{{ $pedido->telefono_contacto }}">{{ $pedido->telefono_contacto }}</a></p>
            <hr style="border: 0; border-top: 1px dashed #ddd; margin: 10px 0;">
            <p><strong>Dirección:</strong><br> {{ $pedido->direccion_envio }}</p>
            
            @if($pedido->pago && $pedido->pago->estado_validacion == 'rechazado')
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-top: 10px;">
                    <strong>Motivo del Rechazo:</strong><br>
                    {{ $pedido->pago->notas_admin }}
                </div>
            @endif
        </div>

        <div class="details-box">
            <h3>Productos</h3>
            <ul class="product-list">
                @foreach($pedido->detalles_pedido as $detalle)
                    <li>
                        <span class="qty">{{ $detalle->cantidad }} x</span>
                        <span class="name">{{ $detalle->producto->nombre }}</span>
                        <span class="price">S/ {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <div style="text-align: right; margin-top: 15px; font-weight: bold; font-size: 1.1em; color: #b42a6a; border-top: 1px solid #eee; padding-top: 10px;">
                Total: S/ {{ number_format($pedido->monto_total, 2) }}
            </div>
        </div>
    </div>
</div>
@endsection