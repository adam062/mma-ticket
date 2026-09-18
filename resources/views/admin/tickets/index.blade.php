@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-500">{{ __('Tickets') }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-black rounded-xl p-4 text-center">
            <p class="text-sm text-gray-500">{{ __('Total') }}</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-green-50 rounded-xl p-4 text-center">
            <p class="text-sm text-green-600">{{ __('Active') }}</p>
            <p class="text-2xl font-bold">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-gray-200 rounded-xl p-4 text-center">
            <p class="text-sm text-gray-500">{{ __('Used') }}</p>
            <p class="text-2xl font-bold">{{ $stats['used'] }}</p>
        </div>
        <div class="bg-red-50 rounded-xl p-4 text-center">
            <p class="text-sm text-red-600">{{ __('Cancelled') }}</p>
            <p class="text-2xl font-bold">{{ $stats['cancelled'] }}</p>
        </div>
    </div>

    <div class="bg-black rounded-xl shadow-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-black">
                <tr>
                    <th class="px-4 py-3 text-left">{{ __('Serial') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Customer') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Type') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Booking') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Created At') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Used At') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Used By') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($tickets as $ticket)
                <tr>
                    <td class="px-4 py-3 font-mono">{{ $ticket->serial }}</td>
                    <td class="px-4 py-3">{{ $ticket->booking->name }}</td>
                    <td class="px-4 py-3">{{ $ticket->ticketType->name_en }}</td>
                    <td class="px-4 py-3 font-mono">{{ $ticket->booking->reference }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $ticket->getStatusColorClass() }}">{{ $ticket->getStatusLabel() }}</span>
                    </td>
                    <td class="px-4 py-3">{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-4 py-3">{{ $ticket->used_at?->format('M d, Y H:i') ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $ticket->usedBy?->name ?? '-' }}</td>
                    <td class="px-4 py-3"><a href="{{ route('admin.tickets.show', $ticket) }}" class="text-red-600 hover:text-red-800">{{ __('View') }}</a></td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-4 py-8 text-center text-gray-500">{{ __('No tickets found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tickets->links() }}</div>
</div>
@endsection
