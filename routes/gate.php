<?php

use Illuminate\Support\Facades\Route;

Route::prefix('gate')->name('gate.')->middleware(['locale', 'web'])->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'gate'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Gate\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/verify', [\App\Http\Controllers\Gate\DashboardController::class, 'verifyForm'])->name('verify');
        Route::post('/verify/qr', [\App\Http\Controllers\Gate\DashboardController::class, 'verifyQr'])->name('verify.qr');
        Route::post('/verify/serial', [\App\Http\Controllers\Gate\DashboardController::class, 'verifySerial'])->name('verify.serial');
        Route::post('/ticket/{ticket}/use', [\App\Http\Controllers\Gate\DashboardController::class, 'markUsed'])->name('ticket.use');
        Route::get('/ticket/lookup/{serial}', [\App\Http\Controllers\Gate\DashboardController::class, 'lookup'])->name('ticket.lookup');
    });
});
