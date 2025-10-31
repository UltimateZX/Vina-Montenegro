@extends('layouts.admin')

@section('content')
<style>
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    .admin-table th { background-color: #f2f2f2; }
    .admin-table tr:hover { background-color: #f1f1f1; }
    .btn-review {
        background-color: #007bff; color: white; padding: 8px 12px;
        text-decoration: none; border-radius: 5px;
    }
    .alert-success {
        background-color: #d4edda; color: #155724; padding: 10px;
        border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;
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

<div style="background: white; padding: 20px; border-radius: 8px;">
    @if($pedidos->isEmpty())
        <p>No hay pedidos pendientes de validación.</p>
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
                    <td>{{ $pedido->id }}</td>
                    <td>{{ $pedido->usuario->nombre_completo }}</td>
                    <td>S/ {{ number_format($pedido->monto_total, 2) }}</td>
                    <td>{{ $pedido->pago->fecha_carga }}</td>
                    <td>
                        <a href="{{ route('admin.pagos.show', $pedido->id) }}" class="btn-review">Revisar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection