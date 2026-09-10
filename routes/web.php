<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::post('/order/add-to-cart', [OrderController::class, 'addToCart'])->name('order.add-to-cart');
Route::post('/order/update-cart', [OrderController::class, 'updateCart'])->name('order.update-cart');
Route::get('/order/remove-from-cart/{menuId}', [OrderController::class, 'removeFromCart'])->name('order.remove-from-cart');
Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::post('/order/set-table', [OrderController::class, 'setTable'])->name('order.set-table');
