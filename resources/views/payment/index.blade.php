@extends('layouts.app')

@section('content')

<style>
    .payment-page-container {
        max-width: 600px;
        margin: 30px auto; /* Despega del header y centra */
        background: #fff;
        border-radius: 8px;
        padding: 30px 40px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        text-align: center;
    }
    .payment-page-container h2 {
        margin-top: 0;
        font-size: 1.8em;
    }
    .payment-page-container p {
        color: #555;
        font-size: 1.1em;
        line-height: 1.6;
    }
    
    /* Estilo para el Monto a Pagar */
    .total-amount {
        font-size: 2.5em;
        font-weight: bold;
        color: #b42a6a; /* Color fucsia */
        margin: 20px 0;
    }
    
    /* ¡NUEVO! Estilo para el número de Yape */
    .yape-number {
        font-size: 1.3em;
        font-weight: bold;
        margin: 15px 0;
        background: #fdf2f6;
        padding: 10px;
        border-radius: 5px;
    }
    
    /* Estilo para la imagen QR */
    .qr-code img {
        width: 300px;
        height: 300px;
        margin: 10px auto 20px auto;
        border: 5px solid #eee;
        border-radius: 8px;
    }

    /* Estilos del Formulario de Subida */
    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        font-size: 1.1em;
    }
    .form-group input[type="file"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 1em;
    }
    
    /* Estilos del Botón */
    .btn-submit-payment {
        display: block;
        width: 100%;
        padding: 15px;
        background: #28a745; /* Verde para "Finalizar" */
        color: white;
        text-decoration: none;
        text-align: center;
        border: none;
        border-radius: 5px;
        font-size: 1.1em;
        cursor: pointer;
        margin-top: 10px;
    }
    .btn-submit-payment:hover {
        background: #218838;
    }
</style>

<div class="payment-page-container">
    <h2>¡Casi listo! Realiza tu pago</h2>
    <p>Escanea el código QR con Yape y paga el monto exacto:</p>
    
    <div class="total-amount">
        S/ {{ number_format($pedido->monto_total, 2) }}
    </div>

    <p>o yapea al número:</p>
    <div class="yape-number">
        904 881 991
    </div>

    <div class="qr-code">
        <img src="{{ asset('imagenes/qr-yape.png') }}" alt="Código QR de Yape">
    </div>

    <p style="font-weight: bold; font-size: 1.1em; border-top: 1px solid #eee; padding-top: 20px;">
        Una vez realizado el pago, sube tu voucher (captura de pantalla) aquí:
    </p>

    <form action="{{ route('payment.store', ['pedido' => $pedido->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @if ($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: left;">
                <strong>¡Ups! Hubo un problema:</strong>
                <ul style="margin: 10px 0 0 20px; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="form-group">
            <label for="voucher">Subir Voucher</label>
            <input type="file" name="voucher" id="voucher" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit-payment">Finalizar Compra</button>
        </div>
    </form>
</div>
@endsection