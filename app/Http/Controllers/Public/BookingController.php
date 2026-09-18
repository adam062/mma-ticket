<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreBookingRequest;
use App\Models\Booking;
use App\Models\PaymentProof;
use App\Models\Setting;
use App\Models\TicketType;
use App\Services\TicketService;
use App\Services\TelegramService;
use App\Notifications\BookingSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    const TOTAL_STEPS = 4;

    public function create()
    {
        return redirect()->route('booking.step', 1);
    }

    public function step(int $step, Request $request)
    {
        $step = max(1, min($step, self::TOTAL_STEPS));

        $ticketTypes = TicketType::where('is_active', true)->orderBy('sort_order')->get();

        $paymentMethods = [
            ['id' => 'vodafone_cash', 'name_en' => 'Vodafone Cash', 'name_ar' => 'فودافون كاش', 'icon' => 'vodafone'],
            ['id' => 'instapay', 'name_en' => 'InstaPay', 'name_ar' => 'إنستاباي', 'icon' => 'instapay'],
        ];

        $data = session('booking_data', []);

        return view('public.booking-step', compact('step', 'ticketTypes', 'paymentMethods', 'data'));
    }

    public function storeStep(Request $request, int $step)
    {
        $step = max(1, min($step, self::TOTAL_STEPS));

        $data = session('booking_data', []);

        if ($step === 1) {
            $validated = $request->validate([
                'full_name' => 'required|string|min:2|max:255',
                'phone_number' => 'required|string|min:10|max:20',
                'email' => 'required|email|max:255',
            ], [
                'full_name.required' => __('The full name field is required.'),
                'full_name.min' => __('The full name must be at least 2 characters.'),
                'phone_number.required' => __('The phone number field is required.'),
                'phone_number.min' => __('The phone number must be at least 10 characters.'),
                'email.required' => __('The email field is required.'),
                'email.email' => __('The email address is not valid.'),
            ]);
            $data = array_merge($data, $validated);
        } elseif ($step === 2) {
            $validated = $request->validate([
                'ticket_type_id' => ['required', Rule::exists('ticket_types', 'id')],
            ], [
                'ticket_type_id.required' => __('The ticket type field is required.'),
                'ticket_type_id.exists' => __('The selected ticket type is invalid.'),
            ]);
            $data = array_merge($data, $validated);
        } elseif ($step === 3) {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:10',
            ], [
                'quantity.required' => __('The quantity field is required.'),
                'quantity.min' => __('Please select at least 1 ticket.'),
                'quantity.max' => __('The maximum is 10 tickets.'),
            ]);
            $data = array_merge($data, $validated);
        } elseif ($step === 4) {
            $validated = $request->validate([
                'payment_method' => ['required', Rule::in(['vodafone_cash', 'instapay'])],
            ], [
                'payment_method.required' => __('The payment method field is required.'),
                'payment_method.in' => __('The selected payment method is invalid.'),
            ]);
            $data = array_merge($data, $validated);
        } elseif ($step === 5) {
            $validated = $request->validate([
                'transfer_phone' => 'required|string|min:5|max:50',
                'screenshot' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            ], [
                'transfer_phone.required' => __('The transfer phone field is required.'),
                'transfer_phone.min' => __('The transfer phone must be at least 5 characters.'),
                'screenshot.required' => __('The transfer screenshot is required.'),
                'screenshot.image' => __('The file must be an image.'),
                'screenshot.mimes' => __('Supported formats: JPG, JPEG, PNG, WEBP.'),
                'screenshot.max' => __('The image may not be greater than 5 MB.'),
            ]);
            $data = array_merge($data, $validated);
        }

        session(['booking_data' => $data]);

        if ($step < self::TOTAL_STEPS) {
            return redirect()->route('booking.step', $step + 1)->with('data', $data);
        }

        return $this->processBooking($request, $data);
    }

    protected function processBooking(Request $request, array $data)
    {
        $ticketType = TicketType::findOrFail($data['ticket_type_id']);

        $unitPrice = app(TicketService::class)->calculateTotal($ticketType->price, 1);
        $totalAmount = app(TicketService::class)->calculateTotal($ticketType->price, $data['quantity']);

        return DB::transaction(function () use ($data, $ticketType, $unitPrice, $totalAmount, $request) {
            $reference = $this->generateReference();

            $booking = Booking::create([
                'reference' => $reference,
                'name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone_number'],
                'transfer_phone' => $data['transfer_phone'],
                'ticket_type_id' => $ticketType->id,
                'quantity' => $data['quantity'],
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'payment_method' => $data['payment_method'],
                'status' => Booking::STATUS_PENDING,
            ]);

            $screenshotPath = $request->file('screenshot')->store('payment-proofs', 'local');

            PaymentProof::create([
                'booking_id' => $booking->id,
                'payment_method' => $data['payment_method'],
                'transfer_phone' => $data['transfer_phone'],
                'amount' => $totalAmount,
                'screenshot_path' => $screenshotPath,
                'submitted_at' => now(),
            ]);

            $telegramService = app(TelegramService::class);
            if ($telegramService->isConfigured()) {
                $token = bin2hex(random_bytes(32));
                \App\Models\TelegramLink::create([
                    'booking_id' => $booking->id,
                    'token' => $token,
                    'status' => 'pending',
                    'expires_at' => now()->addHours(48),
                ]);
            }

            Notification::route('mail', $data['email'])
                ->notify(new BookingSubmittedNotification($reference, $booking->getStatusLabel()));

            Session::forget('booking_data');

            return redirect()->route('booking.show', $reference)
                ->with('success', __('Your booking request has been submitted.'));
        });
    }

    public function prevStep(Request $request, int $step)
    {
        $step = max(1, min($step, self::TOTAL_STEPS));

        if ($step > 1) {
            return redirect()->route('booking.step', $step - 1);
        }

        return redirect()->route('booking.step', 1);
    }

    public function show(string $reference)
    {
        $booking = Booking::where('reference', $reference)
            ->with(['ticketType', 'paymentProofs' => fn ($q) => $q->latest()->limit(1), 'telegramLink'])
            ->firstOrFail();

        return view('public.booking-confirmation', compact('booking'));
    }

    protected function generateReference(): string
    {
        $year = now()->year;
        do {
            $number = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $reference = "MMA-BK-{$year}-{$number}";
            $exists = Booking::where('reference', $reference)->exists();
        } while ($exists);

        return $reference;
    }
}
