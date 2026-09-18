<footer class="bg-black text-gray-500 py-8 mt-16">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::cached('event', 'name_en', 'MMA Championship') }}. {{ __('All rights reserved.') }}</p>
            <div class="flex space-x-4">
                @if (app()->getLocale() === 'ar')
                    <a href="{{ route('locale.switch', 'en') }}" class="text-sm px-3 py-1 bg-black text-gray-500 rounded hover:bg-gray-900">EN</a>
                @else
                    <a href="{{ route('locale.switch', 'ar') }}" class="text-sm px-3 py-1 bg-black text-gray-500 rounded hover:bg-gray-900">العربية</a>
                @endif
            </div>
        </div>
    </div>
</footer>
