<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        // Obtenemos todos los productos con su categoría y los paginamos
        $products = Product::with('category')->latest()->paginate(10);

        return Inertia::render('admin/products/Index', [
            'products' => $products,
        ]);
    }
}
