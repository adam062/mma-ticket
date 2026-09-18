<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function event()
    {
        $settings = $this->getSettings('event');
        return view('admin.settings.event', compact('settings'));
    }

    public function updateEvent(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|string|max:20',
            'location_en' => 'required|string|max:255',
            'location_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ]);

        $this->saveSettings('event', $validated);
        $this->updateLogo($request);

        return back()->with('success', __('Event settings saved.'));
    }

    public function payment()
    {
        $settings = $this->getSettings('payment');
        return view('admin.settings.payment', compact('settings'));
    }

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'vodafone_cash_number' => 'nullable|string|max:20',
            'vodafone_cash_name' => 'nullable|string|max:255',
            'instapay_account' => 'nullable|string|max:255',
            'instapay_name' => 'nullable|string|max:255',
            'instructions_en' => 'nullable|string',
            'instructions_ar' => 'nullable|string',
        ]);

        $this->saveSettings('payment', $validated);

        return back()->with('success', __('Payment settings saved.'));
    }

    public function telegram()
    {
        $settings = $this->getSettings('telegram');
        $service = app(\App\Services\TelegramService::class);
        $webhookInfo = null;
        if ($service->isConfigured()) {
            $webhookInfo = $service->getWebhookInfo();
        }

        return view('admin.settings.telegram', compact('settings', 'webhookInfo'));
    }

    public function updateTelegram(Request $request)
    {
        $validated = $request->validate([
            'bot_token' => 'nullable|string|max:255',
            'bot_username' => 'nullable|string|max:255',
            'enabled' => 'boolean',
        ]);

        $this->saveSettings('telegram', $validated);

        return back()->with('success', __('Telegram settings saved.'));
    }

    protected function getSettings(string $group): array
    {
        $keys = match($group) {
            'event' => ['name_en', 'name_ar', 'date', 'time', 'location_en', 'location_ar', 'description_en', 'description_ar', 'logo'],
            'payment' => ['vodafone_cash_number', 'vodafone_cash_name', 'instapay_account', 'instapay_name', 'instructions_en', 'instructions_ar'],
            'telegram' => ['bot_token', 'bot_username', 'enabled'],
            'tickets' => ['serial_prefix'],
        };

        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = Setting::get($group, $key);
        }

        return $settings;
    }

    protected function saveSettings(string $group, array $data): void
    {
        foreach ($data as $key => $value) {
            $type = is_bool($value) ? 'boolean' : 'string';
            if (is_array($value)) {
                $type = 'array';
                Setting::set($group, $key, $value, $type);
            } elseif (is_bool($value)) {
                Setting::set($group, $key, $value, $type);
            } elseif (is_numeric($value)) {
                $type = 'integer';
                Setting::set($group, $key, $value, $type);
            } else {
                Setting::set($group, $key, $value, $type);
            }
        }

        Setting::clearCache();
    }

    protected function updateLogo(Request $request): void
    {
        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|mimes:png,jpg,jpeg|max:2048']);

            $path = $request->file('logo')->store('event', 'public');
            Setting::set('event', 'logo', $path, 'string');
            Setting::clearCache();
        }
    }
}
