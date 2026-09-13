<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/order/select-table', [OrderController::class, 'selectTable'])->name('order.select-table');
Route::get('/order/table/{tableNumber}', [OrderController::class, 'orderWithTable'])->name('order.with-table');
Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::get('/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/order/add-to-cart', [OrderController::class, 'addToCart'])->name('order.add-to-cart');
Route::post('/order/update-cart', [OrderController::class, 'updateCart'])->name('order.update-cart');
Route::get('/order/remove-from-cart/{menuId}', [OrderController::class, 'removeFromCart'])->name('order.remove-from-cart');
Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::post('/order/set-table', [OrderController::class, 'setTable'])->name('order.set-table');

Route::get('/payment/{orderId}', [PaymentController::class, 'showPayment'])->name('payment.show');
Route::post('/payment/check-status/{orderId}', [PaymentController::class, 'checkStatus'])->name('payment.check-status');

Route::get('/login', function () {
    return 'Halaman Login Admin (akan dibuat nanti)';
})->name('login');
