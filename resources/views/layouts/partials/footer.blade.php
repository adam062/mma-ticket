<footer class="bg-gray-950 text-gray-400 py-8 mt-16 border-t border-gray-800">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::cached('event', 'name_en', 'MMA Championship') }}. {{ __('All rights reserved.') }}</p>
            <div class="flex space-x-4">
                @if (app()->getLocale() === 'ar')
                    <a href="{{ route('locale.switch', 'en') }}" class="text-sm px-3 py-1 bg-gray-900 text-gray-400 rounded hover:bg-gray-800 hover:text-white">EN</a>
                @else
                    <a href="{{ route('locale.switch', 'ar') }}" class="text-sm px-3 py-1 bg-gray-900 text-gray-400 rounded hover:bg-gray-800 hover:text-white">العربية</a>
                @endif
            </div>
        </div>
    </div>
</footer>
