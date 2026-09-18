@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-500">{{ __('Payment Settings') }}</h1>

    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-xl p-6 mb-6">
        <p class="text-yellow-800 font-medium">{{ __('⚠️ Use placeholder numbers in development. Never use real payment credentials.') }}</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.payment.update') }}" class="bg-black rounded-xl shadow-lg p-6 space-y-6">
        @csrf @method('PUT')

        <h3 class="text-lg font-bold text-gray-500">{{ __('Vodafone Cash') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium mb-1">{{ __('Vodafone Cash Number') }}</label><input type="text" name="vodafone_cash_number" value="{{ $settings['vodafone_cash_number'] }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg font-mono"></div>
            <div><label class="block text-sm font-medium mb-1">{{ __('Account Name') }}</label><input type="text" name="vodafone_cash_name" value="{{ $settings['vodafone_cash_name'] }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></div>
        </div>

        <h3 class="text-lg font-bold text-gray-500 pt-4">{{ __('InstaPay') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium mb-1">{{ __('InstaPay Account') }}</label><input type="text" name="instapay_account" value="{{ $settings['instapay_account'] }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg font-mono"></div>
            <div><label class="block text-sm font-medium mb-1">{{ __('Account Name') }}</label><input type="text" name="instapay_name" value="{{ $settings['instapay_name'] }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></div>
        </div>

        <h3 class="text-lg font-bold text-gray-500 pt-4">{{ __('Payment Instructions (English)') }}</h3>
        <textarea name="instructions_en" rows="5" class="w-full px-3 py-2 border border-gray-700 rounded-lg">{{ $settings['instructions_en'] }}</textarea>

        <h3 class="text-lg font-bold text-gray-500 pt-4">{{ __('Payment Instructions (Arabic)') }}</h3>
        <textarea name="instructions_ar" rows="5" class="w-full px-3 py-2 border border-gray-700 rounded-lg" dir="rtl">{{ $settings['instructions_ar'] }}</textarea>

        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-bold">{{ __('Save Changes') }}</button>
    </form>

    <div class="bg-black rounded-xl shadow-lg p-6 mt-6">
        <h3 class="text-lg font-bold text-gray-500 mb-4">{{ __('Serial Prefix') }}</h3>
        <form method="POST" action="{{ route('admin.ticket-types.index') }}" class="text-sm text-gray-500">
            @csrf
            <p>{{ __('Current prefix') }}: <span class="font-mono font-bold">{{ $settings['serial_prefix'] ?? \App\Models\Setting::get('tickets', 'serial_prefix') }}</span></p>
        </form>
    </div>
</div>
@endsection
