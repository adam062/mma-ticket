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
use Illuminate\Support\Facades\Storage;

class SendTicketByTelegram implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Ticket $ticket
    ) {}

    public function handle(): void
    {
        try {
            $booking = $this->ticket->booking;
            $telegramLink = $booking->telegramLink()->where('status', 'confirmed')->latest()->first();

            if (! $telegramLink || ! $telegramLink->chat_id) {
                TicketDelivery::record($this->ticket->id, 'telegram', 'failed', __('No Telegram chat linked.'));
                return;
            }

            $path = app(TicketPdfService::class)->generate($this->ticket);
            $fullPath = Storage::path($path);

            $service = app(TelegramService::class);

            $locale = app()->getLocale();
            $message = $this->buildMessage($locale, $this->ticket);

            $result = $service->sendMessage($telegramLink->chat_id, $message);

            if (! $result || isset($result['ok']) && $result['ok'] === false) {
                TicketDelivery::record($this->ticket->id, 'telegram', 'failed', json_encode($result ?? 'No response'));
                $service->sendDocument($telegramLink->chat_id, $fullPath, $message);
                return;
            }

            $docResult = $service->sendDocument($telegramLink->chat_id, $fullPath, $message);

            if (! $docResult || (isset($docResult['ok']) && $docResult['ok'] === false)) {
                TicketDelivery::record($this->ticket->id, 'telegram', 'failed', json_encode($docResult ?? 'No response'));
            } else {
                TicketDelivery::record($this->ticket->id, 'telegram', 'sent');
            }
        } catch (\Throwable $e) {
            TicketDelivery::record($this->ticket->id, 'telegram', 'failed', $e->getMessage());
            throw $e;
        }
    }

    private function buildMessage(string $locale, Ticket $ticket): string
    {
        app()->setLocale($locale);

        if ($locale === 'ar') {
            return "🎯 بطولة MMA\n\nتمت الموافقة على دفعتك. 🎟️\n\nالتذكرة:\n{$ticket->serial}\n\nتم إرفاق ملف PDF في هذه الرسالة. يرجى عرض رمز الاستجابة السريعة عند الدخول. 🙏";
        }

        return "🎯 MMA CHAMPIONSHIP\n\nYour payment has been approved. 🎟️\n\nTicket:\n{$ticket->serial}\n\nYour ticket PDF is attached. Please show the QR code at the entrance. 🙏";
    }
}
