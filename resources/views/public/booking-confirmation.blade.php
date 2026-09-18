@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="bg-green-50 border border-green-200 rounded-xl p-8 text-center mb-8">
            <div class="text-5xl mb-4">✅</div>
            <h1 class="text-3xl font-bold text-green-800 mb-2">{{ __('Request Submitted') }}</h1>
            <p class="text-lg text-green-700 mb-4">{{ __('request_submitted_ar') }}</p>
        </div>

        <div class="bg-black rounded-xl shadow-lg p-8 space-y-6">
            <h2 class="text-xl font-bold text-gray-500 border-b pb-3">{{ __('Booking Details') }}</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">{{ __('Booking Reference') }}</p>
                    <p class="font-mono font-bold text-lg">{{ $booking->reference }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $booking->getStatusColorClass() }}">{{ $booking->getStatusLabel() }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Ticket Type') }}</p>
                    <p class="font-bold">{{ $booking->ticketType->name_en }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Quantity') }}</p>
                    <p class="font-bold">{{ $booking->quantity }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Total Amount') }}</p>
                    <p class="font-bold text-red-600 text-xl">{{ number_format($booking->total_amount) }} EGP</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Payment Method') }}</p>
                    <p class="font-bold">{{ __($booking->payment_method) }}</p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
                <p class="text-blue-800 font-medium mb-2">{{ __('Your request is waiting for manual payment verification.') }}</p>
                <p class="text-sm text-blue-700">{{ __('After approval, the ticket PDF will be sent to your email and Telegram.') }}</p>
            </div>

            @if (Setting::cached('telegram', 'enabled'))
                @if ($booking->telegramLink)
                    @if ($booking->telegramLink->status === 'pending')
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mt-6">
                            <h3 class="font-bold text-purple-800 mb-2">{{ __('Connect Telegram') }}</h3>
                            <p class="text-purple-700 mb-4">{{ __('Link your Telegram account to receive tickets directly.') }}</p>
                            <a href="{{ $service->getStartLink($booking->telegramLink->token) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition">{{ __('Link Telegram') }}</a>
                        </div>
                    @elseif ($booking->telegramLink->status === 'confirmed')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mt-6">
                            <p class="text-green-800 font-medium">✅ {{ __('Telegram account linked') }}</p>
                        </div>
                    @endif
                @endif
            @endif

            <div class="text-center pt-6">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-500">{{ __('← Back to Home') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
