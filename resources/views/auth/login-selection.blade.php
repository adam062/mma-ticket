@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12">
    <div class="max-w-md w-full bg-gray-900 rounded-xl shadow-lg p-8 text-center">
        <div class="mb-8">
            <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-5xl">🎟️</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-200">{{ config('app.name', 'MMA Championship') }}</h1>
        </div>

        <p class="text-gray-400 mb-8">{{ __('Select your access type') }}</p>

        <div class="space-y-4">
            <a href="{{ route('login.admin') }}" class="block w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-6 rounded-xl flex flex-col items-center gap-2 transition transform hover:scale-105">
                <span class="text-3xl">👑</span>
                {{ __('Admin Login') }}
            </a>
            <a href="{{ route('login.gate') }}" class="block w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-4 px-6 rounded-xl flex flex-col items-center gap-2 transition transform hover:scale-105">
                <span class="text-3xl">🎫</span>
                {{ __('Gate Login') }}
            </a>
        </div>
    </div>
</div>
@endsection
