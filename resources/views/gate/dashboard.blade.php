@extends('layouts.gate')

@section('content')
<div class="text-center py-12">
    <div class="text-6xl mb-6">📱</div>
    <h1 class="text-3xl font-bold text-gray-500 mb-4">{{ __('Gate Dashboard') }}</h1>
    <p class="text-gray-500 mb-8 max-w-md mx-auto">{{ __('Scan a QR code or enter a serial number below to verify tickets.') }}</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
        <button onclick="openScanner()" class="bg-red-500 hover:bg-red-600 text-white py-6 px-6 rounded-xl font-bold text-xl flex flex-col items-center gap-3 transition transform hover:scale-105">
            <span class="text-3xl">📷</span>
            {{ __('Scan QR') }}
        </button>
        <a href="{{ route('gate.verify') }}" class="bg-black hover:bg-black text-white py-6 px-6 rounded-xl font-bold text-xl flex flex-col items-center gap-3 transition transform hover:scale-105">
            <span class="text-3xl">🔢</span>
            {{ __('Enter Serial') }}
        </a>
    </div>

    @include('gate.partials.scanner')
</div>
@endsection
