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
    'nombre',
    'descripcion',
    'precio',
    'stock',
    'categoria_id',
    'is_active',
    'url_imagen', // <--- ESTO ES LO QUE HACE QUE SE GUARDE EN LA BD
];

    /**
     * Define la relación con Categoria.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}