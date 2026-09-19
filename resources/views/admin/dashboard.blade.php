@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-200">{{ __('Admin Dashboard') }}</h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-yellow-400">{{ __('Pending Payments') }}</p>
                    <p class="text-3xl font-bold text-yellow-300">{{ $stats['pending_payments'] }}</p>
                </div>
                <div class="text-3xl">⏳</div>
            </div>
        </div>

        <div class="bg-green-900/20 border border-green-500/30 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-green-400">{{ __('Approved Bookings') }}</p>
                    <p class="text-3xl font-bold text-green-300">{{ $stats['approved_bookings'] }}</p>
                </div>
                <div class="text-3xl">✅</div>
            </div>
        </div>

        <div class="bg-red-900/20 border border-red-500/30 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-red-400">{{ __('Denied Bookings') }}</p>
                    <p class="text-3xl font-bold text-red-300">{{ $stats['denied_bookings'] }}</p>
                </div>
                <div class="text-3xl">❌</div>
            </div>
        </div>

        <div class="bg-blue-900/20 border border-blue-500/30 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-blue-400">{{ __('Total Tickets') }}</p>
                    <p class="text-3xl font-bold text-blue-300">{{ $stats['total_tickets'] }}</p>
                </div>
                <div class="text-3xl">🎫</div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-400">{{ __('Used Tickets') }}</p>
                    <p class="text-2xl font-bold text-gray-300">{{ $stats['used_tickets'] }}</p>
                </div>
                <div class="text-2xl text-gray-500">✓</div>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-400">{{ __('Unused Tickets') }}</p>
                    <p class="text-2xl font-bold text-gray-300">{{ $stats['unused_tickets'] }}</p>
                </div>
                <div class="text-2xl text-gray-500">⭕</div>
            </div>
        </div>

        <div class="bg-purple-900/20 border border-purple-500/30 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-purple-400">{{ __('Expected Revenue') }}</p>
                    <p class="text-2xl font-bold text-purple-300">{{ number_format($stats['expected_revenue']) }} EGP</p>
                </div>
                <div class="text-2xl">💰</div>
            </div>
        </div>

        <div class="bg-indigo-900/20 border border-indigo-500/30 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-indigo-400">{{ __('Verified Revenue') }}</p>
                    <p class="text-2xl font-bold text-indigo-300">{{ number_format($stats['verified_revenue']) }} EGP</p>
                </div>
                <div class="text-2xl">💵</div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-200">{{ __('Recent Bookings') }}</h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-red-500 hover:text-red-400 text-sm font-medium">{{ __('View All →') }}</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="px-4 py-2 text-left text-gray-400 font-medium">{{ __('Reference') }}</th>
                        <th class="px-4 py-2 text-left text-gray-400 font-medium">{{ __('Customer') }}</th>
                        <th class="px-4 py-2 text-left text-gray-400 font-medium">{{ __('Type') }}</th>
                        <th class="px-4 py-2 text-left text-gray-400 font-medium">{{ __('Amount') }}</th>
                        <th class="px-4 py-2 text-left text-gray-400 font-medium">{{ __('Status') }}</th>
                        <th class="px-4 py-2 text-left text-gray-400 font-medium">{{ __('Date') }}</th>
                        <th class="px-4 py-2 text-left text-gray-400 font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach ($recentBookings as $booking)
                    <tr class="hover:bg-gray-800/50 transition-colors">
                        <td class="px-4 py-2 font-mono text-gray-300">{{ $booking->reference }}</td>
                        <td class="px-4 py-2 text-gray-300">{{ $booking->name }}</td>
                        <td class="px-4 py-2 text-gray-300">{{ $booking->ticketType->name_en }}</td>
                        <td class="px-4 py-2 text-gray-300">{{ number_format($booking->total_amount) }} EGP</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $booking->getStatusColorClass() }}">{{ $booking->getStatusLabel() }}</span>
                        </td>
                        <td class="px-4 py-2 text-gray-300">{{ $booking->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-red-500 hover:text-red-400">{{ __('View') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
