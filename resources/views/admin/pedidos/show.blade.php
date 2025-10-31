@extends('layouts.admin')

@section('content')
<style>
    .validation-layout { display: flex; gap: 30px; }
    .voucher-container { flex: 2; }
    .order-details { flex: 1; }
    .voucher-image { max-width: 100%; border: 2px solid #ddd; border-radius: 8px; }
    .details-box { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .details-box h3 { margin-top: 0; }
    .details-box p { margin: 5px 0; }
    .status-procesando { color: #007bff; font-weight: bold; font-size: 1.2em; }
    .status-cancelado { color: #dc3545; font-weight: bold; font-size: 1.2em; }
    .status-completado { color: #28a745; font-weight: bold; font-size: 1.2em; }

    /* CSS PARA LA LISTA DE PRODUCTOS */
    .product-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .product-list li {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
        font-size: 0.9em;
    }
    .product-list li:last-child { border-bottom: none; }
    .product-list .qty {
        font-weight: bold;
        color: #b42a6a;
        margin-right: 10px;
    }
    .product-list .name { flex-grow: 1; }
    .product-list .price { color: #555; }
</style>

<div class="admin-header">
    <h1>Detalle del Pedido #{{ $pedido->id }}</h1>
    <a href="{{ route('admin.pedidos.index') }}" style="font-size: 0.9em;">&larr; Volver al Historial</a>
</div>

<div class="validation-layout">
    <div class="voucher-container details-box">
        <h3>Voucher de Pago</h3>
        @if($pedido->pago)
            <img src="{{ asset($pedido->pago->url_voucher) }}" alt="Voucher" class="voucher-image">
        @else
            <p>No se encontró voucher para este pedido.</p>
        @endif
    </div>
    
    <div class="order-details">
        <div class="details-box">
            <h3>Detalles del Pedido</h3>
            <p>
                <strong>Estado:</strong>
                <span class="status-{{ $pedido->estado }}">{{ ucfirst($pedido->estado) }}</span>
            </p>
            <p><strong>Cliente:</strong> {{ $pedido->usuario->nombre_completo }}</p>
            <p><strong>Monto:</strong> S/ {{ number_format($pedido->monto_total, 2) }}</p>
            <p><strong>Teléfono:</strong> {{ $pedido->telefono_contacto }}</p>
            <p><strong>Dirección:</strong> {{ $pedido->direccion_envio }}</p>
            
            @if($pedido->pago && $pedido->pago->estado_validacion == 'rechazado')
                <p style="color: red; border-top: 1px solid #eee; padding-top: 10px;">
                    <strong>Motivo del Rechazo:</strong><br>
                    {{ $pedido->pago->notas_admin }}
                </p>
            @endif
        </div>

        <div class="details-box">
            <h3>Productos del Pedido</h3>
            <ul class="product-list">
                @foreach($pedido->detalles_pedido as $detalle)
                    <li>
                        <span class="qty">{{ $detalle->cantidad }} x</span>
                        <span class="name">{{ $detalle->producto->nombre }}</span>
                        <span class="price">S/ {{ number_format($detalle->precio_unitario, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection