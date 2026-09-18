<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_payments' => Booking::where('status', 'pending')->count(),
            'approved_bookings' => Booking::where('status', 'approved')->count(),
            'denied_bookings' => Booking::where('status', 'denied')->count(),
            'total_tickets' => Ticket::count(),
            'used_tickets' => Ticket::where('status', 'used')->count(),
            'unused_tickets' => Ticket::where('status', 'active')->count(),
            'expected_revenue' => Booking::where('status', 'approved')->sum('total_amount'),
            'verified_revenue' => Booking::where('status', 'approved')->sum('total_amount'),
        ];

        $recentBookings = Booking::with('ticketType')->orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}
