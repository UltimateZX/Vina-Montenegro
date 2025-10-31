@extends('layouts.admin')

@section('content')
<style>
    .validation-layout { display: flex; gap: 30px; }
    .voucher-container { flex: 2; }
    .order-details { flex: 1; }
    .voucher-image {
        max-width: 100%;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: zoom-in;
    }
    .details-box, .actions-box {
        background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px;
    }
    .details-box h3 { margin-top: 0; }
    .details-box p { margin: 5px 0; }
    .btn-approve, .btn-reject {
        width: 100%; padding: 15px; border: none; border-radius: 5px;
        font-size: 1.1em; cursor: pointer; font-weight: bold;
    }
    .btn-approve { background: #28a745; color: white; }
    .btn-reject { background: #dc3545; color: white; margin-top: 10px; }
    
    /* ¡NUEVO CSS PARA LA LISTA DE PRODUCTOS! */
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
    <h1>Revisar Pedido #{{ $pedido->id }}</h1>
</div>

<div class="validation-layout">
    <div class="voucher-container details-box">
        <h3>Voucher de Pago (Haz clic para ampliar)</h3>
        <a href="{{ asset($pedido->pago->url_voucher) }}" target="_blank">
            <img src="{{ asset($pedido->pago->url_voucher) }}" alt="Voucher" class="voucher-image">
        </a>
    </div>
    
    <div class="order-details">
        <div class="actions-box">
            <h3>Acciones</h3>
            <form action="{{ route('admin.pagos.approve', $pedido->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-approve">Aprobar Pago</button>
            </form>
            
            <hr style="margin: 20px 0;">
            
            <form action="{{ route('admin.pagos.reject', $pedido->id) }}" method="POST">
                @csrf
                <label for="notas_admin" style="font-weight: bold;">Motivo del Rechazo (Requerido)</label>
                <textarea name="notas_admin" style="width: 100%; height: 60px; margin-top: 10px;"></textarea>
                <button type="submit" class="btn-reject">Rechazar Pago</button>
            </form>
        </div>
        
        <div class="details-box">
            <h3>Detalles del Cliente</h3>
            <p><strong>Cliente:</strong> {{ $pedido->usuario->nombre_completo }}</p>
            <p><strong>Email:</strong> {{ $pedido->usuario->email }}</p>
            <p><strong>Monto:</strong> S/ {{ number_format($pedido->monto_total, 2) }}</p>
            <p><strong>Teléfono:</strong> {{ $pedido->telefono_contacto }}</p>
            <p><strong>Dirección:</strong> {{ $pedido->direccion_envio }}</p>
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