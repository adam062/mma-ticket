@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12">
    <div class="max-w-md w-full bg-black rounded-xl shadow-lg p-8 text-center">
        <h1 class="text-2xl font-bold text-gray-500 mb-6">{{ __('Login') }}</h1>
        <form method="POST" action="{{ request()->route()->named('login.admin') || request()->route()->named('login.admin.post') ? route('login.admin.post') : route('login.gate.post') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-700 rounded-lg focus:ring-2 focus:ring-red-500" autofocus>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Password') }}</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-700 rounded-lg focus:ring-2 focus:ring-red-500">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-center">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-700 rounded">
                    <label for="remember" class="ml-2 text-sm">{{ __('Remember me') }}</label>
                </div>
            </div>
            <button type="submit" class="w-full mt-6 font-bold text-white py-2 px-4 rounded-lg transition">{{ __('Login') }}</button>
        </form>
    </div>
</div>
@endsection
