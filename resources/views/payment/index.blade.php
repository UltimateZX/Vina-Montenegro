@extends('layouts.app')

@section('content')
<style>
    .payment-page {
        max-width: 600px; margin: auto; background: #fff;
        padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    .qr-code img {
        width: 300px; height: 300px; margin: 20px auto;
        border: 5px solid #eee; border-radius: 8px;
    }
    .total-amount {
        font-size: 2em; font-weight: bold; color: #b42a6a; margin-bottom: 20px;
    }
    .form-group { margin-bottom: 20px; text-align: left; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
    .form-group input {
        width: 100%; padding: 10px; border: 1px solid #ddd;
        border-radius: 4px; box-sizing: border-box;
    }
    .form-actions button {
        background-color: #28a745; color: white; padding: 12px 20px;
        border: none; border-radius: 5px; cursor: pointer;
        font-size: 1.1em; width: 100%;
    }
</style>

<div class="payment-page">
    <h2>¡Casi listo! Realiza tu pago</h2>
    <p>Escanea el código QR con Yape y paga el monto exacto:</p>

    <div class="total-amount">
        S/ {{ number_format($pedido->monto_total, 2) }}
    </div>

    <div class="qr-code">
        <img src="{{ asset('imagenes/qr-yape.png') }}" alt="Código QR de Yape">
    </div>

    <p>Una vez realizado el pago, sube tu voucher (captura de pantalla) aquí:</p>

    <form action="{{ route('payment.store', ['pedido' => $pedido->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="voucher">Subir Voucher</label>
            <input type="file" name="voucher" id="voucher" required>
        </div>

        <div class.form-actions">
            <button type="submit">Finalizar Compra</button>
        </div>
    </form>
</div>
@endsection