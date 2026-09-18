<nav class="bg-black shadow-sm border-b border-gray-800">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            @php
                $logo = \App\Models\Setting::cached('event', 'logo');
            @endphp
            @if ($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="h-10 w-auto">
            @else
                <div class="h-10 w-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">MMA</div>
            @endif
            <span class="text-xl font-bold text-gray-500">{{ \App\Models\Setting::cached('event', 'name_en', 'MMA Championship') }}</span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-red-400 transition">{{ __('Home') }}</a>
            <a href="{{ route('booking.create') }}" class="text-gray-500 hover:text-red-400 transition">{{ __('Book Ticket') }}</a>
            <a href="{{ route('home') }}#faq" class="text-gray-500 hover:text-red-400 transition">{{ __('FAQ') }}</a>
            <div class="flex items-center space-x-2">
                @if (app()->getLocale() === 'ar')
                    <a href="{{ route('locale.switch', 'en') }}" class="text-sm px-3 py-1 bg-gray-900 text-gray-500 rounded hover:bg-gray-800">EN</a>
                @else
                    <a href="{{ route('locale.switch', 'ar') }}" class="text-sm px-3 py-1 bg-gray-900 text-gray-500 rounded hover:bg-gray-800">العربية</a>
                @endif
            </div>
        </div>
    </div>
</nav>
