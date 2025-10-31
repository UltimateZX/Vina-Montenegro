@extends('layouts.app')

@section('content')
<style>
    .checkout-page {
        max-width: 600px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box; /* Importante para el padding */
    }
    .form-group textarea {
        height: 100px;
        resize: vertical;
    }
    .form-actions button {
        background-color: #b42a6a;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.1em;
        width: 100%;
    }
</style>

<div class="checkout-page">
    <h2>Información de Envío</h2>
    <p>Por favor, completa dónde te enviaremos tu pedido.</p>

    <form action="{{ route('checkout.placeOrder') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nombre_receptor">Nombre de quien recibe</label>
            <input type="text" name="nombre_receptor" id="nombre_receptor" 
                   value="{{ auth()->user()->nombre_completo }}" required>
        </div>

        <div class="form-group">
            <label for="telefono_contacto">Teléfono de Contacto</label>
            <input type="tel" name="telefono_contacto" id="telefono_contacto" 
                   placeholder="987654321" required>
        </div>

        <div class="form-group">
            <label for="direccion_envio">Dirección de Envío</label>
            <textarea name="direccion_envio" id="direccion_envio" 
                      placeholder="Ej. Av. Los Girasoles 123, Urb...." required></textarea>
        </div>

        <div class="form-actions">
            <button type="submit">Confirmar y Pagar</button>
        </div>
    </form>
</div>
@endsection