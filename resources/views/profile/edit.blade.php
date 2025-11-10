@extends('layouts.app')

@section('content')

<style>

    .btn-pay-now {
    background-color: #28a745; /* Verde */
    color: white;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.9em;
    font-weight: bold;
}
.btn-pay-now:hover {
    background-color: #218838;
}
    .profile-container {
        max-width: 800px;
        margin: 30px auto; /* Para despegarlo del header */
    }
    .profile-card {
        background: #fff;
        border-radius: 8px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .profile-card h2 {
        margin-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }
    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box; 
    }
    .form-button {
        background-color: #b42a6a;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
    }
    /* Estilos para la tabla (copiados del admin) */
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    .admin-table th { background-color: #f2f2f2; }
    .admin-table tr:hover { background-color: #f1f1f1; }
    .status-procesando { color: #007bff; font-weight: bold; }
    .status-cancelado { color: #dc3545; font-weight: bold; }
    .status-completado { color: #28a745; font-weight: bold; }
    .status-pendiente_validacion { color: #ffc107; font-weight: bold; }
    .status-pendiente_pago { color: #6c757d; font-weight: bold; }
</style>

<div class="profile-container">

    <div class="profile-card">
        <h2>Información del Perfil</h2>
        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="nombre_completo">Nombre Completo</label>
                <input id="nombre_completo" name="nombre_completo" type="text" value="{{ old('nombre_completo', $user->nombre_completo) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <button type="submit" class="form-button">Guardar Cambios</button>
            @if (session('status') === 'profile-updated')
                <p style="color: green; margin-top: 10px;">¡Guardado!</p>
            @endif
        </form>
    </div>

    <div class="profile-card">
        <h2>Actualizar Contraseña</h2>
        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="current_password">Contraseña Actual</label>
                <input id="current_password" name="current_password" type="password" required>
            </div>
            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input id="password" name="password" type="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>
            <button type="submit" class="form-button">Guardar Contraseña</button>
            @if (session('status') === 'password-updated')
                <p style="color: green; margin-top: 10px;">¡Contraseña guardada!</p>
            @endif
        </form>
    </div>
    
    <div class="profile-card">
        <h2>Historial de Mis Pedidos</h2>
        
        @if($pedidos->isEmpty())
            <p>Aún no has realizado ningún pedido.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th> </tr>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
        <td>#{{ $pedido->id }}</td>
        <td>{{ $pedido->fecha_pedido }}</td>
        <td>S/ {{ number_format($pedido->monto_total, 2) }}</td>
        <td>
            <span class="status-{{ $pedido->estado }}">
                {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
            </span>
        </td>

        <td>
            @if($pedido->estado == 'pendiente_pago')
                <a href="{{ route('payment.index', ['pedido' => $pedido->id]) }}" class="btn-pay-now">
                    Pagar Ahora
                </a>
            @else
                --
            @endif
        </td>

    </tr>
@endforeach
</tbody>
</table>
        @endif
    </div>
    
    </div>
@endsection