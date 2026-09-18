@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-500">{{ __('Booking Details') }}: {{ $booking->reference }}</h1>
        <a href="{{ route('admin.bookings.index') }}" class="text-gray-500 hover:text-gray-500">{{ __('← Back') }}</a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Info -->
        <div class="bg-black rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('Customer Information') }}</h2>
            <div class="space-y-3">
                <div><p class="text-sm text-gray-500">{{ __('Name') }}</p><p class="font-bold">{{ $booking->name }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Phone') }}</p><p class="font-bold">{{ $booking->phone }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Email') }}</p><p class="font-bold">{{ $booking->email }}</p></div>
            </div>
        </div>

        <!-- Booking Info -->
        <div class="bg-black rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('Booking Information') }}</h2>
            <div class="space-y-3">
                <div><p class="text-sm text-gray-500">{{ __('Booking Reference') }}</p><p class="font-mono font-bold">{{ $booking->reference }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Ticket Type') }}</p><p class="font-bold">{{ $booking->ticketType->name_en }} ({{ number_format($booking->ticketType->price) }} EGP)</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Quantity') }}</p><p class="font-bold">{{ $booking->quantity }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Unit Price') }}</p><p class="font-bold">{{ number_format($booking->unit_price) }} EGP</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Total Amount') }}</p><p class="font-bold text-red-600">{{ number_format($booking->total_amount) }} EGP</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Date') }}</p><p class="font-bold">{{ $booking->created_at->format('M d, Y H:i') }}</p></div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-black rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('Payment Information') }}</h2>
            <div class="space-y-3">
                <div><p class="text-sm text-gray-500">{{ __('Payment Method') }}</p><p class="font-bold">{{ __($booking->payment_method) }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Transfer From') }}</p><p class="font-bold">{{ $booking->transfer_phone }}</p></div>
                <div>
                    <p class="text-sm text-gray-500 mb-2">{{ __('Screenshot') }}</p>
                    @if ($latestProof = $booking->paymentProofs->first())
                        <a href="{{ $latestProof->screenshot_path }}" target="_blank" class="block">
                            <img src="{{ $latestProof->screenshot_path }}" alt="Payment screenshot" class="max-h-32 rounded-lg border border-gray-800">
                        </a>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Amount') }}: {{ number_format($latestProof->amount) }} EGP • {{ $latestProof->submitted_at?->format('M d, Y H:i') }}</p>
                    @else
                        <p class="text-gray-500">{{ __('No screenshot uploaded.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Warning -->
    <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-6">
        <h3 class="font-bold text-red-800 mb-2">{{ __('DO NOT approve based on the screenshot alone.') }}</h3>
        <p class="text-red-400">{{ __('Confirm that the money has actually been received in the Vodafone Cash/InstaPay account before approving.') }}</p>
    </div>

    <!-- Current Status -->
    <div class="bg-black rounded-xl shadow-lg p-6">
        <div class="flex items-center gap-4">
            <span class="px-4 py-2 rounded-full font-medium {{ $booking->getStatusColorClass() }}">{{ $booking->getStatusLabel() }}</span>
            <span class="text-sm text-gray-500">({{ __('Submission Timestamp') }}: {{ $booking->created_at->format('M d, Y H:i') }})</span>
        </div>
    </div>

    <!-- Actions -->
    @if ($booking->status === 'pending')
    <div class="flex gap-4">
        <button type="button" onclick="openApproveModal()" class="flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition">
            <span>✅</span> {{ __('Approve Payment') }}
        </button>
        <button type="button" onclick="openDenyModal()" class="flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition">
            <span>❌</span> {{ __('Deny Payment') }}
        </button>
        <button type="button" onclick="openResubmissionModal()" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition">
            <span>🔄</span> {{ __('Request Resubmission') }}
        </button>
    </div>
    @endif

    <!-- Generated Tickets -->
    @if ($booking->tickets->isNotEmpty())
    <div class="bg-black rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('Generated Tickets') }}</h2>
        <div class="space-y-4">
            @foreach ($booking->tickets as $ticket)
            <div class="border border-gray-800 rounded-lg p-4 flex justify-between items-center">
                <div>
                    <p class="font-mono font-bold">{{ $ticket->serial }}</p>
                    <p class="text-sm text-gray-500">{{ $ticket->ticketType->name_en }} • {{ $ticket->status }}</p>
                </div>
                <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-red-600 hover:text-red-800">{{ __('View') }}</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Review History -->
    @if ($booking->adminReviews->isNotEmpty())
    <div class="bg-black rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold text-gray-500 mb-4">{{ __('Review History') }}</h2>
        <div class="space-y-4">
            @foreach ($booking->adminReviews as $review)
            <div class="border border-gray-800 rounded-lg p-4">
                <div class="flex justify-between">
                    <span class="font-medium">{{ $review->admin->name }}</span>
                    <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y H:i') }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">{{ $review->action }} • {{ $review->payment_confirmed ? 'Payment Confirmed' : 'Not Confirmed' }}</p>
                @if ($review->note)
                    <p class="text-sm text-gray-500 mt-1">{{ $review->note }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Approval Modal -->
<div id="approve-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-black rounded-xl shadow-2xl w-full max-w-lg mx-4">
        <div class="border-b border-gray-800 p-6">
            <h2 class="text-2xl font-bold text-gray-500">{{ __('Confirm Payment Approval') }}</h2>
        </div>
        <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}">
            @csrf
            <div class="p-6 space-y-4">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="font-medium text-yellow-800">{{ __('Have you personally confirmed that the payment amount has been received?') }}</p>
                </div>

                <div class="space-y-3 border-b pb-3">
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">{{ __('Expected Amount') }}</span>
                        <span class="font-bold">{{ number_format($booking->total_amount) }} EGP</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">{{ __('Payment Method') }}</span>
                        <span class="font-bold">{{ __($booking->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">{{ __('Transfer From') }}</span>
                        <span class="font-bold">{{ $booking->transfer_phone }}</span>
                    </div>
                    <input type="hidden" name="booking_reference" value="{{ $booking->reference }}">
                    <input type="hidden" name="total_amount" value="{{ $booking->total_amount }}">
                    <input type="hidden" name="payment_method" value="{{ $booking->payment_method }}">
                </div>

                <div class="flex items-start">
                    <input type="checkbox" name="payment_verified" value="1" id="confirm_checkbox" class="mt-1 mr-3" required>
                    <label for="confirm_checkbox" class="text-sm">{{ __('I confirm that I have verified that the money was received.') }}</label>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Admin Note (optional)') }}</label>
                    <textarea name="admin_note" rows="2" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></textarea>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeApproveModal()" class="flex-1 px-4 py-2 border border-gray-700 rounded-lg">{{ __('Cancel') }}</button>
                    <button type="submit" id="confirm-btn" disabled class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg font-bold">{{ __('Confirm Approval') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Deny Modal -->
<div id="deny-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-black rounded-xl shadow-2xl w-full max-w-lg mx-4">
        <div class="border-b border-gray-800 p-6">
            <h2 class="text-2xl font-bold text-gray-500">{{ __('Deny Payment') }}</h2>
        </div>
        <form method="POST" action="{{ route('admin.bookings.deny', $booking) }}">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Reason') }}</label>
                    <select name="reason" required class="w-full px-3 py-2 border border-gray-700 rounded-lg">
                        <option value="">{{ __('Select a reason...') }}</option>
                        <option value="payment_not_received">{{ __('Payment not received') }}</option>
                        <option value="wrong_amount">{{ __('Wrong amount') }}</option>
                        <option value="invalid_transfer">{{ __('Invalid transfer') }}</option>
                        <option value="screenshot_unclear">{{ __('Screenshot unclear') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Additional Note (optional)') }}</label>
                    <textarea name="note" rows="3" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeDenyModal()" class="flex-1 px-4 py-2 border border-gray-700 rounded-lg">{{ __('Cancel') }}</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-bold">{{ __('Deny Payment') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Resubmission Modal -->
<div id="resubmission-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-black rounded-xl shadow-2xl w-full max-w-lg mx-4">
        <div class="border-b border-gray-800 p-6">
            <h2 class="text-2xl font-bold text-gray-500">{{ __('Request Resubmission') }}</h2>
        </div>
        <form method="POST" action="{{ route('admin.bookings.request-resubmission', $booking) }}">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Reason') }}</label>
                    <select name="reason" required class="w-full px-3 py-2 border border-gray-700 rounded-lg">
                        <option value="">{{ __('Select a reason...') }}</option>
                        <option value="screenshot_unclear">{{ __('Screenshot unclear') }}</option>
                        <option value="wrong_amount">{{ __('Wrong amount') }}</option>
                        <option value="wrong_account">{{ __('Wrong transfer account') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Additional Note') }}</label>
                    <textarea name="note" rows="3" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeResubmissionModal()" class="flex-1 px-4 py-2 border border-gray-700 rounded-lg">{{ __('Cancel') }}</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-bold">{{ __('Request Resubmission') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openApproveModal() {
    document.getElementById('approve-modal').classList.remove('hidden');
}
function closeApproveModal() {
    document.getElementById('approve-modal').classList.add('hidden');
}
function openDenyModal() {
    document.getElementById('deny-modal').classList.remove('hidden');
}
function closeDenyModal() {
    document.getElementById('deny-modal').classList.add('hidden');
}
function openResubmissionModal() {
    document.getElementById('resubmission-modal').classList.remove('hidden');
}
function closeResubmissionModal() {
    document.getElementById('resubmission-modal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('confirm_checkbox');
    const btn = document.getElementById('confirm-btn');
    if (checkbox && btn) {
        checkbox.addEventListener('change', function() {
            btn.disabled = !this.checked;
        });
    }
});
</script>
@endpush
@endsection
