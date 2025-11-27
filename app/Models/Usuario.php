<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// ¡Importante! Usamos 'Authenticatable' como base
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable // <-- Debe extender 'Authenticatable'
{
    use HasFactory, Notifiable;

    protected static function booted()
    {
        static::creating(function ($usuario) {
            $usuario->fecha_registro = now();
        });
    }

    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'usuarios';

    /**
     * Indica si el modelo debe tener timestamps.
     */
    public $timestamps = false; // No usamos created_at/updated_at

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre_completo',
        'email',
        'password', // <-- ¡Corregido! (antes era password_hash)
        'rol',
        'fecha_registro',
    ];

    /**
     * Los atributos que deben ocultarse.
     */
    protected $hidden = [
        'password', // <-- ¡Corregido!
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'fecha_registro' => 'datetime',
        ];
    }
    /**
 * Un Usuario puede tener muchos Pedidos.
 */
    public function pedidos()
{
    return $this->hasMany(Pedido::class);
}
}
