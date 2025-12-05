@extends('layouts.app')

@section('content')

<style>
    .checkout-page-container {
        max-width: 600px;
        margin: 30px auto;
        background: #fff;
        border-radius: 8px;
        padding: 30px 40px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .checkout-page-container h2 {
        margin-top: 0; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px;
    }
    .checkout-page-container p {
        text-align: center; color: #555; font-size: 0.9em; margin-bottom: 25px;
    }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
    .form-group input, .form-group textarea {
        width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;
        box-sizing: border-box; font-family: inherit; font-size: 1em;
    }
    .form-group textarea { height: 100px; resize: vertical; }
    
    .btn-submit-checkout {
        display: block; width: 100%; padding: 15px; background: #b42a6a; color: white;
        text-decoration: none; text-align: center; border: none; border-radius: 5px;
        font-size: 1.1em; cursor: pointer; margin-top: 10px;
    }
    .btn-submit-checkout:hover { background: #9a2359; }
    
    .error-message-box {
        background-color: #f8d7da; color: #721c24; padding: 15px;
        border-radius: 5px; margin-bottom: 20px;
    }
    .error-message-box ul { margin: 0 0 0 20px; padding: 0; }

    /* --- 📱 CSS RESPONSIVO --- */
    @media (max-width: 600px) {
        .checkout-page-container {
            margin: 15px; /* Menos margen externo */
            padding: 20px; /* Menos relleno interno */
            width: auto; /* Ocupa lo que pueda */
        }
        .checkout-page-container h2 { font-size: 1.5em; }
    }
</style>

<div class="checkout-page-container">
    <h2>Información de Envío</h2>
    <p>Por favor, completa dónde te enviaremos tu pedido.</p>

    <form action="{{ route('checkout.placeOrder') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="error-message-box">
                <strong>¡Ups! Hubo un problema:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label for="nombre_receptor">Nombre de quien recibe</label>
            <input type="text" name="nombre_receptor" id="nombre_receptor" 
                   value="{{ old('nombre_receptor', auth()->user()->nombre_completo) }}" required>
        </div>

        <div class="form-group">
            <label for="telefono_contacto">Teléfono de Contacto</label>
            <input type="tel" name="telefono_contacto" id="telefono_contacto" 
                   placeholder="987654321" value="{{ old('telefono_contacto') }}" required>
        </div>

        <div class="form-group">
            <label for="direccion_envio">Dirección de Envío</label>
            <textarea name="direccion_envio" id="direccion_envio" 
                      placeholder="Ej. Av. Los Girasoles 123, Urb...." required>{{ old('direccion_envio') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit-checkout">Confirmar y Pagar</button>
        </div>
    </form>
</div>
@endsection