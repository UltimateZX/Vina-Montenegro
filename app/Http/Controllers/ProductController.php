<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $repository)
    {
        $this->productRepository = $repository;
    }

    public function index()
    {
        $productos = $this->productRepository->all();

        // AQUÍ ESTÁ LA CORRECCIÓN: 'home' (sin coma)
        return view('home', [
            'productos' => $productos
        ]);
    }
}