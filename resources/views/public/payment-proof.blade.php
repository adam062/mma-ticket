@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="bg-black rounded-xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-500 mb-6">{{ __('Booking') }}: {{ $booking->reference }}</h1>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500">{{ __('Customer Name') }}</p>
                    <p class="font-bold">{{ $booking->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $booking->getStatusColorClass() }}">{{ $booking->getStatusLabel() }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Phone') }}</p>
                    <p class="font-bold">{{ $booking->phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('Email') }}</p>
                    <p class="font-bold">{{ $booking->email }}</p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-500 mb-4">{{ __('Payment Proof') }}</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Payment Method') }}</p>
                        <p class="font-bold">{{ __($proof->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Transfer Phone') }}</p>
                        <p class="font-bold">{{ $proof->transfer_phone }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500 mb-2">{{ __('Screenshot') }}</p>
                        <img src="{{ asset('storage/' . $proof->screenshot_path) }}" alt="Payment screenshot" class="max-h-64 rounded-lg border border-gray-800">
                    </div>
                </div>
            </div>

            @if ($booking->status === \App\Models\Booking::STATUS_RESUBMISSION_REQUIRED)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-yellow-800 mb-2">{{ __('Resubmission Required') }}</h3>
                    <p class="text-yellow-700">
                        @foreach ($booking->adminReviews as $review)
                            @if ($review->action === 'resubmission')
                                {{ $review->note }}
                            @endif
                        @endforeach
                    </p>
                </div>

                <form method="POST" action="{{ route('payment-proof.update', $booking) }}" enctype="multipart/form-data" class="space-y-4">
                    @method('PUT')
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('New Transfer Phone') }}</label>
                        <input type="text" name="transfer_phone" required class="w-full px-4 py-2 border border-gray-700 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('New Screenshot') }}</label>
                        <input type="file" name="screenshot" accept="image/jpeg,image/png,image/webp" required class="w-full px-4 py-2 border border-gray-700 rounded-lg">
                    </div>
                    <button type="submit" class="w-full font-bold text-white py-2 px-4 rounded-lg">{{ __('Resubmit Proof') }}</button>
                </form>
            @elseif ($booking->status === \App\Models\Booking::STATUS_DENIED)
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-red-800 mb-2">{{ __('Booking Denied') }}</h3>
                    <p class="text-red-400">{{ $booking->denial_reason }}</p>
                </div>
            @elseif ($booking->status === \App\Models\Booking::STATUS_PENDING)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <p class="text-yellow-800 font-medium">{{ __('Your request is waiting for manual payment verification.') }}</p>
                </div>
            @endif
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-500">{{ __('← Back to Home') }}</a>
        </div>
    </div>
</div>
@endsection
