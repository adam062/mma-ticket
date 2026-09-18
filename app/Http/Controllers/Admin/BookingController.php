<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveBookingRequest;
use App\Http\Requests\Admin\DenyBookingRequest;
use App\Http\Requests\Admin\ResubmissionRequest;
use App\Models\AdminReview;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\PaymentProof;
use App\Models\Ticket;
use App\Models\TicketDelivery;
use App\Models\TicketType;
use App\Services\TicketService;
use App\Services\TicketPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('ticketType');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['ticketType', 'paymentProofs' => fn ($q) => $q->latest(), 'tickets', 'adminReviews']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Request $request, Booking $booking, ApproveBookingRequest $formRequest)
    {
        $validated = $formRequest->validated();

        $admin = $request->user();
        $bookingService = app(TicketService::class);

        $result = $bookingService->approveBooking($booking, $admin, $validated);

        if ($result['success']) {
            AuditLog::log('approve_payment', $booking, $validated['admin_note'] ?? null);

            return redirect()->route('admin.bookings.show', $booking)
                ->with('success', __('Booking approved. Tickets have been generated.'));
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('error', $result['error']);
    }

    public function deny(DenyBookingRequest $formRequest, Booking $booking)
    {
        $validated = $formRequest->validated();
        $admin = $formRequest->user();

        DB::transaction(function () use ($booking, $validated) {
            $booking->update([
                'status' => Booking::STATUS_DENIED,
                'approved_at' => now(),
                'approved_by' => $admin->id,
                'denial_reason' => $validated['reason'] . ' ' . ($validated['note'] ?? ''),
            ]);

            AdminReview::create([
                'booking_id' => $booking->id,
                'admin_id' => $admin->id,
                'action' => 'deny',
                'note' => $validated['reason'] . ': ' . ($validated['note'] ?? ''),
                'payment_confirmed' => false,
            ]);

            AuditLog::log('deny_payment', $booking, $validated['note'] ?? null);
        });

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', __('Booking denied.'));
    }

    public function requestResubmission(ResubmissionRequest $formRequest, Booking $booking)
    {
        $validated = $formRequest->validated();
        $admin = $formRequest->user();

        DB::transaction(function () use ($booking, $validated) {
            $booking->update([
                'status' => Booking::STATUS_RESUBMISSION_REQUIRED,
            ]);

            AdminReview::create([
                'booking_id' => $booking->id,
                'admin_id' => $admin->id,
                'action' => 'resubmission',
                'note' => $validated['reason'] . ': ' . ($validated['note'] ?? ''),
                'payment_confirmed' => false,
            ]);

            AuditLog::log('request_resubmission', $booking, $validated['note'] ?? null);
        });

        // Notify customer
        \Illuminate\Support\Facades\Notification::route('mail', $booking->email)
            ->notify(new \App\Notifications\BookingStatusChangedNotification(
                $booking->reference,
                'Resubmission Required',
                $validated['note'] ?? null
            ));

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', __('Resubmission requested. Customer will be notified.'));
    }
}
