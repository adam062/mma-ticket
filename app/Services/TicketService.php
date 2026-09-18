<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function generateSerial(): string
    {
        $prefix = Setting::cached('tickets', 'serial_prefix', 'MMA-2026');
        $year = now()->year;

        do {
            $number = sprintf('%06d', mt_rand(1, 999999));
            $serial = "{$prefix}-{$number}";
            $exists = \App\Models\Ticket::where('serial', $serial)->exists();
        } while ($exists);

        return $serial;
    }

    public function generateQrToken(): string
    {
        do {
            $token = bin2hex(random_bytes(32));
            $exists = \App\Models\Ticket::where('qr_token', $token)->exists();
        } while ($exists);

        return $token;
    }

    public function getTicketTypes(): \Illuminate\Database\Eloquent\Collection
    {
        return TicketType::where('is_active', true)->orderBy('sort_order')->get();
    }

    public function calculateTotal(int $unitPrice, int $quantity): int
    {
        return $unitPrice * $quantity;
    }

    public function checkAvailability(TicketType $type, int $quantity): bool
    {
        $sold = $type->tickets()
            ->join('bookings', 'tickets.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'approved')
            ->whereIn('tickets.status', ['active', 'used'])
            ->count();

        return ($sold + $quantity) <= $type->capacity;
    }

    public function approveBooking(Booking $booking, User $admin, array $validated): array
    {
        return DB::transaction(function () use ($booking, $admin, $validated) {
            $locked = Booking::where('id', $booking->id)
                ->where('status', Booking::STATUS_PENDING)
                ->lockForUpdate()
                ->first();

            if (! $locked) {
                return ['success' => false, 'error' => __('This booking has already been processed.')];
            }

            $ticketType = $locked->ticketType;

            if (! $this->checkAvailability($ticketType, $locked->quantity)) {
                return ['success' => false, 'error' => __('Insufficient ticket capacity for this type.')];
            }

            $recalculatedTotal = $this->calculateTotal($ticketType->price, $locked->quantity);
            $locked->unit_price = $ticketType->price;
            $locked->total_amount = $recalculatedTotal;

            \App\Models\AdminReview::create([
                'booking_id' => $locked->id,
                'admin_id' => $admin->id,
                'action' => 'approve',
                'note' => $validated['admin_note'] ?? null,
                'payment_confirmed' => true,
            ]);

            $tickets = [];
            for ($i = 0; $i < $locked->quantity; $i++) {
                $serial = $this->generateSerial();
                $qrToken = $this->generateQrToken();

                $tickets[] = Ticket::create([
                    'booking_id' => $locked->id,
                    'ticket_type_id' => $ticketType->id,
                    'serial' => $serial,
                    'qr_token' => $qrToken,
                    'status' => Ticket::STATUS_ACTIVE,
                ]);
            }

            $locked->update([
                'status' => Booking::STATUS_APPROVED,
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);

            foreach ($tickets as $ticket) {
                \App\Jobs\SendTicketByEmail::dispatch($ticket);
                \App\Jobs\SendTicketByTelegram::dispatch($ticket);
            }

            return ['success' => true, 'tickets' => $tickets];
        });
    }
}
