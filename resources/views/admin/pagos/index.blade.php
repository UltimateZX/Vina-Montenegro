@extends('layouts.admin')

@section('content')
<style>
    /* --- ESTILOS GENERALES (PC) --- */
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .admin-table th, .admin-table td { border-bottom: 1px solid #eee; padding: 15px; text-align: left; }
    .admin-table th { background-color: #f9f9f9; color: #555; font-weight: 600; text-transform: uppercase; font-size: 0.85em; }
    .admin-table tr:hover { background-color: #fcfcfc; }

    .btn-review {
        background-color: #007bff; color: white; padding: 8px 15px;
        text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 0.9em;
        display: inline-block; transition: background 0.2s;
    }
    .btn-review:hover { background-color: #0056b3; }

    .alert-success {
        background-color: #d4edda; color: #155724; padding: 15px;
        border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;
    }

    .admin-header { margin-bottom: 25px; }
    .admin-header h1 { margin: 0; font-size: 1.8em; color: #333; }

    /* --- 📱 RESPONSIVIDAD (CELULAR) --- */
    @media (max-width: 768px) {
        /* Ocultar encabezados de tabla */
        .admin-table thead { display: none; }
        
        /* Convertir filas en tarjetas */
        .admin-table, .admin-table tbody, .admin-table tr, .admin-table td {
            display: block;
            width: 100%;
        }
        
        .admin-table tr {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        
        .admin-table td {
            padding: 8px 0;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95em;
        }
        
        /* Etiquetas simuladas a la izquierda */
        .admin-table td::before {
            content: attr(data-label); /* Usamos un atributo HTML para el texto */
            font-weight: bold;
            color: #777;
            margin-right: 15px;
        }
        
        /* El botón ocupa todo el ancho al final */
        .admin-table td:last-child {
            display: block;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #eee;
        }
        .admin-table td:last-child::before { display: none; }
        
        .btn-review { width: 100%; text-align: center; padding: 12px; box-sizing: border-box; }
    }
</style>

<div class="admin-header">
    <h1>Validación de Pagos Pendientes</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    @if($pedidos->isEmpty())
        <div style="text-align: center; padding: 40px; color: #777;">
            <p style="font-size: 2em; margin-bottom: 10px;">✅</p>
            <p>No hay pagos pendientes por validar.</p>
        </div>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Pedido ID</th>
                    <th>Cliente</th>
                    <th>Monto Total</th>
                    <th>Fecha de Carga</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                <tr>
                    <td data-label="Pedido ID"><strong>#{{ $pedido->id }}</strong></td>
                    <td data-label="Cliente">{{ $pedido->usuario->nombre_completo }}</td>
                    <td data-label="Monto" style="font-weight: bold; color: #b42a6a;">
                        S/ {{ number_format($pedido->monto_total, 2) }}
                    </td>
                    <td data-label="Fecha">{{ \Carbon\Carbon::parse($pedido->pago->fecha_carga)->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.pagos.show', $pedido->id) }}" class="btn-review">Revisar Voucher</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection