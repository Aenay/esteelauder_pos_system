<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
require __DIR__.'/auth.php';

// Main POS Interface
Route::middleware(['auth'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/complete-sale', [PosController::class, 'completeSale'])->name('pos.complete_sale');

    // AJAX Endpoints for POS
    Route::prefix('ajax')->name('ajax.')->group(function () {
        Route::get('products/search', [PosController::class, 'searchProducts'])->name('products.search');
        Route::get('customers/search', [PosController::class, 'searchCustomers'])->name('customers.search');
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [PosController::class, 'getCart'])->name('get');
            Route::post('add', [PosController::class, 'addToCart'])->name('add');
            Route::post('update', [PosController::class, 'updateCart'])->name('update');
            Route::post('remove', [PosController::class, 'removeFromCart'])->name('remove');
            Route::post('clear', [PosController::class, 'clearCart'])->name('clear');
            Route::post('checkout', [PosController::class, 'checkout'])->name('checkout');
        });
    });

    // Promotions Route
    Route::get('/promotions', function () {
        return view('promotions.index');
    })->name('promotions.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin|mananger'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::resource('products', ProductController::class);

    // Customer Management
    Route::resource('customers', CustomerController::class);

    // Supplier Management
    Route::resource('suppliers', SupplierController::class);

    // Purchase Orders Management
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::post('purchase-orders/{id}/receive', [PurchaseOrderController::class, 'receiveStock'])->name('purchase-orders.receive');

    // Order History
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // User Management
    Route::resource('users', UserController::class);
    Route::get('users/{user}/profile', [UserController::class, 'show'])->name('users.profile');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('users/{user}/password', [UserController::class, 'updatePassword'])->name('users.update-password');
});

// Welcome page
Route::get('/', function () {
    return view('auth.login');
});

// Dashboard for authenticated users
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');