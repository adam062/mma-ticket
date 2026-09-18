@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-500">{{ __('Telegram Setup') }}</h1>

    @if ($webhookInfo)
        <div class="bg-black rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('Webhook Status') }}</h2>
            <div class="space-y-2 text-sm">
                <p><span class="font-medium">URL:</span> {{ $webhookInfo['result']['url'] ?? 'Not set' ?: 'Not set' }}</p>
                <p><span class="font-medium">Has valid certificate:</span> {{ $webhookInfo['result']['has_valid_certificate'] ? 'Yes' : 'No' }}</p>
                <p><span class="font-medium">Pending updates:</span> {{ $webhookInfo['result']['pending_update_count'] ?? 0 }}</p>
            </div>
            <form method="POST" action="{{ route('admin.telegram.set-webhook') }}" class="mt-4">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold">{{ __('Set Webhook') }}</button>
            </form>
        </div>
    @endif

    <div class="bg-black rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('Test Bot') }}</h2>
        <p class="text-gray-500 mb-4">{{ __('Send a test message to verify the bot is working.') }}</p>
        <form id="test-form" method="POST" action="{{ route('admin.telegram.check') }}" class="flex gap-3">
            @csrf
            <input type="number" name="chat_id" placeholder="Chat ID" class="px-4 py-2 border border-gray-700 rounded-lg flex-1">
            <input type="text" name="message" placeholder="Test message" class="px-4 py-2 border border-gray-700 rounded-lg flex-1">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-bold">{{ __('Send') }}</button>
        </form>
    </div>
</div>
@endsection
