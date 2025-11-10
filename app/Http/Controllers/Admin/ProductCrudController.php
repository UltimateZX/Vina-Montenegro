<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto; // <-- Solo usamos el Modelo
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductCrudController extends Controller
{
    // ¡HEMOS BORRADO EL REPOSITORIO DE AQUÍ!
    // Ya no hay __construct()

    /**
     * Muestra la lista de productos (la tabla).
     */
    public function index()
    {
        // ¡ESTA ES LA CORRECIÓN!
        // Usamos el Modelo 'Producto' para traer TODO,
        // incluyendo los inactivos.
        $productos = Producto::orderBy('id', 'desc')->get(); 

        return view('admin.productos.index', [
            'productos' => $productos
        ]);
    }

    /**
     * Muestra el formulario para crear un producto.
     */
    public function create()
    {
        $categorias = Categoria::all(); 
        return view('admin.productos.create', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Guarda el nuevo producto en la base de datos.
     */
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
            'url_imagen' => $path,
            'is_active' => true // Por defecto está activo
        ]);

        return redirect()->route('admin.productos.index')
                         ->with('success', '¡Producto creado exitosamente!');
    }

    /**
     * Muestra el formulario para editar un producto.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('admin.productos.edit', [
            'producto' => $producto,
            'categorias' => $categorias
        ]);
    }

    /**
     * Actualiza el producto en la base de datos.
     */
    public function update(Request $request, Producto $producto)
    {
        // ... (Este método ya estaba bien)
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);
        $path = $producto->url_imagen;
        if ($request->hasFile('imagen')) {
            if ($producto->url_imagen) {
                $oldPath = str_replace('/storage/', '', $producto->url_imagen);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('imagen')->store('imagenes', 'public');
            $path = '/storage/' . $path;
        }
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'url_imagen' => $path
        ]);
        return redirect()->route('admin.productos.index')
                         ->with('success', '¡Producto actualizado exitosamente!');
    }

    /**
     * Desactiva (archiva) el producto.
     */
    public function destroy(Producto $producto)
    {
        $producto->is_active = false;
        $producto->save();
        
        return redirect()->route('admin.productos.index')
                         ->with('success', '¡Producto desactivado (archivado) exitosamente!');
    }

    /**
     * Reactiva un producto que estaba archivado.
     */
    public function activate(Producto $producto)
    {
        $producto->is_active = true;
        $producto->save();
        
        return redirect()->route('admin.productos.index')
                         ->with('success', '¡Producto reactivado exitosamente!');
    }
}