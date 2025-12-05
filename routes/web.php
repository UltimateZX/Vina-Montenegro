<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
        'products' => Product::with('category')->latest()->take(3)->get(),
    ]);
})->name('home');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('dashboard', function () {
        $stats = [
            'totalRevenue' => Payment::where('status', 'succeeded')->sum('amount'),
            'newOrders' => Order::where('created_at', '>=', now()->subMonth())->count(),    
            'newUsers' => User::where('created_at', '>=', now()->subMonth())->count(),
            'activeProducts' => Product::count(),
        ];

        return Inertia::render('dashboard', [
            'stats' => $stats
        ]);
    })->name('dashboard');

    // --- Rutas de menú administrador ---
    Route::get('admin/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('admin/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
});

require __DIR__.'/settings.php';
