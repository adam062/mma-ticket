@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-500">{{ __('Admin Dashboard') }}</h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-yellow-700">{{ __('Pending Payments') }}</p>
                    <p class="text-3xl font-bold text-yellow-800">{{ $stats['pending_payments'] }}</p>
                </div>
                <div class="text-3xl">⏳</div>
            </div>
        </div>

        <div class="bg-green-50 border-l-4 border-green-500 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-green-700">{{ __('Approved Bookings') }}</p>
                    <p class="text-3xl font-bold text-green-800">{{ $stats['approved_bookings'] }}</p>
                </div>
                <div class="text-3xl">✅</div>
            </div>
        </div>

        <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-red-400">{{ __('Denied Bookings') }}</p>
                    <p class="text-3xl font-bold text-red-800">{{ $stats['denied_bookings'] }}</p>
                </div>
                <div class="text-3xl">❌</div>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-blue-700">{{ __('Total Tickets') }}</p>
                    <p class="text-3xl font-bold text-blue-800">{{ $stats['total_tickets'] }}</p>
                </div>
                <div class="text-3xl">🎫</div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gray-900 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">{{ __('Used Tickets') }}</p>
                    <p class="text-2xl font-bold text-gray-500">{{ $stats['used_tickets'] }}</p>
                </div>
                <div class="text-2xl">✓</div>
            </div>
        </div>

        <div class="bg-gray-900 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">{{ __('Unused Tickets') }}</p>
                    <p class="text-2xl font-bold text-gray-500">{{ $stats['unused_tickets'] }}</p>
                </div>
                <div class="text-2xl">⭕</div>
            </div>
        </div>

        <div class="bg-purple-50 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-purple-700">{{ __('Expected Revenue') }}</p>
                    <p class="text-2xl font-bold text-purple-800">{{ number_format($stats['expected_revenue']) }} EGP</p>
                </div>
                <div class="text-2xl">💰</div>
            </div>
        </div>

        <div class="bg-indigo-50 rounded-xl p-6 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-indigo-700">{{ __('Verified Revenue') }}</p>
                    <p class="text-2xl font-bold text-indigo-800">{{ number_format($stats['verified_revenue']) }} EGP</p>
                </div>
                <div class="text-2xl">💵</div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-black rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-500">{{ __('Recent Bookings') }}</h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-red-600 hover:text-red-800 text-sm font-medium">{{ __('View All →') }}</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="px-4 py-2 text-left text-gray-500 font-medium">{{ __('Reference') }}</th>
                        <th class="px-4 py-2 text-left text-gray-500 font-medium">{{ __('Customer') }}</th>
                        <th class="px-4 py-2 text-left text-gray-500 font-medium">{{ __('Type') }}</th>
                        <th class="px-4 py-2 text-left text-gray-500 font-medium">{{ __('Amount') }}</th>
                        <th class="px-4 py-2 text-left text-gray-500 font-medium">{{ __('Status') }}</th>
                        <th class="px-4 py-2 text-left text-gray-500 font-medium">{{ __('Date') }}</th>
                        <th class="px-4 py-2 text-left text-gray-500 font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach ($recentBookings as $booking)
                    <tr class="hover:bg-gray-900/50 transition-colors">
                        <td class="px-4 py-2 font-mono text-gray-300">{{ $booking->reference }}</td>
                        <td class="px-4 py-2 text-gray-300">{{ $booking->name }}</td>
                        <td class="px-4 py-2 text-gray-300">{{ $booking->ticketType->name_en }}</td>
                        <td class="px-4 py-2 text-gray-300">{{ number_format($booking->total_amount) }} EGP</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $booking->getStatusColorClass() }}">{{ $booking->getStatusLabel() }}</span>
                        </td>
                        <td class="px-4 py-2 text-gray-300">{{ $booking->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-red-600 hover:text-red-800">{{ __('View') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
