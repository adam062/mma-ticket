@extends('layouts.admin')

@push('scripts')
<script>
function setWebhook() {
    document.getElementById('webhook-form').submit();
}
</script>
@endpush

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-500">{{ __('Telegram Settings') }}</h1>

    <form method="POST" action="{{ route('admin.settings.telegram.update') }}" class="bg-black rounded-xl shadow-lg p-6 space-y-6">
        @csrf @method('PUT')

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800 text-sm">{{ __('Configure your Telegram Bot credentials below.') }}</p>
            <p class="text-blue-700 text-sm mt-1">{{ __('Get your bot token from @BotFather on Telegram.') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Bot Token') }}</label>
                <input type="text" name="bot_token" value="{{ old('bot_token', $settings['bot_token'] ?? '') }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg font-mono" placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz">
                @error('bot_token') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Bot Username') }}</label>
                <input type="text" name="bot_username" value="{{ old('bot_username', $settings['bot_username'] ?? '') }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg" placeholder="mma_championship_bot">
            </div>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="enabled" value="1" id="enabled" class="mr-2" {{ ($settings['enabled'] ?? false) ? 'checked' : '' }}>
            <label for="enabled" class="text-sm font-medium">{{ __('Enable Telegram Delivery') }}</label>
        </div>

        @if (isset($webhookInfo))
            <div class="bg-black rounded-lg p-4">
                <p class="text-sm text-gray-500">{{ __('Webhook URL') }}: <span class="font-mono">{{ \App\Models\Setting::get('telegram', 'bot_username') }}</span></p>
                <p class="text-sm text-gray-500 mt-2">{{ __('Webhook Status') }}: {{ $webhookInfo['result']['url'] ?? 'Not set' ?: 'Not set' }}</p>
            </div>
        @endif

        <div class="flex gap-4">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-bold">{{ __('Save Settings') }}</button>
            @if ($service->isConfigured())
                <button type="button" onclick="setWebhook()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold">Set Webhook</button>
            @endif
        </div>
    </form>

    <form id="webhook-form" method="POST" action="{{ route('admin.telegram.set-webhook') }}">
        @csrf
    </form>

    <div class="bg-black rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-500 mb-4">{{ __('Telegram Bot Setup') }}</h2>
        <p class="text-gray-500 mb-4">{{ __('After saving your bot token, click "Set Webhook" to enable Telegram bot interactions.') }}</p>
        <p class="text-sm text-gray-500">{{ __('The webhook allows the bot to receive /start commands from users for account linking.') }}</p>
    </div>
</div>
@endsection
