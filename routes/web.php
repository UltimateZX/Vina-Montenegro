<?php

use App\Http\Controllers\Admin\ProductCrudController;
use Illuminate\Support\Facades\Route;
// Controladores de Breeze
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Rutas de la Tienda
|--------------------------------------------------------------------------
*/

// La página de inicio ('/') ES nuestro catálogo de productos
Route::get('/', [ProductController::class, 'index'])->name('home');

// Grupo de rutas para el Carrito
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrito/aumentar', [CartController::class, 'increase'])->name('cart.increase');
Route::post('/carrito/reducir', [CartController::class, 'decrease'])->name('cart.decrease');
Route::post('/carrito/eliminar', [CartController::class, 'remove'])->name('cart.remove');


/*
|--------------------------------------------------------------------------
| Rutas de Autenticación (Breeze)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect(route('home'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ruta para mostrar el formulario de dirección (checkout)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
});

// Esto carga las rutas de login, registro, logout, etc.
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Rutas del Panel de Administrador
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Ruta del Dashboard (ahora usa el layout)
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // <-- Lo crearemos en el Paso 4
    })->name('dashboard');

    // NUEVO: Rutas del CRUD de Productos
    // Esto crea automáticamente:
    // admin.productos.index (GET /admin/productos)
    // admin.productos.create (GET /admin/productos/crear)
    // admin.productos.store (POST /admin/productos)
    // admin.productos.edit (GET /admin/productos/{id}/editar)
    // admin.productos.update (PUT/PATCH /admin/productos/{id})
    // admin.productos.destroy (DELETE /admin/productos/{id})
    Route::resource('productos', ProductCrudController::class);

    // Muestra el formulario de dirección
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');

    // ¡NUEVA RUTA! Recibe los datos del formulario y crea el pedido
    Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.placeOrder');

    Route::get('/pago/{pedido}', [PaymentController::class, 'index'])->name('payment.index');

});