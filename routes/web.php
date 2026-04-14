<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminNotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/dang-nhap', [AuthController::class, 'login']);

Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register');
Route::post('/dang-ky', [AuthController::class, 'register']);

Route::get('/quen-mat-khau', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/quen-mat-khau', [AuthController::class, 'forgotPassword'])->name('password.email');

Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/manage', [HomeController::class, 'manage'])->name('manage')->middleware(['auth', 'admin']);

Route::middleware('auth')->group(function () {
    Route::get('/admin-mode/on', function () {
        if (auth()->user()->role === 'admin') {
            session(['admin_mode' => true]);
        }

        return redirect()->route('manage');
    })->name('admin.mode.on');

    Route::get('/admin-mode/off', function () {
        session()->forget('admin_mode');
        return redirect()->route('home');
    })->name('admin.mode.off');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/products/{product}', [ProductController::class, 'detail']);

    Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/them-vao-gio', [CartController::class, 'store'])->name('cart.store');
    Route::get('/api/cart', [CartController::class, 'getCart'])->name('cart.api');
    Route::post('/cart/delete-item', [CartController::class, 'deleteItem']);
    Route::post('/api/cart/update-qty', [CartController::class, 'updateQty']);

    Route::get('/profile', [AuthController::class, 'profileInfo'])->name('profile.info');
    Route::get('/profile/security', [AuthController::class, 'profileSecurity'])->name('profile.security');
    Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('profile.changePassword');

    Route::get('/dat-hang', [OrderController::class, 'index'])->name('checkout');
    Route::post('/dat-hang', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/cam-on', [OrderController::class, 'thankyou'])->name('thankyou');

    Route::get('/lich-su-mua-hang', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/api/orders/{id}', [OrderController::class, 'getDetailApi']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/don-hang/{id}/theo-doi', [OrderController::class, 'track'])->name('orders.track');
    Route::get('/don-hang/{id}/xuat-hoa-don', [OrderController::class, 'exportInvoice'])->name('orders.export');
    Route::post('/orders/{id}/update-process', [OrderController::class, 'updateProcess'])->name('orders.updateProcess')->middleware(['auth']);

    Route::get('/admin/thong-ke', [OrderController::class, 'adminReport'])->name('admin.report');
    Route::get('/admin/notifications', [OrderController::class, 'adminNotifications'])->name('admin.notifications');
    Route::get('/thong-bao', [OrderController::class, 'userNotifications'])->name('user.notifications');
});