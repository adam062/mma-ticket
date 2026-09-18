@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12">
    <div class="max-w-md w-full bg-black rounded-xl shadow-lg p-8 text-center">
        <h1 class="text-2xl font-bold text-gray-500 mb-6">{{ __('Login') }}</h1>
        <p class="text-gray-500 mb-8">{{ __('Select your access type') }}</p>

        <div class="space-y-4">
            <a href="{{ route('login.admin') }}" class="block w-full font-bold text-white py-3 px-6 rounded-lg transition">
                {{ __('Admin Login') }}
            </a>
            <a href="{{ route('login.gate') }}" class="block w-full bg-black hover:bg-black text-white font-bold py-3 px-6 rounded-lg transition">
                {{ __('Gate Login') }}
            </a>
        </div>
    </div>
</div>
@endsection
