<nav class="bg-gray-950 shadow-sm border-b border-gray-800">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center relative">
        <div class="flex items-center space-x-4">
            @php
                $logo = \App\Models\Setting::cached('event', 'logo');
            @endphp
            @if ($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="h-10 w-auto">
            @else
                <div class="h-10 w-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">MMA</div>
            @endif
            <span class="text-xl font-bold text-gray-200">{{ \App\Models\Setting::cached('event', 'name_en', 'MMA Championship') }}</span>
        </div>
        <div class="flex items-center space-x-4">
            <button id="mobile-menu-btn" class="sm:hidden text-gray-400 hover:text-white p-2 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div id="desktop-nav" class="hidden sm:flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-red-400 transition">{{ __('Home') }}</a>
                <a href="{{ route('booking.create') }}" class="text-gray-300 hover:text-red-400 transition">{{ __('Book Ticket') }}</a>
                <a href="{{ route('home') }}#faq" class="text-gray-300 hover:text-red-400 transition">{{ __('FAQ') }}</a>
            </div>
            <div id="mobile-nav" class="sm:hidden absolute top-full left-0 right-0 bg-gray-950 border-t border-gray-800 hidden">
                <div class="flex flex-col space-y-2 p-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-red-400 transition py-2">{{ __('Home') }}</a>
                    <a href="{{ route('booking.create') }}" class="text-gray-300 hover:text-red-400 transition py-2">{{ __('Book Ticket') }}</a>
                    <a href="{{ route('home') }}#faq" class="text-gray-300 hover:text-red-400 transition py-2">{{ __('FAQ') }}</a>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if (app()->getLocale() === 'ar')
                    <a href="{{ route('locale.switch', 'en') }}" class="text-sm px-3 py-1 bg-gray-900 text-gray-400 rounded hover:bg-gray-800 hover:text-white">EN</a>
                @else
                    <a href="{{ route('locale.switch', 'ar') }}" class="text-sm px-3 py-1 bg-gray-900 text-gray-400 rounded hover:bg-gray-800 hover:text-white">العربية</a>
                @endif
            </div>
        </div>
    </div>
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
            const mobileNav = document.getElementById('mobile-nav');
            mobileNav?.classList.toggle('hidden');
        });
    </script>
</nav>
