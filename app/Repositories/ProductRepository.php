<?php

namespace App\Repositories;

use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    /**
     * Obtiene productos (activos) con filtros de búsqueda y categoría.
     */
    public function all($searchTerm = null, $categoriaId = null)
    {
        // Empezamos la consulta Y SOLO MOSTRAMOS PRODUCTOS ACTIVOS
        $query = Producto::query()->where('is_active', true);

        // Filtro de Búsqueda (ignora mayúsculas)
        if ($searchTerm) {
            $searchTermLower = strtolower($searchTerm);
            $query->where(function($q) use ($searchTermLower) {
                $q->where(DB::raw('LOWER(nombre)'), 'LIKE', "%{$searchTermLower}%")
                ->orWhere(DB::raw('LOWER(descripcion)'), 'LIKE', "%{$searchTermLower}%");
            });
        }

        // Filtro de Categoría
        if ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        }

        return $query->get();
    }

    /**
     * Busca un producto por su ID.
     */
    public function find(int $id)
    {
        return Producto::findOrFail($id);
    }
}