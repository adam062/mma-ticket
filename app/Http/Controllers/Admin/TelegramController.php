<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelegramLink;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function setup()
    {
        $service = app(TelegramService::class);
        $webhookInfo = $service->isConfigured() ? $service->getWebhookInfo() : null;

        return view('admin.telegram.setup', compact('webhookInfo'));
    }

    public function setWebhook(Request $request, TelegramService $service)
    {
        if (! $service->isConfigured()) {
            return back()->with('error', __('Telegram bot token is not configured.'));
        }

        $result = $service->setWebhook();

        if (isset($result['ok']) && $result['ok']) {
            return back()->with('success', __('Webhook set successfully.'));
        }

        return back()->with('error', __('Failed to set webhook: ' . ($result['description'] ?? 'Unknown error')));
    }

    public function webhook(Request $request, TelegramService $service)
    {
        $update = $request->all();

        if (isset($update['message']['text']) && isset($update['message']['from']['id'])) {
            $text = $update['message']['text'];
            $chatId = $update['message']['from']['id'];

            if (str_starts_with($text, '/start ')) {
                $token = trim(substr($text, 7));

                $link = TelegramLink::where('token', $token)->first();

                if ($link && $link->status === 'pending' && ! $link->isExpired()) {
                    $link->update([
                        'status' => 'confirmed',
                        'chat_id' => $chatId,
                        'confirmed_at' => now(),
                    ]);

                    $service->sendMessage($chatId, __('✅ Your Telegram account has been linked successfully! Your tickets will be sent here.'));

                    return response('ok', 200);
                }

                if ($link && $link->isExpired()) {
                    $service->sendMessage($chatId, __('❌ This linking token has expired. Please request a new one.'));
                    return response('ok', 200);
                }

                $service->sendMessage($chatId, __('❌ Invalid linking token. Please make sure you are using the correct link.'));
                return response('ok', 200);
            }

            if (trim($text) === '/start') {
                $service->sendMessage($chatId, __("🤖 MMA Championship Bot\n\nTo link your account, click the link from the website.\n\nTo verify a ticket, send the serial number."));
                return response('ok', 200);
            }

            if (preg_match('/^MMA-\d{4}-\d{6}$/', trim($text))) {
                $ticket = \App\Models\Ticket::where('serial', trim($text))->first();

                if ($ticket) {
                    $service->sendMessage($chatId, __("🎫 Ticket: :serial\nStatus: :status\nType: :type\nEvent: :event", [
                        'serial' => $ticket->serial,
                        'status' => $ticket->getStatusLabel(),
                        'type' => $ticket->ticketType->name,
                        'event' => \App\Models\Setting::cached('event', 'name_en', 'MMA Championship'),
                    ]));
                } else {
                    $service->sendMessage($chatId, __('❌ Ticket not found.'));
                }

                return response('ok', 200);
            }
        }

        return response('ok', 200);
    }

    public function checkUpdates(TelegramService $service)
    {
        if (! $service->isConfigured()) {
            return back()->with('error', __('Telegram bot token is not configured.'));
        }

        $result = $service->getUpdates();

        if (isset($result['ok']) && $result['ok']) {
            return back()->with('success', __('Retrieved updates successfully.'));
        }

        return back()->with('error', __('Failed to get updates.'));
    }
}
