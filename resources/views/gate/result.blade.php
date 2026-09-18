@extends('layouts.gate')

@section('content')
<div class="max-w-3xl mx-auto text-center py-12">
    <div class="text-6xl mb-6">
        @if ($status === 'valid') ✅
        @elseif ($status === 'used') ⚠️
        @elseif ($status === 'cancelled') ❌
        @else ❌
        @endif
    </div>
    <h1 class="text-4xl font-bold mb-4 {{ $status === 'valid' ? 'text-green-600' : ($status === 'used' ? 'text-yellow-600' : 'text-red-600') }}">{{ $message }}</h1>
    @if (app()->getLocale() === 'ar')
        <h2 class="text-2xl font-bold mb-8 {{ $status === 'valid' ? 'text-green-600' : ($status === 'used' ? 'text-yellow-600' : 'text-red-600') }}">{{ $message_ar }}</h2>
    @endif

    @if ($ticket && $status === 'valid')
    <div class="bg-black rounded-xl shadow-lg p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-500 mb-6">{{ __('Ticket Details') }}</h2>
        <div class="grid grid-cols-2 gap-4 text-left">
            <div><p class="text-sm text-gray-500">{{ __('Customer Name') }}</p><p class="font-bold">{{ $ticket->booking->name }}</p></div>
            <div><p class="text-sm text-gray-500">{{ __('Ticket Serial') }}</p><p class="font-mono font-bold">{{ $ticket->serial }}</p></div>
            <div><p class="text-sm text-gray-500">{{ __('Ticket Type') }}</p><p class="font-bold">{{ $ticket->ticketType->name_en }}</p></div>
            <div><p class="text-sm text-gray-500">{{ __('Event') }}</p><p class="font-bold">{{ \App\Models\Setting::cached('event', 'name_en', 'MMA Championship') }}</p></div>
            <div><p class="text-sm text-gray-500">{{ __('Status') }}</p><p class="font-bold">{{ $ticket->getStatusLabel() }}</p></div>
        </div>

        <form method="POST" action="{{ route('gate.ticket.use', $ticket) }}" class="mt-8">
            @csrf
            <button type="submit" onclick="return confirm('{{ __('Are you sure you want to allow entry?') }}')" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 px-6 rounded-xl font-bold text-xl transition">
                {{ __('ALLOW ENTRY') }}
            </button>
        </form>
    </div>
    @elseif ($ticket && $status === 'used')
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-8">
        <p class="text-yellow-800 font-bold text-xl">{{ __('ALREADY USED') }}</p>
        <p class="text-yellow-700 mt-2">{{ __('This ticket has already been used for entry.') }}</p>
        <p class="text-yellow-700 text-sm mt-2">{{ __('Used at') }}: {{ $ticket->used_at->format('M d, Y H:i') }}</p>
    </div>
    @elseif ($ticket && $status === 'cancelled')
    <div class="bg-red-50 border border-red-200 rounded-xl p-8">
        <p class="text-red-800 font-bold text-xl">{{ __('CANCELLED TICKET') }}</p>
        <p class="text-red-400 mt-2">{{ __('This ticket has been cancelled.') }}</p>
    </div>
    @else
    <div class="bg-red-50 border border-red-200 rounded-xl p-8">
        <p class="text-red-800 font-bold text-xl">{{ __('INVALID TICKET') }}</p>
        <p class="text-red-400 mt-2">{{ __('No ticket found with this code.') }}</p>
    </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('gate.dashboard') }}" class="text-gray-500 hover:text-gray-500">{{ __('← Back to Dashboard') }}</a>
    </div>
</div>
@endsection
