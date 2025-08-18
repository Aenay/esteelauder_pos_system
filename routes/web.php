<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/pos', function () {
    $products = [
        [
            'id' => 1,
            'name' => 'Advanced Night Repair',
            'price' => 75.00,
            'image' => 'https://placehold.co/150x150/fce7f3/4a044e?text=Serum',
        ],
        [
            'id' => 2,
            'name' => 'Double Wear Foundation',
            'price' => 48.00,
            'image' => 'https://placehold.co/150x150/fce7f3/4a044e?text=Foundation',
        ],
    ];
    return view('pos', ['products' => $products]);
});

Route::get('/products', function () {
    $products = [
        [
            'sku' => 'EL-ANR-50',
            'name' => 'Advanced Night Repair',
            'price' => 75.00,
            'stock' => 150,
            'image' => 'https://placehold.co/150x150/fce7f3/4a044e?text=Serum',
        ],
        [
            'sku' => 'EL-DWF-IV',
            'name' => 'Double Wear Foundation',
            'price' => 48.00,
            'stock' => 200,
            'image' => 'https://placehold.co/150x150/fce7f3/4a044e?text=Foundation',
        ],
        [
            'sku' => 'EL-RVS-M',
            'name' => 'Revitalizing Supreme+',
            'price' => 62.00,
            'stock' => 85,
            'image' => 'https://placehold.co/150x150/fce7f3/4a044e?text=Cream',
        ],
    ];
    return view('products.index', ['products' => $products]);
});

Route::get('/orders', function () {
    $orders = [
        [
            'id' => 1054,
            'customer' => 'John Smith',
            'date' => '2025-08-17',
            'amount' => 123.00,
            'status' => 'Completed',
        ],
        [
            'id' => 1053,
            'customer' => 'Emily White',
            'date' => '2025-08-17',
            'amount' => 48.00,
            'status' => 'Completed',
        ],
    ];
    return view('orders.index', ['orders' => $orders]);
});

Route::get('/promotions', function () {
    $promotions = [
        [
            'name' => '10% Off Total Order',
            'type' => 'Percentage',
            'value' => '10%',
            'status' => 'Active',
        ],
        [
            'name' => '$5 Off Total Order',
            'type' => 'Fixed Amount',
            'value' => '$5.00',
            'status' => 'Inactive',
        ],
    ];
    return view('promotions.index', ['promotions' => $promotions]);
});

require __DIR__.'/auth.php';
