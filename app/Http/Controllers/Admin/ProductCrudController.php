<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // ¡Importante para borrar imágenes!


class ProductCrudController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $repository)
    {
        $this->productRepository = $repository;
    }

    public function index()
    {
        $productos = $this->productRepository->all();
        return view('admin.productos.index', [
            'productos' => $productos
        ]);
    }

    public function create()
    {
        $categorias = Categoria::all(); 
        return view('admin.productos.create', [
            'categorias' => $categorias
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('imagenes', 'public');
            $path = '/storage/' . $path;
        }

        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'url_imagen' => $path
        ]);

        return redirect()->route('admin.productos.index')
                         ->with('success', '¡Producto creado exitosamente!');
    }

    /**
     * ¡NUEVO! Muestra el formulario para editar un producto.
     */
    public function edit(Producto $producto)
    {
        // $producto ya viene inyectado gracias al Route Model Binding
        $categorias = Categoria::all(); // También necesitamos las categorías
        
        return view('admin.productos.edit', [
            'producto' => $producto,
            'categorias' => $categorias
        ]);
    }

    /**
     * ¡NUEVO! Actualiza el producto en la base de datos.
     */
    public function update(Request $request, Producto $producto)
    {
        // 1. Validamos (la imagen ahora es 'nullable', no 'required')
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048' // <-- CAMBIO
        ]);

        $path = $producto->url_imagen; // Mantenemos la imagen vieja por defecto

        // 2. Si se sube una imagen NUEVA
        if ($request->hasFile('imagen')) {
            
            // 2a. Borramos la imagen vieja del storage
            if ($producto->url_imagen) {
                // Quitamos '/storage/' para tener la ruta relativa (ej: 'imagenes/mi-vino.jpg')
                $oldPath = str_replace('/storage/', '', $producto->url_imagen);
                Storage::disk('public')->delete($oldPath);
            }

            // 2b. Guardamos la imagen nueva
            $path = $request->file('imagen')->store('imagenes', 'public');
            $path = '/storage/' . $path;
        }

        // 3. Actualizamos el producto en la BD
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'url_imagen' => $path // Guardamos la ruta (nueva o la vieja)
        ]);

        // 4. Redirigimos
        return redirect()->route('admin.productos.index')
                         ->with('success', '¡Producto actualizado exitosamente!');
    }

    /**
     * ¡NUEVO! Elimina el producto de la base de datos.
     */
    public function destroy(Producto $producto)
    {
        // 1. Borramos la imagen del storage
        if ($producto->url_imagen) {
            $oldPath = str_replace('/storage/', '', $producto->url_imagen);
            Storage::disk('public')->delete($oldPath);
        }
        
        // 2. Borramos el producto de la BD
        $producto->delete();
        
        // 3. Redirigimos
        return redirect()->route('admin.productos.index')
                         ->with('success', '¡Producto eliminado exitosamente!');
    }
}