<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $table = 'pedidos';
    public $timestamps = false; // No usamos created_at/updated_at

    // Campos que podemos llenar masivamente
    protected $fillable = [
        'usuario_id',
        'monto_total',
        'direccion_envio',
        'telefono_contacto',
        'nombre_receptor',
        'estado',
        'fecha_pedido',
    ];

    public function usuario()
{
    return $this->belongsTo(Usuario::class);
}

    // Un Pedido tiene un Pago
    public function pago()
{
    return $this->hasOne(Pago::class);
}

public function detalles_pedido()
{
    return $this->hasMany(DetallePedido::class);
}
}