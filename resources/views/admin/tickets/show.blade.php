@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-500">{{ __('Ticket') }}: {{ $ticket->serial }}</h1>
        <a href="{{ route('admin.tickets.index') }}" class="text-gray-500 hover:text-gray-500">{{ __('← Back') }}</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-black rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('Ticket Information') }}</h2>
            <div class="space-y-3">
                <div><p class="text-sm text-gray-500">{{ __('Serial') }}</p><p class="font-mono font-bold">{{ $ticket->serial }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('QR Token') }}</p><p class="font-mono text-xs break-all">{{ $ticket->qr_token }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Ticket Type') }}</p><p class="font-bold">{{ $ticket->ticketType->name_en }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Status') }}</p><span class="px-2 py-1 rounded-full text-xs font-medium {{ $ticket->getStatusColorClass() }}">{{ $ticket->getStatusLabel() }}</span></div>
                <div><p class="text-sm text-gray-500">{{ __('Created At') }}</p><p class="font-bold">{{ $ticket->created_at->format('M d, Y H:i') }}</p></div>
                @if ($ticket->used_at)<div><p class="text-sm text-gray-500">{{ __('Used At') }}</p><p class="font-bold">{{ $ticket->used_at->format('M d, Y H:i') }}</p></div>@endif
                @if ($ticket->usedBy)<div><p class="text-sm text-gray-500">{{ __('Used By') }}</p><p class="font-bold">{{ $ticket->usedBy->name }}</p></div>@endif
            </div>
        </div>

        <div class="bg-black rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('Booking Information') }}</h2>
            <div class="space-y-3">
                <div><p class="text-sm text-gray-500">{{ __('Reference') }}</p><p class="font-mono font-bold">{{ $ticket->booking->reference }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Customer') }}</p><p class="font-bold">{{ $ticket->booking->name }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Email') }}</p><p class="font-bold">{{ $ticket->booking->email }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Phone') }}</p><p class="font-bold">{{ $ticket->booking->phone }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Total Paid') }}</p><p class="font-bold">{{ number_format($ticket->booking->total_amount) }} EGP</p></div>
            </div>

            <div class="mt-6">
                <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('QR Code') }}</h2>
                <div class="bg-black border border-gray-800 rounded-lg p-4 inline-block">
                    {!! $qrImage !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery History -->
    <div class="bg-black rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('Resend Ticket') }}</h2>
        <form method="POST" action="{{ route('admin.tickets.resend', $ticket) }}" class="flex gap-4 items-end">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Channels') }}</label>
                <div class="flex gap-3">
                    <label class="flex items-center"><input type="checkbox" name="channels[]" value="email" class="mr-2"> {{ __('Email') }}</label>
                    <label class="flex items-center"><input type="checkbox" name="channels[]" value="telegram" class="mr-2"> {{ __('Telegram') }}</label>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">{{ __('Resend') }}</button>
        </form>

        @if ($ticket->deliveries->isNotEmpty())
            <div class="mt-6">
                <h3 class="font-bold text-gray-500 mb-2">{{ __('Delivery History') }}</h3>
                <table class="w-full text-sm">
                    <thead class="bg-black">
                        <tr><th class="px-3 py-2 text-left">{{ __('Channel') }}</th><th class="px-3 py-2 text-left">{{ __('Status') }}</th><th class="px-3 py-2 text-left">{{ __('Sent At') }}</th><th class="px-3 py-2 text-left">{{ __('Error') }}</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($ticket->deliveries as $delivery)
                        <tr>
                            <td class="px-3 py-2">{{ $delivery->channel }}</td>
                            <td class="px-3 py-2">{{ $delivery->status }}</td>
                            <td class="px-3 py-2">{{ $delivery->sent_at?->format('M d, Y H:i') ?? '-' }}</td>
                            <td class="px-3 py-2 text-red-500">{{ $delivery->error_message ?? '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
