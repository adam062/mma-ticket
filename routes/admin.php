<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['locale', 'web'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Ticket types
        Route::get('/ticket-types', [\App\Http\Controllers\Admin\TicketTypeController::class, 'index'])->name('ticket-types.index');
        Route::get('/ticket-types/create', [\App\Http\Controllers\Admin\TicketTypeController::class, 'create'])->name('ticket-types.create');
        Route::post('/ticket-types', [\App\Http\Controllers\Admin\TicketTypeController::class, 'store'])->name('ticket-types.store');
        Route::get('/ticket-types/{type}/edit', [\App\Http\Controllers\Admin\TicketTypeController::class, 'edit'])->name('ticket-types.edit');
        Route::put('/ticket-types/{type}', [\App\Http\Controllers\Admin\TicketTypeController::class, 'update'])->name('ticket-types.update');

        // Settings
        Route::get('/settings/event', [\App\Http\Controllers\Admin\SettingController::class, 'event'])->name('settings.event');
        Route::put('/settings/event', [\App\Http\Controllers\Admin\SettingController::class, 'updateEvent'])->name('settings.event.update');
        Route::get('/settings/payment', [\App\Http\Controllers\Admin\SettingController::class, 'payment'])->name('settings.payment');
        Route::put('/settings/payment', [\App\Http\Controllers\Admin\SettingController::class, 'updatePayment'])->name('settings.payment.update');
        Route::get('/settings/telegram', [\App\Http\Controllers\Admin\SettingController::class, 'telegram'])->name('settings.telegram');
        Route::put('/settings/telegram', [\App\Http\Controllers\Admin\SettingController::class, 'updateTelegram'])->name('settings.telegram.update');

        // Bookings
        Route::get('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/approve', [\App\Http\Controllers\Admin\BookingController::class, 'approve'])->name('bookings.approve');
        Route::post('/bookings/{booking}/deny', [\App\Http\Controllers\Admin\BookingController::class, 'deny'])->name('bookings.deny');
        Route::post('/bookings/{booking}/request-resubmission', [\App\Http\Controllers\Admin\BookingController::class, 'requestResubmission'])->name('bookings.request-resubmission');

        // Tickets
        Route::get('/tickets', [\App\Http\Controllers\Admin\TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [\App\Http\Controllers\Admin\TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/resend', [\App\Http\Controllers\Admin\TicketController::class, 'resend'])->name('tickets.resend');

        // Audit log
        Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');

        // Telegram setup
        Route::get('/telegram/setup', [\App\Http\Controllers\Admin\TelegramController::class, 'setup'])->name('telegram.setup');
        Route::post('/telegram/set-webhook', [\App\Http\Controllers\Admin\TelegramController::class, 'setWebhook'])->name('telegram.set-webhook');
        Route::post('/telegram/webhook', [\App\Http\Controllers\Admin\TelegramController::class, 'webhook'])->name('telegram.webhook');
        Route::get('/telegram/check', [\App\Http\Controllers\Admin\TelegramController::class, 'checkUpdates'])->name('telegram.check');

        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});
