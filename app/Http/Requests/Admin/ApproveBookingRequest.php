<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ApproveBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'admin_note' => 'nullable|string|max:1000',
            'payment_verified' => 'accepted',
            'booking_reference' => 'required|string|max:50',
            'total_amount' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_verified.accepted' => 'You must confirm that the payment has been received.',
            'booking_reference.required' => 'Booking reference is required.',
            'total_amount.required' => 'Total amount is required.',
        ];
    }
}
