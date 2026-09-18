<aside class="w-64 bg-black text-gray-500 flex flex-col">
    <div class="p-4 border-b border-gray-800">
        <span class="text-xl font-bold text-white">{{ __('Admin') }}</span>
    </div>
    <nav class="flex-1 py-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-500 hover:bg-black hover:text-white transition">
            <i class="mr-3">📊</i> {{ __('Dashboard') }}
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-4 py-2 text-gray-500 hover:bg-black hover:text-white transition">
            <i class="mr-3">🎫</i> {{ __('Bookings') }}
        </a>
        <a href="{{ route('admin.tickets.index') }}" class="flex items-center px-4 py-2 text-gray-500 hover:bg-black hover:text-white transition">
            <i class="mr-3">🎟️</i> {{ __('Tickets') }}
        </a>
        <a href="{{ route('admin.ticket-types.index') }}" class="flex items-center px-4 py-2 text-gray-500 hover:bg-black hover:text-white transition">
            <i class="mr-3">💎</i> {{ __('Ticket Types') }}
        </a>
        <a href="{{ route('admin.settings.event') }}" class="flex items-center px-4 py-2 text-gray-500 hover:bg-black hover:text-white transition">
            <i class="mr-3">⚙️</i> {{ __('Settings') }}
        </a>
        <a href="{{ route('admin.telegram.setup') }}" class="flex items-center px-4 py-2 text-gray-500 hover:bg-black hover:text-white transition">
            <i class="mr-3">📱</i> {{ __('Telegram') }}
        </a>
        <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center px-4 py-2 text-gray-500 hover:bg-black hover:text-white transition">
            <i class="mr-3">📝</i> {{ __('Audit Log') }}
        </a>
    </nav>
</aside>
