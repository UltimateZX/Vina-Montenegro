<?php

// Controladores del Admin
use App\Http\Controllers\Admin\DashboardController; // <-- ¡Añadido!
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentValidationController;
use App\Http\Controllers\Admin\ProductCrudController;
// Controladores de Breeze
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
// Controladores de la Tienda
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
// Adicionales
use Inertia\Inertia;
use App\Models\Producto;
use App\Models\Categoria;

/*
|--------------------------------------------------------------------------
| Rutas de la Tienda (Públicas)
|--------------------------------------------------------------------------
*/

// ANTES:
// Route::get('/', [WelcomeController::class, 'index'])->name('home');

// AHORA:
Route::get('/', function () {
    return Inertia::render('welcome', [
        'productos' => Producto::where('is_active', true)->get(),
        'categorias' => Categoria::all(),
    ]);
})->name('home');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update'); // Asumiendo que tienes un método update
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');


/*
|--------------------------------------------------------------------------
| Rutas de Autenticación (Clientes y Admin logueados)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect(route('home'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.placeOrder');

    Route::get('/pago/{pedido}', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/pago/{pedido}', [PaymentController::class, 'storeVoucher'])->name('payment.store');
});

Route::get('/pago/exito', function() {
    return view('payment.success');
})->name('payment.success');

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Rutas del Panel de Administrador (SOLO ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('productos', ProductCrudController::class);
    Route::post('/productos/{producto}/activate', [ProductCrudController::class, 'activate'])->name('productos.activate');

    // Validación de Pagos
    Route::get('/pagos', [PaymentValidationController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/{pedido}', [PaymentValidationController::class, 'show'])->name('pagos.show');
    Route::post('/pagos/{pedido}/approve', [PaymentValidationController::class, 'approve'])->name('pagos.approve');
    Route::post('/pagos/{pedido}/reject', [PaymentValidationController::class, 'reject'])->name('pagos.reject');

    // Historial de Pedidos
    Route::get('/pedidos', [OrderController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedido}', [OrderController::class, 'show'])->name('pedidos.show');
    Route::delete('/pedidos/{pedido}', [OrderController::class, 'destroy'])->name('pedidos.destroy');
    Route::post('/pedidos/{pedido}/complete', [OrderController::class, 'complete'])->name('pedidos.complete');

    // Gestión de Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{usuario}', [UserController::class, 'show'])->name('usuarios.show');
    Route::post('/usuarios/{usuario}/toggle-role', [UserController::class, 'toggleRole'])->name('usuarios.toggleRole');
    Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');

});
