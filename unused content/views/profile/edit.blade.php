@extends('layouts.app')

@section('content')

<style>
    .btn-pay-now {
        background-color: #28a745; /* Verde */
        color: white;
        padding: 8px 12px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 0.9em;
        font-weight: bold;
        display: inline-block;
        text-align: center;
        transition: background 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-pay-now:hover { background-color: #218838; }

    .btn-cancel-order {
        background-color: #dc3545; /* Rojo */
        color: white;
        padding: 8px 12px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 0.9em;
        font-weight: bold;
        display: inline-block;
        text-align: center;
        transition: background 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-cancel-order:hover { background-color: #c82333; }
    
    .profile-container {
        max-width: 900px;
        margin: 30px auto; 
        padding: 0 20px;
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
        font-size: 1.5em;
        color: #333;
    }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
    .form-group input {
        width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;
        box-sizing: border-box; font-size: 1em;
    }
    
    .form-button {
        background-color: #b42a6a; color: white; padding: 12px 20px; border: none;
        border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold;
        transition: background 0.2s;
    }
    .form-button:hover { background-color: #901e52; }

    /* --- ESTILOS TABLA (SOLO PC) --- */
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .admin-table th, .admin-table td { border-bottom: 1px solid #eee; padding: 15px; text-align: left; }
    .admin-table th { background-color: #f9f9f9; color: #555; font-weight: 600; text-transform: uppercase; font-size: 0.85em; letter-spacing: 0.5px; }
    .admin-table tr:last-child td { border-bottom: none; }

    .status-procesando { color: #007bff; font-weight: bold; background: #e7f1ff; padding: 4px 8px; border-radius: 4px; font-size: 0.9em; }
    .status-cancelado { color: #dc3545; font-weight: bold; background: #fce8ea; padding: 4px 8px; border-radius: 4px; font-size: 0.9em; }
    .status-completado { color: #28a745; font-weight: bold; background: #e6f4ea; padding: 4px 8px; border-radius: 4px; font-size: 0.9em; }
    .status-pendiente_validacion { color: #e67e22; font-weight: bold; background: #fdf3e5; padding: 4px 8px; border-radius: 4px; font-size: 0.9em; }
    .status-pendiente_pago { color: #6c757d; font-weight: bold; background: #f2f2f2; padding: 4px 8px; border-radius: 4px; font-size: 0.9em; }

    .user-actions { display: flex; gap: 5px; flex-wrap: wrap; }

    /* --- 📱 RESPONSIVIDAD (CELULAR) --- */
    @media (max-width: 768px) {
        .profile-container { padding: 10px; margin: 15px auto; }
        .profile-card { padding: 20px; }
        
        .admin-table, .admin-table thead, .admin-table tbody, .admin-table th, .admin-table td, .admin-table tr { 
            display: block; 
        }
        .admin-table thead { display: none; } 
        
        .admin-table tr {
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        
        .admin-table td {
            border: none;
            padding: 8px 0;
            position: relative;
            padding-left: 40%; 
            text-align: right;
        }
        
        .admin-table td:before {
            position: absolute; top: 8px; left: 0; width: 35%; white-space: nowrap; font-weight: bold; color: #777; text-align: left;
        }
        
        .admin-table td:nth-of-type(1):before { content: "ID Pedido"; }
        .admin-table td:nth-of-type(2):before { content: "Fecha"; }
        .admin-table td:nth-of-type(3):before { content: "Total"; }
        .admin-table td:nth-of-type(4):before { content: "Estado"; }
        .admin-table td:nth-of-type(5):before { content: "Acciones"; }
        
        .user-actions { justify-content: flex-end; }
        .btn-pay-now, .btn-cancel-order { width: 100%; box-sizing: border-box; margin-top: 5px; }
    }
</style>

<div class="profile-container">

    <!-- 1. Información Personal -->
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
                <p style="color: green; margin-top: 10px; font-weight: bold;">✅ ¡Información actualizada!</p>
            @endif
        </form>
    </div>

    <!-- 2. Cambio de Contraseña -->
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
                <p style="color: green; margin-top: 10px; font-weight: bold;">✅ ¡Contraseña cambiada!</p>
            @endif
        </form>
    </div>
    
    <!-- 3. Historial de Pedidos (Responsivo) -->
    <div class="profile-card">
        <h2>Historial de Mis Pedidos</h2>
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                {{ session('error') }}
            </div>
        @endif
        
        @if($pedidos->isEmpty())
            <div style="text-align: center; color: #777; padding: 20px;">
                <p>Aún no has realizado ningún pedido.</p>
                <a href="{{ route('home') }}" style="color: #b42a6a; font-weight: bold;">Ir a comprar</a>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <td><strong>#{{ $pedido->id }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}</td>
                            <td style="font-weight: bold;">S/ {{ number_format($pedido->monto_total, 2) }}</td>
                            <td>
                                <span class="status-{{ $pedido->estado }}">
                                    {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                                </span>
                            </td>
                            <td>
                                <div class="user-actions">
                                    @if($pedido->estado == 'pendiente_pago' || $pedido->estado == 'pendiente_validacion')
                                        
                                        @if($pedido->estado == 'pendiente_pago')
                                            <a href="{{ route('payment.index', ['pedido' => $pedido->id]) }}" class="btn-pay-now">
                                                Pagar
                                            </a>
                                        @endif

                                        <form action="{{ route('user.pedidos.cancel', $pedido->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="btn-cancel-order" onclick="return confirm('¿Estás seguro de cancelar este pedido? El stock será liberado.')">
                                                Cancelar
                                            </button>
                                        </form>
                                        
                                    @else
                                        <span style="color: #aaa; font-size: 0.9em;">Sin acciones</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
</div>
@endsection