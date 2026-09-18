<!DOCTYPE html>
<html lang="{{ $locale ?? 'en' }}" {{ ($locale ?? 'en') === 'ar' ? 'dir="rtl"' : '' }}>
<head>
    <meta charset="UTF-8">
    <title>{{ $ticket->serial }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        body { font-family: 'Roboto', sans-serif; margin: 0; padding: 20px; }
        .ticket { border: 4px solid #dc2626; border-radius: 12px; padding: 30px; }
        .ticket-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .logo { height: 60px; }
        .qr-code { text-align: center; }
        .qr-code img { width: 150px; height: 150px; }
        .badge { background: #dc2626; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .arabic { font-family: 'Amiri', serif; }
        table { width: 100%; margin-top: 20px; }
        td { padding: 8px 0; }
        .border-top { border-top: 1px solid #e5e7eb; }
        .text-red-600 { color: #dc2626; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <div>
                <h1 style="font-size: 28px; font-weight: bold; color: #1f2937;">{{ $eventNameEn }}</h1>
                <p style="color: #6b7280; font-size: 16px;">{{ $eventDate }} • {{ $eventTime }}</p>
                <p style="color: #6b7280; font-size: 14px;">{{ $eventLocationEn }}</p>
            </div>
            <div>
                <span class="badge">{{ strtoupper($ticket->ticketType->name_en) }}</span>
                <div style="margin-top: 10px; font-size: 12px; color: #6b7280;">{{ $ticket->created_at->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="qr-code">
            {!! $qrImage !!}
        </div>

        <table>
            <tr>
                <td><strong>{{ __('Customer Name') }}</strong></td>
                <td>{{ $booking->name }}</td>
                <td><strong>{{ __('Ticket Type') }}</strong></td>
                <td>{{ $ticket->ticketType->name_en }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('Ticket Serial') }}</strong></td>
                <td><span class="font-bold">{{ $ticket->serial }}</span></td>
                <td><strong>{{ __('Ticket Status') }}</strong></td>
                <td>{{ $ticket->getStatusLabel() }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('Event') }}</strong></td>
                <td>{{ $eventNameEn }}</td>
                <td><strong>{{ __('Date') }}</strong></td>
                <td>{{ $eventDate }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('Location') }}</strong></td>
                <td>{{ $eventLocationEn }}</td>
                <td><strong>{{ __('Time') }}</strong></td>
                <td>{{ $eventTime }}</td>
            </tr>
        </table>

        <div class="border-top" style="margin-top: 20px; padding-top: 15px; text-center;">
            <p style="font-size: 12px; color: #9ca3af;">{{ __('Entry Instructions') }}: {{ __('Please present this QR code at the entrance.') }}</p>
            <p style="font-size: 10px; color: #9ca3af; margin-top: 8px;">{{ $ticket->qr_token }}</p>
        </div>
    </div>
</body>
</html>
