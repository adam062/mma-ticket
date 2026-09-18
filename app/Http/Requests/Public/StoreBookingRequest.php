<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|min:2|max:255',
            'phone_number' => 'required|string|min:10|max:20',
            'email' => 'required|email|max:255',
            'ticket_type_id' => ['required', Rule::exists('ticket_types', 'id')],
            'quantity' => 'required|integer|min:1|max:10',
            'payment_method' => ['required', Rule::in(['vodafone_cash', 'instapay'])],
            'transfer_phone' => 'required|string|min:5|max:50',
            'screenshot' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => __('The full name field is required.'),
            'full_name.min' => __('The full name must be at least 2 characters.'),
            'phone_number.required' => __('The phone number field is required.'),
            'phone_number.min' => __('The phone number must be at least 10 characters.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('The email address is not valid.'),
            'ticket_type_id.required' => __('The ticket type field is required.'),
            'ticket_type_id.exists' => __('The selected ticket type is invalid.'),
            'quantity.required' => __('The quantity field is required.'),
            'quantity.min' => __('Please select at least 1 ticket.'),
            'quantity.max' => __('The maximum is 10 tickets.'),
            'payment_method.required' => __('The payment method field is required.'),
            'payment_method.in' => __('The selected payment method is invalid.'),
            'transfer_phone.required' => __('The transfer phone field is required.'),
            'transfer_phone.min' => __('The transfer phone must be at least 5 characters.'),
            'screenshot.required' => __('The transfer screenshot is required.'),
            'screenshot.image' => __('The file must be an image.'),
            'screenshot.mimes' => __('Supported formats: JPG, JPEG, PNG, WEBP.'),
            'screenshot.max' => __('The image may not be greater than 5 MB.'),
        ];
    }
}
