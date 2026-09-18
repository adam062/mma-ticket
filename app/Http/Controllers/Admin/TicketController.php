<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['booking', 'ticketType']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Ticket::count(),
            'active' => Ticket::where('status', 'active')->count(),
            'used' => Ticket::where('status', 'used')->count(),
            'cancelled' => Ticket::where('status', 'cancelled')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'stats'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['booking', 'ticketType', 'deliveries']);

        $qrImage = QrCode::size(200)->generate($ticket->qr_token);

        return view('admin.tickets.show', compact('ticket', 'qrImage'));
    }

    public function resend(Request $request, Ticket $ticket)
    {
        $request->validate([
            'channels' => 'required|array|in:email,telegram',
        ]);

        $channels = $request->input('channels');

        if (in_array('email', $channels)) {
            \App\Jobs\SendTicketByEmail::dispatch($ticket);
        }

        if (in_array('telegram', $channels)) {
            \App\Jobs\SendTicketByTelegram::dispatch($ticket);
        }

        \App\Models\AuditLog::log('resend_ticket', $ticket, 'Resent via: ' . implode(', ', $channels));

        return back()->with('success', __('Ticket resend queued.'));
    }
}
