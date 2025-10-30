<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'productos';

    /**
     * Indica si el modelo debe tener timestamps.
     */
    public $timestamps = false; // No usamos created_at/updated_at

    /**
     * Los atributos que se pueden asignar masivamente.
     * ¡AQUÍ ESTÁ LA CORRECCIÓN!
     */
    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'url_imagen', // <-- Añadimos este campo a la lista
    ];

    /**
     * Define la relación con Categoria.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}