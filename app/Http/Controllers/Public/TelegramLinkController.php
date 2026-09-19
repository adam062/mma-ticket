<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TelegramLink;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class TelegramLinkController extends Controller
{
    public function show(Booking $booking)
    {
        if (! $booking->telegramLink()->where('status', 'pending')->exists()) {
            return redirect()->route('booking.show', $booking->reference)
                ->with('info', __('No pending Telegram linking.'));
        }

        $link = $booking->telegramLink()->where('status', 'pending')->latest()->first();
        $service = app(TelegramService::class);

        return view('public.telegram-link', compact('booking', 'link', 'service'));
    }

    public function store(Request $request, Booking $booking, TelegramService $service)
    {
        $link = TelegramLink::where('booking_id', $booking->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $request->validate([
            'telegram_name' => 'nullable|string|max:255',
        ]);

        if ($service->isConfigured()) {
            return redirect()->away($service->getStartLink($link->token));
        }

        return back()->with('error', __('Telegram is not configured by the administrator yet.'));
    }
}
