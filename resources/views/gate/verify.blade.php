@extends('layouts.gate')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-500 mb-6">{{ __('Verify Ticket') }}</h1>
    <p class="text-gray-500 mb-8">{{ __('Enter a serial number or scan a QR code to verify a ticket.') }}</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <form method="POST" action="{{ route('gate.verify.serial') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Serial Number') }}</label>
                <input type="text" name="serial" placeholder="MMA-2026-000001" required class="w-full px-4 py-3 border-2 border-gray-700 rounded-xl text-center font-mono text-lg focus:ring-2 focus:ring-red-500">
            </div>
            <button type="submit" class="w-full bg-black hover:bg-black text-white py-3 px-6 rounded-xl font-bold">{{ __('Verify Ticket') }}</button>
        </form>

        <form method="POST" action="{{ route('gate.verify.qr') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('QR Token') }}</label>
                <input type="text" name="qr_token" placeholder="{{ __('Enter QR token') }}" required class="w-full px-4 py-3 border-2 border-gray-700 rounded-xl text-center font-mono text-sm focus:ring-2 focus:ring-red-500">
            </div>
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-6 rounded-xl font-bold">{{ __('Verify Ticket') }}</button>
        </form>
    </div>
</div>
@endsection
