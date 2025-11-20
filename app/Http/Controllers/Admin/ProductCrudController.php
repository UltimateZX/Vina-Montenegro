<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
// Ya no necesitamos Cloudinary ni Storage
// use CloudinaryLabs...

class ProductCrudController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('id', 'desc')->get(); 
        return view('admin.productos.index', ['productos' => $productos]);
    }

    public function create()
    {
        $categorias = Categoria::all(); 
        return view('admin.productos.create', ['categorias' => $categorias]);
    }

    // --- GUARDAR (BASE64) ---
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

        $imagenBase64 = null;

        if ($request->hasFile('imagen')) {
            // 1. Obtener la imagen real
            $file = $request->file('imagen');
            
            // 2. Leer el contenido del archivo
            $path = $file->getRealPath();
            $imagenData = file_get_contents($path);
            
            // 3. Convertir a BASE64 con su cabecera correcta
            // Quedará algo como: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA..."
            $imagenBase64 = 'data:' . $file->getClientMimeType() . ';base64,' . base64_encode($imagenData);
        }

        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria_id = $request->categoria_id;
        
        // Guardamos el "chorizo" de texto gigante en la BD
        $producto->url_imagen = $imagenBase64; 
        
        $producto->is_active = true;
        $producto->save();

        return redirect()->route('admin.productos.index')
                         ->with('success', '¡Producto creado exitosamente!');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('admin.productos.edit', [
            'producto' => $producto,
            'categorias' => $categorias
        ]);
    }

    // --- ACTUALIZAR (BASE64) ---
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria_id = $request->categoria_id;

        if ($request->hasFile('imagen')) {
            // Repetimos el proceso de conversión si suben imagen nueva
            $file = $request->file('imagen');
            $path = $file->getRealPath();
            $imagenData = file_get_contents($path);
            
            $producto->url_imagen = 'data:' . $file->getClientMimeType() . ';base64,' . base64_encode($imagenData);
        }

        $producto->save();

        return redirect()->route('admin.productos.index')
                         ->with('success', '¡Producto actualizado exitosamente!');
    }

    public function destroy(Producto $producto)
    {
        $producto->is_active = false;
        $producto->save();
        return redirect()->route('admin.productos.index');
    }

    public function activate(Producto $producto)
    {
        $producto->is_active = true;
        $producto->save();
        return redirect()->route('admin.productos.index');
    }
}