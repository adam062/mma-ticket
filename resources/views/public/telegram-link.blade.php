@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="bg-black rounded-xl shadow-lg p-8 text-center">
            <div class="text-5xl mb-6">📱</div>
            <h1 class="text-2xl font-bold text-gray-500 mb-4">{{ __('Connect Telegram') }}</h1>
            <p class="text-gray-500 mb-6">{{ __('telegram_link_instructions') }}</p>

            @if ($service->isConfigured())
                <a href="{{ $service->getStartLink($link->token) }}" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg transition mb-6">
                    <span>Open Telegram →</span>
                </a>
                <div class="bg-black rounded-lg p-4 mt-6">
                    <p class="text-sm text-gray-500">{{ __('Or share this link') }}</p>
                    <p class="font-mono text-sm break-all mt-2">{{ $service->getStartLink($link->token) }}</p>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                    <p class="text-red-800 font-medium">{{ __('Telegram is not configured by the administrator yet.') }}</p>
                </div>
            @endif

            <div class="mt-6 text-center">
                <a href="{{ route('booking.show', $booking->reference) }}" class="text-gray-500 hover:text-gray-500">{{ __('← Back to Booking') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
