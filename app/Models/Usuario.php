<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// ¡Importante! Usamos 'Authenticatable' como base
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable // <-- Debe extender 'Authenticatable'
{
    use HasFactory, Notifiable;

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
    ];

    /**
     * Los atributos que deben ocultarse.
     */
    protected $hidden = [
        'password', // <-- ¡Corregido!
        'remember_token',
    ];
}