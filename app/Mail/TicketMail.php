<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public string $pdfPath
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('MMA Championship - Your Ticket'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.ticket',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorage('tickets/' . $this->ticket->qr_token . '.pdf')
                ->type('application/pdf')
                ->name($this->ticket->serial . '.pdf'),
        ];
    }
}
