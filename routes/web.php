<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminTableController;
use App\Http\Controllers\Admin\AdminReportController;

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

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    // Protected Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // Menus
        Route::get('/menus', [AdminMenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/create', [AdminMenuController::class, 'create'])->name('menus.create');
        Route::post('/menus', [AdminMenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{id}', [AdminMenuController::class, 'show'])->name('menus.show');
        Route::get('/menus/{id}/edit', [AdminMenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{id}', [AdminMenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{id}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');
        
        // Tables
        Route::get('/tables', [AdminTableController::class, 'index'])->name('tables.index');
        Route::get('/tables/create', [AdminTableController::class, 'create'])->name('tables.create');
        Route::post('/tables', [AdminTableController::class, 'store'])->name('tables.store');
        Route::get('/tables/{id}', [AdminTableController::class, 'show'])->name('tables.show');
        Route::get('/tables/{id}/edit', [AdminTableController::class, 'edit'])->name('tables.edit');
        Route::put('/tables/{id}', [AdminTableController::class, 'update'])->name('tables.update');
        Route::delete('/tables/{id}', [AdminTableController::class, 'destroy'])->name('tables.destroy');
        Route::post('/tables/{id}/regenerate-qr', [AdminTableController::class, 'regenerateQrCode'])->name('tables.regenerate-qr');
        
        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    });
});
