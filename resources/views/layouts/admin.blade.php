<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" {{ app()->getLocale() === 'ar' ? 'dir="rtl"' : '' }}>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Admin Dashboard') }} | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="{{ app()->getLocale() === 'ar' ? 'font-arabic' : '' }}">
    <div class="flex h-screen bg-black">
        @include('layouts.partials.admin-sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-black shadow-sm border-b border-gray-800">
                <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                    <h1 class="text-xl font-bold text-gray-500">{{ __('Admin Dashboard') }}</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                        @if (app()->getLocale() === 'ar')
                            <a href="{{ route('locale.switch', 'en') }}" class="text-sm px-3 py-1 bg-black rounded hover:bg-gray-800">EN</a>
                        @else
                            <a href="{{ route('locale.switch', 'ar') }}" class="text-sm px-3 py-1 bg-black rounded text-white hover:bg-gray-900">العربية</a>
                        @endif
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                                <button type="submit" class="text-sm text-gray-400 hover:text-white">{{ __('Logout') }}</button>
                        </form>
                    </div>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto p-4">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
