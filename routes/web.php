<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::middleware('locale')->group(function () {
    Route::get('/', [\App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');
    Route::get('/booking', [\App\Http\Controllers\Public\BookingController::class, 'create'])->name('booking.create');
    Route::get('/booking/step/{step}', [\App\Http\Controllers\Public\BookingController::class, 'step'])->name('booking.step');
    Route::post('/booking/step/{step}', [\App\Http\Controllers\Public\BookingController::class, 'storeStep'])->name('booking.step.store');
    Route::get('/booking/step/{step}/prev', [\App\Http\Controllers\Public\BookingController::class, 'prevStep'])->name('booking.step.prev');
    Route::post('/booking', [\App\Http\Controllers\Public\BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{reference}', [\App\Http\Controllers\Public\BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/{booking}/payment-proof', [\App\Http\Controllers\Public\PaymentProofController::class, 'show'])->name('payment-proof.show');
    Route::get('/booking/{booking}/payment-proof/edit', [\App\Http\Controllers\Public\PaymentProofController::class, 'edit'])->name('payment-proof.edit');
    Route::put('/booking/{booking}/payment-proof', [\App\Http\Controllers\Public\PaymentProofController::class, 'update'])->name('payment-proof.update');
    Route::get('/booking/{booking}/telegram-link', [\App\Http\Controllers\Public\TelegramLinkController::class, 'show'])->name('telegram-link.show');
    Route::post('/booking/{booking}/telegram-link', [\App\Http\Controllers\Public\TelegramLinkController::class, 'store'])->name('telegram-link.store');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    // Auth (admin / gate man)
    Route::get('/login/admin', [LoginController::class, 'showLoginForm'])->name('login.admin');
    Route::post('/login/admin', [LoginController::class, 'login'])->name('login.admin.post');
    Route::get('/login/gate', [LoginController::class, 'showLoginForm'])->name('login.gate');
    Route::post('/login/gate', [LoginController::class, 'login'])->name('login.gate.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

require __DIR__ . '/admin.php';
require __DIR__ . '/gate.php';
