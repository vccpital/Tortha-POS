<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resource routes
    Route::resource('users', UserController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('products', ProductController::class);
    Route::resource('product-images', ProductImageController::class)->only(['store', 'destroy']);
    Route::resource('orders', OrderController::class);
    Route::resource('order-items', OrderItemController::class);
    // Payment Route
    Route::post('/order/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
    // Callback Route (This is the URL where Safaricom sends the response after payment)
    Route::post('/order/payment/callback', [OrderController::class, 'callback'])->name('orders.payment.callback');
    Route::get('/orders/{order}/pay', [OrderController::class, 'showPaymentForm'])->name('orders.showPaymentForm');
    Route::post('/mpesa/callback', [OrderController::class, 'callback'])->name('stk.callback');

    Route::resource('scans', ScanController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addProduct'])->name('cart.add');
    Route::post('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

require __DIR__.'/auth.php';
