<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository; // Importa el Repositorio
use App\Models\Categoria; // Importa el Modelo Categoria

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $repository)
    {
        $this->productRepository = $repository;
    }

    /**
     * Muestra la página de inicio con el catálogo y los filtros.
     */
    public function index(Request $request)
    {
        // 1. Obtenemos los filtros de la URL (si existen)
        $searchTerm = $request->input('search');
        $categoriaId = $request->input('categoria_id');

        // 2. Pedimos los productos (pasando los filtros)
        $productos = $this->productRepository->all($searchTerm, $categoriaId);

        // 3. Obtenemos TODAS las categorías para mostrar en el sidebar
        $categorias = Categoria::all();

        // 4. Enviamos todo a la vista
        return view('home', [
            'productos' => $productos,
            'categorias' => $categorias, // Para el sidebar
            'categoriaActual' => $categoriaId // Para saber cuál marcar como "activa"
        ]);
    }
}