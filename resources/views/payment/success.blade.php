@extends('layouts.app')

@section('content')
<style>
    .success-page {
        max-width: 600px; margin: 50px auto; background: #fff;
        padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    .success-page h1 { color: #28a745; }
    .success-page a {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 20px;
        background: #b42a6a;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
</style>

<div class="success-page">
    <h1>¡Gracias por tu compra!</h1>
    <p>Hemos recibido tu voucher de pago.</p>
    <p>Tu pedido está ahora <strong>pendiente de validación</strong>. Te notificaremos una vez que el vendedor haya confirmado el pago.</p>
    <a href="{{ route('home') }}">Volver al Catálogo</a>
</div>
@endsection