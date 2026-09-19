<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" {{ app()->getLocale() === 'ar' ? 'dir="rtl"' : '' }}>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Gate Dashboard') }} | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
    <body class="{{ app()->getLocale() === 'ar' ? 'font-arabic' : '' }} bg-gray-950">
        <header class="bg-gray-950 text-white shadow-sm border-b border-gray-800">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-200">{{ __('Gate Dashboard') }}</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-300">{{ auth()->user()->name }}</span>
                    @if (app()->getLocale() === 'ar')
                        <a href="{{ route('locale.switch', 'en') }}" class="text-sm px-3 py-1 bg-gray-900 text-gray-400 rounded hover:bg-gray-800 hover:text-white">EN</a>
                    @else
                        <a href="{{ route('locale.switch', 'ar') }}" class="text-sm px-3 py-1 bg-gray-900 text-gray-400 rounded hover:bg-gray-800 hover:text-white">العربية</a>
                    @endif
                    <form method="POST" action="{{ route('gate.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-300 hover:text-white">{{ __('Logout') }}</button>
                    </form>
                </div>
            </div>
        </header>
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
