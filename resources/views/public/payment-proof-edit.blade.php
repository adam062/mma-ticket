@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <span class="text-sm font-medium text-gray-500">{{ __('Step 1') }} — {{ __('Ticket Details') }}</span>
                <span class="text-sm font-medium text-gray-500">{{ __('Step 2') }} — {{ __('Payment') }}</span>
                <span class="text-sm font-medium text-gray-500">{{ __('Step 3') }} — {{ __('Upload Proof') }}</span>
                <span class="text-sm font-medium text-gray-500">{{ __('Step 4') }} — {{ __('Verification') }}</span>
                <span class="text-sm font-medium text-gray-500">{{ __('Step 5') }} — {{ __('Ticket') }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-red-600 h-2 rounded-full" style="width: 60%"></div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <h3 class="font-bold text-yellow-800 mb-2">{{ __('Resubmission Required') }}</h3>
            <p class="text-yellow-700 text-sm">
                @foreach ($booking->adminReviews as $review)
                    @if ($review->action === 'resubmission')
                        {{ $review->note }}
                    @endif
                @endforeach
            </p>
        </div>

        <div class="bg-black rounded-xl shadow-lg p-8 mb-8">
            <h1 class="text-2xl font-bold text-gray-500 mb-6">{{ __('Booking') }}: {{ $booking->reference }}</h1>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500">{{ __('Customer Name') }}</p>
                    <p class="font-bold">{{ $booking->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Ticket Type') }}</p>
                    <p class="font-bold">{{ $ticketType->name_en }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Phone') }}</p>
                    <p class="font-bold">{{ $booking->phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Email') }}</p>
                    <p class="font-bold">{{ $booking->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Unit Price') }}</p>
                    <p class="font-bold">{{ number_format($unitPrice) }} EGP</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Total Amount') }}</p>
                    <p class="font-bold text-red-600">{{ number_format($totalAmount) }} EGP</p>
                </div>
            </div>

            <form method="POST" action="{{ route('payment-proof.update', $booking) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <h2 class="text-xl font-bold text-gray-500 mb-4">{{ __('Payment Method') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($paymentMethods as $method)
                            <label class="border-2 rounded-lg p-4 flex items-center space-x-4 cursor-pointer transition {{ $booking->payment_method == $method['id'] ? 'border-red-600 bg-red-50' : 'border-gray-800' }}">
                                <div class="text-2xl">
                                    @if ($method['id'] === 'vodafone_cash')
                                        📱
                                    @else
                                        💳
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold">{{ __($method['name_en']) }}</h3>
                                    @if ($method['id'] === 'vodafone_cash')
                                        <p class="text-sm text-gray-500 font-mono">{{ \App\Models\Setting::cached('payment', 'vodafone_cash_number') }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 font-mono">{{ \App\Models\Setting::cached('payment', 'instapay_account') }}</p>
                                    @endif
                                </div>
                                <input type="hidden" name="payment_method" value="{{ $method['id'] }}">
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Transfer Phone') }}</label>
                    <p class="text-xs text-gray-500 mb-1">{{ __('Phone number/account you transferred the money from') }}</p>
                    <input type="text" name="transfer_phone" value="{{ old('transfer_phone') }}" required class="w-full px-4 py-2 border border-gray-700 rounded-lg focus:ring-2 focus:ring-red-500" placeholder="{{ __('Enter the phone/account used for transfer') }}">
                    @error('transfer_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('New Screenshot') }}</label>
                    <p class="text-xs text-gray-500 mb-1">{{ __('Upload a clear screenshot of the transfer') }}</p>
                    <input type="file" name="screenshot" accept="image/jpeg,image/png,image/webp,image/jpg" required class="w-full px-4 py-2 border border-gray-700 rounded-lg focus:ring-2 focus:ring-red-500">
                    @error('screenshot') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full font-bold text-white text-xl py-4 px-6 rounded-xl shadow-lg transition">{{ __('Resubmit Proof') }}</button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-500">{{ __('← Back to Home') }}</a>
        </div>
    </div>
</div>
@endsection
