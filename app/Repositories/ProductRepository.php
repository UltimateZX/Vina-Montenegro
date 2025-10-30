<?php

namespace App\Repositories; // <-- ¡¡ESTA LÍNEA ES LA SOLUCIÓN!!

use App\Models\Producto; // <-- Importa el Modelo

class ProductRepository
{
    /**
     * Obtiene todos los productos de la base de datos.
     */
    public function all()
    {
        // Usamos el Modelo de Eloquent para traer todo
        return Producto::all();
    }

    /**
     * Busca un producto por su ID.
     */
    public function find(int $id)
    {
        return Producto::findOrFail($id);
    }
}