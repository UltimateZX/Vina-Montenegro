<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $table = 'pagos';
    public $timestamps = false; // No usamos created_at/updated_at

    // Campos que podemos llenar
    protected $fillable = [
        'pedido_id',
        'metodo_pago',
        'url_voucher',
        'fecha_carga',
        'estado_validacion',
    ];

    public function pedido()
{
    return $this->belongsTo(Pedido::class);
}
}