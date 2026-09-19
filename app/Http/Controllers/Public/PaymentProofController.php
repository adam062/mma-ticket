<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentProof;
use App\Notifications\BookingStatusChangedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PaymentProofController extends Controller
{
    public function show(Booking $booking)
    {
        $proof = $booking->paymentProofs()->latest()->firstOrFail();

        return view('public.payment-proof', compact('booking', 'proof'));
    }

    public function edit(Booking $booking)
    {
        if ($booking->status !== Booking::STATUS_RESUBMISSION_REQUIRED) {
            return redirect()->route('booking.show', $booking->reference)
                ->with('error', __('This booking does not require resubmission.'));
        }

        $ticketType = $booking->ticketType;
        $unitPrice = $booking->unit_price;
        $totalAmount = $booking->total_amount;

        $paymentMethods = [
            ['id' => 'vodafone_cash', 'name_en' => 'Vodafone Cash', 'name_ar' => 'فودافون كاش'],
            ['id' => 'instapay', 'name_en' => 'InstaPay', 'name_ar' => 'إنستاباي'],
        ];

        return view('public.payment-proof-edit', compact('booking', 'ticketType', 'unitPrice', 'totalAmount', 'paymentMethods'));
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->status !== Booking::STATUS_RESUBMISSION_REQUIRED) {
            return redirect()->route('booking.show', $booking->reference)
                ->with('error', __('This booking does not require resubmission.'));
        }

        $request->validate([
            'transfer_phone' => 'required|string|min:5|max:50',
            'screenshot' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'transfer_phone.required' => 'رقم الهاتف/الحساب المستخدم للتحويل مطلوب.',
            'screenshot.required' => 'لقطة شاشة للتحويل مطلوبة.',
            'screenshot.image' => 'يجب أن تكون الصورة ملف صورة.',
            'screenshot.mimes' => 'الصيغ المدعومة: JPG, JPEG, PNG, WEBP.',
            'screenshot.max' => 'حجم الصورة لا يجب أن يتجاوز 5 ميجابايت.',
        ]);

        $screenshotPath = $request->file('screenshot')->store('payment-proofs', 'local');

        PaymentProof::create([
            'booking_id' => $booking->id,
            'payment_method' => $booking->payment_method,
            'transfer_phone' => $request->transfer_phone,
            'amount' => $booking->total_amount,
            'screenshot_path' => $screenshotPath,
            'submitted_at' => now(),
        ]);

        $booking->update(['status' => Booking::STATUS_PENDING]);

        Notification::route('mail', $booking->email)
            ->notify(new \App\Notifications\BookingStatusChangedNotification($booking->reference, $booking->getStatusLabel(), 'Your payment proof has been resubmitted and is pending review.'));

        return redirect()->route('booking.show', $booking->reference)
            ->with('success', __('Your payment proof has been resubmitted for review.'));
    }
}
