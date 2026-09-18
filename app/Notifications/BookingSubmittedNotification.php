<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $reference,
        public string $status
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your Booking Has Been Received'))
            ->greeting(__('Hello'))
            ->line(__('Thank you for your booking.'))
            ->line(__('Booking Reference: :ref', ['ref' => $this->reference]))
            ->line(__('Status: :status', ['status' => $this->status]))
            ->line(__('Your request is waiting for manual payment verification.'))
            ->action(__('View Booking'), url('/'))
            ->line(__('Thank you for choosing MMA Championship!'));
    }
}
