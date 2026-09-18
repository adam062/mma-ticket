<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $reference,
        public string $status,
        public ?string $note = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject(__('Your Booking Status Has Changed'))
            ->greeting(__('Hello'))
            ->line(__('Booking Reference: :ref', ['ref' => $this->reference]))
            ->line(__('New Status: :status', ['status' => $this->status]));

        if ($this->note) {
            $message->line(__('Admin Note: :note', ['note' => $this->note]));
        }

        return $message->action(__('View Details'), url('/'))
            ->line(__('Thank you for choosing MMA Championship!'));
    }
}
