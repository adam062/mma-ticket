<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected ?string $botToken;
    protected ?string $botUsername;
    protected bool $enabled;

    public function __construct()
    {
        $this->botToken = Setting::cached('telegram', 'bot_token', env('TELEGRAM_BOT_TOKEN'));
        $this->botUsername = Setting::cached('telegram', 'bot_username', env('TELEGRAM_BOT_USERNAME', 'MMAChampionshipBot'));
        $this->enabled = (bool) Setting::cached('telegram', 'enabled', false);
    }

    public function isConfigured(): bool
    {
        return ! empty($this->botToken) && $this->enabled;
    }

    public function getBotUsername(): string
    {
        return $this->botUsername;
    }

    public function getBotLink(): string
    {
        return "https://t.me/{$this->botUsername}";
    }

    public function getStartLink(string $token): string
    {
        return "https://t.me/{$this->botUsername}?start=" . $token;
    }

    public function sendMessage(int|string $chatId, string $message, ?string $parseMode = 'HTML'): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $payload = [
            'chat_id' => $chatId,
            'text' => $message,
        ];

        if ($parseMode) {
            $payload['parse_mode'] = $parseMode;
        }

        $response = Http::asForm()->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", $payload);

        return $response->json();
    }

    public function sendDocument(int|string $chatId, string $documentPath, string $caption = '', ?string $parseMode = 'HTML'): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $payload = [
            'chat_id' => $chatId,
            'document' => curl_file_create($documentPath),
            'caption' => $caption,
        ];

        if ($parseMode) {
            $payload['parse_mode'] = $parseMode;
        }

        $response = Http::asMultipart()->post("https://api.telegram.org/bot{$this->botToken}/sendDocument", $payload);

        return $response->json();
    }

    public function sendPhoto(int|string $chatId, string $photoPath, string $caption = '', ?string $parseMode = 'HTML'): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $payload = [
            'chat_id' => $chatId,
            'photo' => curl_file_create($photoPath),
            'caption' => $caption,
        ];

        if ($parseMode) {
            $payload['parse_mode'] = $parseMode;
        }

        $response = Http::asMultipart()->post("https://api.telegram.org/bot{$this->botToken}/sendPhoto", $payload);

        return $response->json();
    }

    public function getWebhookUrl(): string
    {
        return route('admin.telegram.webhook');
    }

    public function setWebhook(): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $response = Http::asForm()->post(
            "https://api.telegram.org/bot{$this->botToken}/setWebhook",
            ['url' => $this->getWebhookUrl()]
        );

        return $response->json();
    }

    public function getWebhookInfo(): ?array
    {
        if (! $this->botToken) {
            return null;
        }

        $response = Http::asForm()->post(
            "https://api.telegram.org/bot{$this->botToken}/getWebhookInfo"
        );

        return $response->json();
    }

    public function getUpdates(int $offset = 0): ?array
    {
        if (! $this->botToken) {
            return null;
        }

        $payload = [];
        if ($offset) {
            $payload['offset'] = $offset;
        }

        $response = Http::asForm()->post(
            "https://api.telegram.org/bot{$this->botToken}/getUpdates",
            $payload
        );

        return $response->json();
    }
}
