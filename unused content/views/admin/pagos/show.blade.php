@extends('layouts.admin')

@section('content')
<style>
    /* --- LAYOUT GENERAL --- */
    .validation-layout { 
        display: flex; 
        gap: 30px; 
        align-items: flex-start;
    }
    
    .voucher-container { flex: 2; } /* 66% en PC */
    .order-details { flex: 1; }     /* 33% en PC */
    
    .voucher-image {
        width: 100%;
        height: auto;
        max-height: 600px;
        object-fit: contain;
        border: 2px solid #eee;
        border-radius: 8px;
        background: #f9f9f9;
    }
    
    .details-box, .actions-box {
        background: #fff; 
        padding: 25px; 
        border-radius: 8px; 
        margin-bottom: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    .details-box h3, .actions-box h3 { margin-top: 0; font-size: 1.2em; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
    .details-box p { margin: 10px 0; line-height: 1.5; color: #555; }
    .details-box strong { color: #333; }
    
    /* Botones */
    .btn-approve, .btn-reject {
        width: 100%; padding: 12px; border: none; border-radius: 5px;
        font-size: 1em; cursor: pointer; font-weight: bold; transition: opacity 0.2s;
    }
    .btn-approve { background: #28a745; color: white; margin-bottom: 20px; }
    .btn-reject { background: #dc3545; color: white; margin-top: 10px; }
    .btn-approve:hover, .btn-reject:hover { opacity: 0.9; }
    
    /* Lista de Productos */
    .product-list { list-style: none; padding: 0; margin: 0; }
    .product-list li {
        display: flex; justify-content: space-between; padding: 10px 0;
        border-bottom: 1px solid #eee; font-size: 0.95em;
    }
    .product-list li:last-child { border-bottom: none; }
    .product-list .qty { font-weight: bold; color: #b42a6a; margin-right: 10px; }
    .product-list .name { flex-grow: 1; color: #333; }
    .product-list .price { color: #777; }

    /* --- 📱 RESPONSIVIDAD (CELULAR) --- */
    @media (max-width: 900px) {
        .validation-layout {
            flex-direction: column; /* Apilar bloques */
        }
        .voucher-container, .order-details {
            width: 100%; /* Ancho completo */
            flex: none;
        }
        
        .voucher-image { max-height: none; } /* Dejar crecer la imagen si es necesario */
        
        .admin-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>

<div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h1 style="margin: 0;">Revisar Pedido #{{ $pedido->id }}</h1>
    <a href="{{ route('admin.pagos.index') }}" style="text-decoration: none; color: #555; font-weight: bold; font-size: 0.9em;">&larr; Volver</a>
</div>

<div class="validation-layout">
    
    <!-- ZONA 1: VOUCHER (Izquierda en PC, Arriba en Móvil) -->
    <div class="voucher-container details-box">
        <h3>Voucher de Pago</h3>
        <p style="font-size: 0.9em; color: #888; margin-bottom: 15px;">Toca la imagen para verla en tamaño original.</p>
        
        <!-- CORRECCIÓN: Sin asset() para Base64 -->
        <a href="{{ $pedido->pago->url_voucher }}" target="_blank">
            <img src="{{ $pedido->pago->url_voucher }}" alt="Voucher" class="voucher-image">
        </a>
    </div>
    
    <!-- ZONA 2: DETALLES Y ACCIONES (Derecha en PC, Abajo en Móvil) -->
    <div class="order-details">
        
        <!-- Caja de Acciones -->
        <div class="actions-box">
            <h3>Acciones</h3>
            <form action="{{ route('admin.pagos.approve', $pedido->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-approve" onclick="return confirm('¿Aprobar este pago y procesar el pedido?')">
                    ✅ Aprobar Pago
                </button>
            </form>
            
            <div style="border-top: 1px dashed #ccc; margin: 15px 0; padding-top: 15px;">
                <form action="{{ route('admin.pagos.reject', $pedido->id) }}" method="POST">
                    @csrf
                    <label for="notas_admin" style="font-weight: bold; display: block; margin-bottom: 5px; color: #dc3545;">Rechazar Pago:</label>
                    <textarea name="notas_admin" placeholder="Motivo del rechazo (Ej: Voucher ilegible)..." required 
                              style="width: 100%; height: 70px; padding: 10px; border: 1px solid #dc3545; border-radius: 4px; box-sizing: border-box; margin-bottom: 10px;"></textarea>
                    <button type="submit" class="btn-reject" onclick="return confirm('¿Rechazar pago y cancelar pedido?')">
                        🚫 Rechazar y Cancelar
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Caja de Información -->
        <div class="details-box">
            <h3>Datos del Cliente</h3>
            <p><strong>Nombre:</strong> {{ $pedido->usuario->nombre_completo }}</p>
            <p><strong>Email:</strong> <a href="mailto:{{ $pedido->usuario->email }}">{{ $pedido->usuario->email }}</a></p>
            <p><strong>Teléfono:</strong> <a href="tel:{{ $pedido->telefono_contacto }}">{{ $pedido->telefono_contacto }}</a></p>
            <p><strong>Total a Pagar:</strong> <span style="color: #b42a6a; font-weight: bold; font-size: 1.1em;">S/ {{ number_format($pedido->monto_total, 2) }}</span></p>
            <p><strong>Dirección:</strong><br>{{ $pedido->direccion_envio }}</p>
        </div>

        <div class="details-box">
            <h3>Productos ({{ $pedido->detalles_pedido->sum('cantidad') }})</h3>
            <ul class="product-list">
                @foreach($pedido->detalles_pedido as $detalle)
                    <li>
                        <span class="qty">{{ $detalle->cantidad }}x</span>
                        <span class="name">{{ $detalle->producto->nombre }}</span>
                        <span class="price">S/ {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection