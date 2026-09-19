@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12">
    <div class="max-w-md w-full bg-black rounded-xl shadow-lg p-8">
        @if (request()->route()->named('login.admin') || request()->route()->named('login.admin.post'))
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-3xl">👑</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-500">{{ __('Admin Login') }}</h1>
                <p class="text-sm text-gray-500 mt-2">{{ __('Access the admin dashboard') }}</p>
            </div>
        @elseif (request()->route()->named('login.gate') || request()->route()->named('login.gate.post'))
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-3xl">🎫</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-500">{{ __('Gate Login') }}</h1>
                <p class="text-sm text-gray-500 mt-2">{{ __('Access the gate scanner dashboard') }}</p>
            </div>
        @else
            <h1 class="text-2xl font-bold text-gray-500 text-center mb-6">{{ __('Login') }}</h1>
        @endif

        <form method="POST" action="{{ request()->route()->named('login.admin') || request()->route()->named('login.admin.post') ? route('login.admin.post') : route('login.gate.post') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">{{ __('Email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-700 rounded-lg bg-black/50 text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">{{ __('Password') }}</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-700 rounded-lg bg-black/50 text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-700 rounded bg-black/50">
                        <label for="remember" class="ml-2 text-sm text-gray-400">{{ __('Remember me') }}</label>
                    </div>
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-red-500">{{ __('← Back to selection') }}</a>
                </div>
            </div>
            <button type="submit" class="w-full mt-6 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition">{{ __('Login') }}</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
    inputs.forEach(function(input) {
        if (input.value) {
            input.classList.add('border-red-500');
        }
    });
});
</script>
@endpush
