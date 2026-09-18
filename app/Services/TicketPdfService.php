<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Setting;
use App\Models\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketPdfService
{
    public function generate(Ticket $ticket): string
    {
        $booking = $ticket->booking;
        $ticketType = $ticket->ticketType;
        $eventNameEn = Setting::cached('event', 'name_en', 'MMA Championship');
        $eventNameAr = Setting::cached('event', 'name_ar', 'بطولة MMA');
        $eventDate = Setting::cached('event', 'date', '2026-12-15');
        $eventTime = Setting::cached('event', 'time', '20:00');
        $eventLocationEn = Setting::cached('event', 'location_en', 'Cairo, Egypt');
        $eventLocationAr = Setting::cached('event', 'location_ar', 'القاهرة، مصر');

        $verificationUrl = route('gate.lookup', $ticket->serial);
        $qrContent = route('gate.verify.serial', ['serial' => $ticket->serial]);

        $qrImage = QrCode::size(250)->generate($qrContent);

        $pdf = \PDF::setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);

        $locale = app()->getLocale();
        $eventName = $locale === 'ar' ? $eventNameAr : $eventNameEn;
        $eventLocation = $locale === 'ar' ? $eventLocationAr : $eventLocationEn;

        $html = view('tickets.pdf', [
            'ticket' => $ticket,
            'booking' => $booking,
            'ticketType' => $ticketType,
            'eventName' => $eventName,
            'eventNameEn' => $eventNameEn,
            'eventNameAr' => $eventNameAr,
            'eventDate' => $eventDate,
            'eventTime' => $eventTime,
            'eventLocation' => $eventLocation,
            'eventLocationEn' => $eventLocationEn,
            'eventLocationAr' => $eventLocationAr,
            'qrImage' => $qrImage,
            'qrContent' => $qrContent,
            'locale' => $locale,
        ])->render();

        $filename = $ticket->qr_token . '.pdf';
        $path = 'tickets/' . $filename;
        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    public function generateForTelegram(Ticket $ticket): string
    {
        return $this->generate($ticket);
    }
}
