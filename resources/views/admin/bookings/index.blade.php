@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-500">{{ __('Bookings') }}</h1>
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" placeholder="{{ __('Search...') }}" value="{{ request('search') }}" class="px-4 py-2 border border-gray-700 rounded-lg">
            <select name="status" class="px-4 py-2 border border-gray-700 rounded-lg">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>{{ __('Denied') }}</option>
                <option value="resubmission_required" {{ request('status') == 'resubmission_required' ? 'selected' : '' }}>{{ __('Resubmission Required') }}</option>
            </select>
            <button class="px-4 py-2 bg-red-600 text-white rounded-lg">{{ __('Filter') }}</button>
        </form>
    </div>

    <div class="bg-black rounded-xl shadow-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-black">
                <tr>
                    <th class="px-4 py-3 text-left">{{ __('Reference') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Customer') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Type') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('Qty') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('Amount') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Payment') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Date') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($bookings as $booking)
                <tr class="{{ $booking->status === 'pending' ? 'bg-yellow-50' : '' }}">
                    <td class="px-4 py-3 font-mono">{{ $booking->reference }}</td>
                    <td class="px-4 py-3">{{ $booking->name }}<br><span class="text-xs text-gray-500">{{ $booking->phone }}</span></td>
                    <td class="px-4 py-3">{{ $booking->ticketType->name_en }}</td>
                    <td class="px-4 py-3 text-right">{{ $booking->quantity }}</td>
                    <td class="px-4 py-3 text-right">{{ number_format($booking->total_amount) }} EGP</td>
                    <td class="px-4 py-3">{{ __($booking->payment_method) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $booking->getStatusColorClass() }}">{{ $booking->getStatusLabel() }}</span>
                    </td>
                    <td class="px-4 py-3">{{ $booking->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-red-600 hover:text-red-800">{{ __('View') }}</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-4 py-8 text-center text-gray-500">{{ __('No bookings found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
