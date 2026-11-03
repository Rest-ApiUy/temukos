<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

// Public & Landing
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/quick-login', [AuthController::class, 'quickLogin'])->name('quick-login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Customer & General Actions
Route::middleware('auth')->group(function () {
    // Order Flow
    Route::get('/order/cari', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/{order:order_code}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/order/{order:order_code}/pay', [OrderController::class, 'simulatePayment'])->name('order.pay');
    Route::post('/order/{order:order_code}/chat', [OrderController::class, 'sendMessage'])->name('order.chat');
    Route::post('/order/{order:order_code}/invoice/{invoice:invoice_code}/approve', [OrderController::class, 'approveInvoice'])->name('order.invoice.approve');
    Route::post('/order/{order:order_code}/invoice/{invoice:invoice_code}/pay', [OrderController::class, 'payInvoice'])->name('order.invoice.pay');

    // Customer Account
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');

    // Staff Internal Panel
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::get('/order/{order:order_code}', [StaffController::class, 'show'])->name('show');
        Route::post('/order/{order:order_code}/status', [StaffController::class, 'updateStatus'])->name('status');
        Route::post('/order/{order:order_code}/invoice', [StaffController::class, 'createInvoice'])->name('invoice');
    });
});

