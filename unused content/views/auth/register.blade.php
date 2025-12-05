@extends('layouts.app')

@section('content')

<style>
    .auth-container {
        max-width: 450px;
        margin: 50px auto; /* Centra y da espacio arriba/abajo */
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .auth-logo {
        text-align: center;
        margin-bottom: 20px;
    }
    .auth-logo img {
        width: 100px; /* Puedes poner tu logo de Viña Montenegro aquí */
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box; /* Importante */
    }
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }
    .btn-primary {
        background: #b42a6a; /* Color fucsia */
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }
    .link-secondary {
        font-size: 0.9em;
        color: #007bff;
        text-decoration: none;
    }
</style>

<div class="auth-container">
    
    <h2 style="text-align: center; margin-top: 0;">Crear Cuenta</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="nombre_completo" class="form-label">Nombre Completo</label>
            <input id="nombre_completo" class="form-input" type="text" name="nombre_completo" value="{{ old('nombre_completo') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('nombre_completo')" class="mt-2" />
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmar Password</label>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="form-actions">
            <a class="link-secondary" href="{{ route('login') }}">
                Already registered?
            </a>

            <button type="submit" class="btn-primary">
                Register
            </button>
        </div>
    </form>
</div>
@endsection