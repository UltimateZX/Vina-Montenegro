<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario; // Importamos el modelo Usuario
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importamos Auth para la protección

class UserController extends Controller
{
    /**
     * Muestra la lista de todos los usuarios.
     */
    public function index(Request $request)
    {
        // Empezamos la consulta a la base de datos
        $query = Usuario::query();
        

        // 1. LÓGICA DE BÚSQUEDA (Ya la teníamos)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre_completo', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // 2. ¡NUEVO! LÓGICA DE FILTRO POR ROL
        if ($request->filled('role')) {
            $query->where('rol', $request->input('role'));
        }

        // 3. ¡NUEVO! LÓGICA DE ORDENAMIENTO
        // Valores por defecto: ordenar por ID, descendente (los más nuevos primero)
        $sort_by = $request->input('sort_by', 'id');
        $order = $request->input('order', 'desc');

        // Pequeña validación para seguridad
        if (!in_array($sort_by, ['id', 'nombre_completo', 'email', 'rol'])) {
            $sort_by = 'id';
        }
        
        $query->orderBy($sort_by, $order);

        // 4. LÓGICA DE PAGINACIÓN (Ya la teníamos)
        $usuarios = $query->simplePaginate(10); // Trae 10 por página

        // 5. Enviamos todo a la vista
        return view('admin.usuarios.index', [
            'usuarios' => $usuarios,
            'sort_by' => $sort_by, // Para saber qué columna está ordenada
            'order' => $order      // Para saber el orden (asc/desc)
        ]);
    }
    /**
     * Cambia el rol de un usuario (de admin a cliente o viceversa).
     */
    public function toggleRole(Usuario $usuario)
    {
        // ¡PROTECCIÓN! No dejes que el admin cambie su propio rol
        if ($usuario->id === Auth::id()) {
            return back()->with('error', '¡No puedes cambiar tu propio rol de administrador!');
        }

        // Lógica de cambio
        if ($usuario->rol == 'admin') {
            $usuario->rol = 'cliente';
        } else {
            $usuario->rol = 'admin';
        }
        
        $usuario->save(); // Guardamos el cambio en la BD

        return back()->with('success', '¡Rol del usuario actualizado exitosamente!');
    }

    /**
     * Borra un usuario.
     */
public function destroy(Usuario $usuario)
{
    // ¡PROTECCIÓN! No dejes que el admin se borre a sí mismo
    if ($usuario->id === Auth::id()) {
        return back()->with('error', '¡No puedes eliminar tu propia cuenta de administrador!');
    }

    // --- Lógica de borrado (Importante) ---
    // Antes de borrar el usuario, ¿qué hacemos con sus pedidos?
    // Opción 1 (Recomendada): Reasignarlos a un usuario "fantasma".
    // Opción 2 (Simple): Simplemente los borramos.
    // Opción 3 (Riesgosa): Borrar el usuario fallará si tiene pedidos (por la foreign key).

    // Por ahora, vamos a la opción simple (Borrado en Cascada)
    // Para que esto funcione, debemos definirlo en la MIGRACIÓN.
    // Por ahora, solo borraremos el usuario.

    // (Nota: Si esto da un error de Foreign Key, 
    // es porque el usuario tiene pedidos y la BD lo protege.
    // Lo ideal sería "anonimizar" el usuario, pero por ahora...)

    $usuario->delete(); // Borramos el usuario

    return back()->with('success', '¡Usuario eliminado exitosamente!');
}

    public function show(Usuario $usuario)
{
    // Cargamos la relación "pedidos" que está en el modelo Usuario
    // y los ordenamos por fecha
    $pedidos = $usuario->pedidos()
                       ->orderBy('fecha_pedido', 'desc')
                       ->get();

    return view('admin.usuarios.show', [
        'usuario' => $usuario,
        'pedidos' => $pedidos
    ]);
}
}