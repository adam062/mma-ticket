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
    <div class="flex h-screen bg-gray-950">
        <!-- Mobile sidebar overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 md:hidden hidden"></div>

        <!-- Sidebar -->
        <div id="admin-sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 text-gray-300 flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out" style="min-height: 100vh;">
            @include('layouts.partials.admin-sidebar')
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-gray-950 shadow-sm border-b border-gray-800">
                <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <button id="mobile-menu-btn" class="md:hidden text-gray-400 hover:text-white p-2 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-xl font-bold text-gray-200">{{ __('Admin Dashboard') }}</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-300">{{ auth()->user()->name }}</span>
                        @if (app()->getLocale() === 'ar')
                            <a href="{{ route('locale.switch', 'en') }}" class="text-sm px-3 py-1 bg-gray-900 text-gray-400 rounded hover:bg-gray-800 hover:text-white">EN</a>
                        @else
                            <a href="{{ route('locale.switch', 'ar') }}" class="text-sm px-3 py-1 bg-gray-900 text-gray-400 rounded hover:bg-gray-800 hover:text-white">العربية</a>
                        @endif
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-300 hover:text-white px-3 py-1 rounded-lg hover:bg-gray-800 transition">{{ __('Logout') }}</button>
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
    <script>
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const menuBtn = document.getElementById('mobile-menu-btn');
        menuBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });
        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>
</body>
</html>
