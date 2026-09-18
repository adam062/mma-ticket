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
                <div class="bg-red-600 h-2 rounded-full" style="width: 20%"></div>
            </div>
        </div>

        @if ($selectedType = ($typeId = request('type')) ? $ticketTypes->firstWhere('id', $typeId) : null)
            @php($selectedTypeId = $selectedType->id)
        @else
            @php($selectedTypeId = null)
        @endif

        <form method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Customer Information -->
            <div class="bg-black rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-500 mb-4">{{ __('Customer Information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Full Name') }}</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full px-4 py-2 border border-gray-700 rounded-lg focus:ring-2 focus:ring-red-500">
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Phone Number') }}</label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required class="w-full px-4 py-2 border border-gray-700 rounded-lg focus:ring-2 focus:ring-red-500">
                        @error('phone_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">{{ __('Email Address') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-700 rounded-lg focus:ring-2 focus:ring-red-500">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Ticket Type Selection -->
            <div class="bg-black rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-500 mb-4">{{ __('Select Ticket Type') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($ticketTypes as $type)
                    <div class="border-2 rounded-lg p-4 text-center cursor-pointer transition {{ $selectedTypeId == $type->id ? 'border-red-600 bg-red-50' : 'border-gray-800 hover:border-red-400' }}">
                        <input type="radio" name="ticket_type_id" value="{{ $type->id }}" {{ $selectedTypeId == $type->id ? 'checked' : '' }} class="sr-only" id="type_{{ $type->id }}">
                        <label for="type_{{ $type->id }}" class="cursor-pointer">
                            <h3 class="font-bold text-lg">{{ $type->name_en }}</h3>
                            <p class="text-2xl font-bold text-red-600 my-2">{{ number_format($type->price) }} EGP</p>
                            <p class="text-sm text-gray-500">{{ Str::limit($type->description_en, 80) }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ __('Capacity') }}: {{ $type->capacity }}</p>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('ticket_type_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <!-- Quantity -->
            <div class="bg-black rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-500 mb-4">{{ __('Select Quantity') }}</h2>
                <div class="flex items-center justify-center space-x-6">
                    <button type="button" id="minus" class="w-12 h-12 bg-gray-200 rounded-full text-2xl font-bold text-gray-500 hover:bg-gray-300 transition">-</button>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" class="w-20 text-center text-2xl font-bold border-2 border-gray-700 rounded-lg py-2" readonly>
                    <button type="button" id="plus" class="w-12 h-12 bg-red-600 rounded-full text-2xl font-bold text-white hover:bg-red-700 transition">+</button>
                </div>
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-500">{{ __('Ticket Price') }}:</p>
                    <p class="text-3xl font-bold text-red-600" id="unit-price-display">{{ number_format($ticketTypes->firstWhere('id', $selectedTypeId)?->price ?? 0) }} EGP</p>
                </div>
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-500">{{ __('Quantity') }}:</p>
                    <p class="text-3xl font-bold" id="quantity-display">1</p>
                </div>
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-500">{{ __('Total') }}:</p>
                    <p class="text-4xl font-extrabold text-red-600" id="total-display">{{ number_format($ticketTypes->firstWhere('id', $selectedTypeId)?->price ?? 0) }} EGP</p>
                </div>
                @error('quantity') <p class="text-red-500 text-xs mt-2 text-center">{{ $message }}</p> @enderror
            </div>

            <!-- Payment Method -->
            <div class="bg-black rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-500 mb-4">{{ __('Payment Method') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="border-2 rounded-lg p-4 flex items-center space-x-4 cursor-pointer transition {{ old('payment_method') == 'vodafone_cash' ? 'border-red-600 bg-red-50' : 'border-gray-800 hover:border-red-400' }}">
                        <input type="radio" name="payment_method" value="vodafone_cash" {{ old('payment_method') == 'vodafone_cash' ? 'checked' : '' }} class="sr-only">
                        <div class="text-2xl">📱</div>
                        <div>
                            <h3 class="font-bold">{{ __('Vodafone Cash') }}</h3>
                            <p class="text-sm text-gray-500 font-mono">{{ \App\Models\Setting::cached('payment', 'vodafone_cash_number') }}</p>
                        </div>
                    </label>
                    <label class="border-2 rounded-lg p-4 flex items-center space-x-4 cursor-pointer transition {{ old('payment_method') == 'instapay' ? 'border-red-600 bg-red-50' : 'border-gray-800 hover:border-red-400' }}">
                        <input type="radio" name="payment_method" value="instapay" {{ old('payment_method') == 'instapay' ? 'checked' : '' }} class="sr-only">
                        <div class="text-2xl">💳</div>
                        <div>
                            <h3 class="font-bold">{{ __('InstaPay') }}</h3>
                            <p class="text-sm text-gray-500 font-mono">{{ \App\Models\Setting::cached('payment', 'instapay_account') }}</p>
                        </div>
                    </label>
                </div>
                @error('payment_method') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <!-- Transfer Phone -->
            <div class="bg-black rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-500 mb-4">{{ __('Payment Details') }}</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Transfer Phone') }}</label>
                        <p class="text-xs text-gray-500 mb-1">{{ __('Phone number/account you transferred the money from') }}</p>
                        <input type="text" name="transfer_phone" value="{{ old('transfer_phone') }}" required class="w-full px-4 py-2 border border-gray-700 rounded-lg focus:ring-2 focus:ring-red-500" placeholder="{{ __('Enter the phone/account used for transfer') }}">
                        @error('transfer_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Transfer Screenshot') }}</label>
                        <input type="file" name="screenshot" accept="image/jpeg,image/png,image/webp,image/jpg" required class="w-full px-4 py-2 border border-gray-700 rounded-lg focus:ring-2 focus:ring-red-500">
                        @error('screenshot') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full font-bold text-white text-xl py-4 px-6 rounded-xl shadow-lg transition">{{ __('Submit Request') }}</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const types = @json($ticketTypes);
    const minus = document.getElementById('minus');
    const plus = document.getElementById('plus');
    const quantityInput = document.getElementById('quantity');
    let quantity = parseInt(quantityInput.value) || 1;

    function updatePrice() {
        const selectedType = types.find(t => String(t.id) === document.querySelector('input[name="ticket_type_id"]:checked')?.value);
        const price = selectedType ? selectedType.price : 0;
        const total = price * quantity;
        document.getElementById('unit-price-display').textContent = new Intl.NumberFormat('en-US').format(price) + ' EGP';
        document.getElementById('quantity-display').textContent = quantity;
        document.getElementById('total-display').textContent = new Intl.NumberFormat('en-US').format(total) + ' EGP';
    }

    minus.addEventListener('click', function() {
        if (quantity > 1) quantity--;
        quantityInput.value = quantity;
        updatePrice();
    });

    plus.addEventListener('click', function() {
        if (quantity < 10) quantity++;
        quantityInput.value = quantity;
        updatePrice();
    });

    document.querySelectorAll('input[name="ticket_type_id"]').forEach(radio => {
        radio.addEventListener('change', updatePrice);
    });

    updatePrice();
});
</script>
@endpush
@endsection
