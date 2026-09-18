<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\TicketDelivery;
use App\Services\TelegramService;
use App\Services\TicketPdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendTicketByEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Ticket $ticket
    ) {}

    public function handle(): void
    {
        try {
            $booking = $this->ticket->booking;
            $path = app(TicketPdfService::class)->generate($this->ticket);

            \Mail::to($booking->email, $booking->name)->send(new \App\Mail\TicketMail($this->ticket, Storage::path($path)));

            TicketDelivery::record($this->ticket->id, 'email', 'sent');
        } catch (\Throwable $e) {
            TicketDelivery::record($this->ticket->id, 'email', 'failed', $e->getMessage());
            throw $e;
        }
    }
}
