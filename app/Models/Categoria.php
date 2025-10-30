<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'categorias'; // Le decimos el nombre de la tabla

    /**
     * Indica si el modelo debe tener timestamps.
     *
     * @var bool
     */
    public $timestamps = false; // No usamos created_at/updated_at

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}