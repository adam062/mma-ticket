<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Storage;

class TicketApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Ticket $ticket
    ) {}

    public function via($notifiable): array
    {
        return [MailChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your MMA Ticket is Ready'))
            ->greeting(__('Hello :name', ['name' => $notifiable->name ?? $this->ticket->booking->name]))
            ->line(__('Your payment has been approved and your ticket has been generated.'))
            ->line(__('Ticket Serial: :serial', ['serial' => $this->ticket->serial]))
            ->line(__('Event Date: :date', ['date' => \App\Models\Setting::cached('event', 'date')]))
            ->line(__('Please show the QR code at the entrance.'))
            ->attach(
                Storage::path('tickets/' . $this->ticket->qr_token . '.pdf'),
                ['mime' => 'application/pdf']
            )
            ->action(__('View Ticket'), url('/'))
            ->line(__('Thank you for choosing MMA Championship!'));
    }
}
