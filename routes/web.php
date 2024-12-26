<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth:client']], function () {
    Route::get('/home', [ClientController::class, 'home'])->name('home');
    Route::get('/my_orders', [ClientController::class, 'orderHistory'])->name('my_orders');
    Route::get('/checkout', [ClientController::class, 'viewCart'])->name('checkout');
    Route::get('/product/{id}', [ProductController::class, 'view'])->name('view');
    Route::post('/add-product-to-cart/{product}', [ClientController::class, 'addToCart'])->name('addToCart');
    Route::delete('/remove-product-of-cart/{name}', [ClientController::class, 'removeFromCart'])->name('removeFromCart');
    Route::get('/create_order', [ClientController::class, 'placeOrder'])->name('create_order');
    Route::get('/order_detail/{reference}', [OrderController::class, 'viewOrder'])->name('order_detail');
});
Route::group(['middleware' => ['auth:vendor']], function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [VendorController::class, 'viewOrders'])->name('orders');
    Route::post('/create_product', [ProductController::class, 'store'])->name('create_product');
    Route::put('/update_product/{id}', [ProductController::class, 'update'])->name('update_product');
    Route::delete('/delete_product/{id}', [ProductController::class, 'destroy'])->name('delete_product');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
