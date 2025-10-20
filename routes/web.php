<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StaffPerformanceController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\BranchController;
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
    Route::resource('promotions', \App\Http\Controllers\PromotionController::class)->only(['index','create','store','edit','update','destroy']);
    Route::patch('/promotions/{promotion}/toggle', [\App\Http\Controllers\PromotionController::class, 'toggle'])->name('promotions.toggle');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Order viewing routes (admin, manager, and sales-assistant): index, show, receipt
Route::middleware(['auth', 'verified', 'role:admin|store-manager|sales-assistant'])->prefix('admin')->name('admin.')->group(function () {
    // Order History - view only
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index')->middleware('permission:orders.view');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('permission:orders.view');
    Route::get('orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt')->middleware('permission:orders.print');
});

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products Management - permission based
    Route::middleware(['permission:view-products'])->group(function () {
        Route::resource('products', ProductController::class);
    });

    // Customer Management - accessible to users with manage-customers permission
    Route::middleware(['permission:manage-customers'])->group(function () {
        Route::resource('customers', CustomerController::class);
    });

    // Supplier Management - permission based
    Route::middleware(['permission:view-suppliers'])->group(function () {
        Route::resource('suppliers', SupplierController::class);
    });

    // Purchase Orders Management - permission based
    Route::middleware(['permission:manage-purchase-orders'])->group(function () {
        Route::resource('purchase-orders', PurchaseOrderController::class);
        Route::post('purchase-orders/{id}/receive', [PurchaseOrderController::class, 'receiveStock'])->name('purchase-orders.receive');
    });

    // Supplier Orders Management - permission based
    Route::middleware(['permission:manage-purchase-orders'])->group(function () {
        Route::resource('supplier-orders', \App\Http\Controllers\Admin\SupplierOrderController::class);
    });

    // Delivery Management - permission based
    Route::middleware(['permission:view-deliveries'])->group(function () {
        Route::resource('deliveries', DeliveryController::class);
        Route::post('deliveries/{delivery}/update-quantities', [DeliveryController::class, 'updateQuantities'])->name('deliveries.update-quantities');
    });

    // Branch Management - permission based
    Route::middleware(['permission:view-branches'])->group(function () {
        Route::resource('branches', BranchController::class);
        Route::get('branches/analytics', [BranchController::class, 'analytics'])->name('branches.analytics');
    });

    // Order Management (edit/delete)
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit')->middleware('permission:orders.edit');
    Route::patch('orders/{order}', [OrderController::class, 'update'])->name('orders.update')->middleware('permission:orders.edit');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy')->middleware('permission:orders.delete');

    // Loyalty Management - permission based
    Route::middleware(['permission:view-loyalty'])->group(function () {
        Route::resource('loyalty', \App\Http\Controllers\Admin\LoyaltyController::class);
        Route::get('loyalty/{loyalty}/analytics', [\App\Http\Controllers\Admin\LoyaltyController::class, 'analytics'])->name('loyalty.analytics');
        Route::post('loyalty/{loyalty}/add-points', [\App\Http\Controllers\Admin\LoyaltyController::class, 'addPoints'])->name('loyalty.add-points');
        Route::post('loyalty/{loyalty}/use-points', [\App\Http\Controllers\Admin\LoyaltyController::class, 'usePoints'])->name('loyalty.use-points');
        Route::post('loyalty/test-calculation', [\App\Http\Controllers\Admin\LoyaltyController::class, 'testCalculation'])->name('loyalty.test-calculation');
    });

    // Staff Performance Management - permission based
    Route::middleware(['permission:view-staff-performance'])->group(function () {
        Route::get('staff-performances/analytics', [StaffPerformanceController::class, 'analytics'])->name('staff-performances.analytics');
        Route::post('staff-performances/regenerate', [StaffPerformanceController::class, 'regenerateFromOrders'])->name('staff-performances.regenerate');
        Route::get('staff-performances/real-time', [StaffPerformanceController::class, 'getRealTimePerformance'])->name('staff-performances.real-time');
        Route::get('staff-performances/trends', [StaffPerformanceController::class, 'getTrends'])->name('staff-performances.trends');
        Route::get('staff-performances/order-trends', [StaffPerformanceController::class, 'getOrderTrends'])->name('staff-performances.order-trends');
        Route::resource('staff-performances', StaffPerformanceController::class);
        Route::get('staff/{staff}/performance', [StaffPerformanceController::class, 'staffPerformance'])->name('staff.performance');
    });

    // Roles & Permissions - permission based
    Route::middleware(['permission:assign-roles'])->group(function () {
        Route::get('roles-permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'index'])->name('roles-permissions.index');
        Route::patch('roles-permissions/{user}', [\App\Http\Controllers\Admin\RolePermissionController::class, 'update'])->name('roles-permissions.update');
    });

    // User Management - permission based
    Route::middleware(['permission:manage-users'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('users/{user}/profile', [UserController::class, 'show'])->name('users.profile');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('users/{user}/password', [UserController::class, 'updatePassword'])->name('users.update-password');
    });
});

// Customer Routes
Route::prefix('customer')->name('customer.')->group(function () {
    // Customer Welcome Page
    Route::get('/', function () {
        return view('customer.welcome');
    })->name('welcome');

    // Customer Authentication Routes
    Route::middleware('guest:customer')->group(function () {
        Route::get('login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'login']);
        Route::get('register', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'register']);
    });

    Route::post('logout', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'logout'])->name('logout');

    // Customer Protected Routes
    Route::middleware('auth:customer')->group(function () {
        // Dashboard
        Route::get('dashboard', [\App\Http\Controllers\CustomerDashboardController::class, 'index'])->name('dashboard');
        
        // Shop and Cart Routes
        Route::get('shop', [\App\Http\Controllers\CustomerCartController::class, 'index'])->name('shop.index');
        Route::get('cart', [\App\Http\Controllers\CustomerCartController::class, 'showCart'])->name('cart.show');
        
        // Cart AJAX Routes
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::post('add', [\App\Http\Controllers\CustomerCartController::class, 'addToCart'])->name('add');
            Route::post('update', [\App\Http\Controllers\CustomerCartController::class, 'updateCart'])->name('update');
            Route::post('remove', [\App\Http\Controllers\CustomerCartController::class, 'removeFromCart'])->name('remove');
            Route::post('clear', [\App\Http\Controllers\CustomerCartController::class, 'clearCart'])->name('clear');
            Route::get('get', [\App\Http\Controllers\CustomerCartController::class, 'getCart'])->name('get');
            Route::post('checkout', [\App\Http\Controllers\CustomerCartController::class, 'checkout'])->name('checkout');
        });
        
        // Order and Profile Routes
        Route::get('orders', [\App\Http\Controllers\CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\CustomerOrderController::class, 'show'])->name('orders.show');
        Route::get('loyalty', [\App\Http\Controllers\CustomerOrderController::class, 'loyalty'])->name('loyalty');
        
        // Profile routes for customers
        Route::get('profile/edit', [\App\Http\Controllers\CustomerProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [\App\Http\Controllers\CustomerProfileController::class, 'update'])->name('profile.update');
    });
});

// Welcome page
Route::get('/', function () {
    return view('auth.login');
});