@extends('mail::message')
# {{ __('MMA Championship - Your Ticket') }}

{{ __('Your payment has been approved and your ticket has been generated.') }}

- {{ __('Ticket Serial') }}: **{{ $ticket->serial }}**
- {{ __('Ticket Type') }}: {{ $ticket->ticketType->name_en }}
- {{ __('Event Date') }}: {{ \App\Models\Setting::cached('event', 'date') }}
- {{ __('Status') }}: {{ $ticket->getStatusLabel() }}

{{ __('Please show the QR code at the entrance.') }}

@attachment
{{ __('Ticket PDF') }}
@endattachment

{{ __('Thank you for choosing MMA Championship!') }}
