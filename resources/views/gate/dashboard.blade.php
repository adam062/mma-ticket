@extends('layouts.gate')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="text-5xl mb-3">📱</div>
        <h1 class="text-3xl font-bold text-gray-500 mb-2">{{ __('Gate Dashboard') }}</h1>
        <p class="text-gray-500">{{ __('Scan a QR code or enter a serial number below to verify tickets.') }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gray-900 rounded-xl p-4 text-center">
            <p class="text-sm text-gray-500">{{ __('Total') }}</p>
            <p class="text-2xl font-bold text-gray-300">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-green-900/20 border border-green-500/30 rounded-xl p-4 text-center">
            <p class="text-sm text-green-400">{{ __('Active') }}</p>
            <p class="text-2xl font-bold text-green-400">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-xl p-4 text-center">
            <p class="text-sm text-yellow-400">{{ __('Used') }}</p>
            <p class="text-2xl font-bold text-yellow-400">{{ $stats['used'] }}</p>
        </div>
        <div class="bg-red-900/20 border border-red-500/30 rounded-xl p-4 text-center">
            <p class="text-sm text-red-400">{{ __('Cancelled') }}</p>
            <p class="text-2xl font-bold text-red-400">{{ $stats['cancelled'] }}</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <button onclick="openScanner()" class="bg-red-500 hover:bg-red-600 text-white py-6 px-6 rounded-xl font-bold text-xl flex flex-col items-center gap-3 transition transform hover:scale-105">
            <span class="text-3xl">📷</span>
            {{ __('Scan QR Code') }}
        </button>
        <a href="{{ route('gate.verify') }}" class="bg-gray-800 hover:bg-gray-700 text-white py-6 px-6 rounded-xl font-bold text-xl flex flex-col items-center gap-3 transition transform hover:scale-105">
            <span class="text-3xl">🔢</span>
            {{ __('Enter Serial Manually') }}
        </a>
    </div>

    @include('gate.partials.scanner')
</div>
@endsection
