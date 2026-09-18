@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="mb-8 animate-in fade-in duration-500">
            <div class="flex justify-between mb-3">
                <span class="text-sm font-medium {{ $step >= 1 ? 'text-red-400' : 'text-gray-600' }} transition-colors duration-300">{{ __('Step 1') }} — {{ __('Customer Info') }}</span>
                <span class="text-sm font-medium {{ $step >= 2 ? 'text-red-400' : 'text-gray-600' }} transition-colors duration-300">{{ __('Step 2') }} — {{ __('Ticket & Quantity') }}</span>
                <span class="text-sm font-medium {{ $step >= 3 ? 'text-red-400' : 'text-gray-600' }} transition-colors duration-300">{{ __('Step 3') }} — {{ __('Payment Method') }}</span>
                <span class="text-sm font-medium {{ $step >= 4 ? 'text-red-400' : 'text-gray-600' }} transition-colors duration-300">{{ __('Step 4') }} — {{ __('Payment Proof') }}</span>
            </div>
            <div class="w-full bg-gray-900 rounded-full h-2.5 overflow-hidden">
                <div class="bg-red-500 h-2.5 rounded-full transition-all duration-500 ease-out shadow-red-500/30" style="width: {{ ($step / 4 * 100) }}%"></div>
            </div>
        </div>

        @if ($message = session('success'))
            <div class="bg-green-900/20 border border-green-600 rounded-xl p-4 mb-6 animate-in fade-in">
                <p class="text-green-400 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.616 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('booking.step.store', $step) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            @if ($step === 1)
                <div class="bg-gray-900 rounded-2xl shadow-2xl p-8 animate-in fade-in-up duration-500">
                    <h2 class="text-2xl font-bold text-gray-200 mb-6 flex items-center"><span class="mr-3 text-3xl">👤</span> {{ __('Customer Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-sm font-medium mb-2 text-gray-400 transition-colors group-focus-within:text-red-400">{{ __('Full Name') }}</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $data['full_name'] ?? '') }}" required class="w-full px-4 py-3 bg-gray-950 border border-gray-700 rounded-xl text-gray-200 transition-all duration-300 input-focus placeholder-gray-600">
                            @error('full_name')
                            <p class="text-red-400 text-xs mt-1 flex items-center animate-in fade-in"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p>
                            @enderror
                        </div>
                        <div class="group">
                            <label class="block text-sm font-medium mb-2 text-gray-400 transition-colors group-focus-within:text-red-400">{{ __('Phone Number') }}</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number', $data['phone_number'] ?? '') }}" required class="w-full px-4 py-3 bg-gray-950 border border-gray-700 rounded-xl text-gray-200 transition-all duration-300 input-focus placeholder-gray-600">
                            @error('phone_number')
                            <p class="text-red-400 text-xs mt-1 flex items-center animate-in fade-in"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2 group">
                            <label class="block text-sm font-medium mb-2 text-gray-400 transition-colors group-focus-within:text-red-400">{{ __('Email Address') }}</label>
                            <input type="email" name="email" value="{{ old('email', $data['email'] ?? '') }}" required class="w-full px-4 py-3 bg-gray-950 border border-gray-700 rounded-xl text-gray-200 transition-all duration-300 input-focus placeholder-gray-600">
                            @error('email')
                            <p class="text-red-400 text-xs mt-1 flex items-center animate-in fade-in"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @elseif ($step === 2)
                @php
                    $selectedType = ($data['ticket_type_id'] ?? null) ? $ticketTypes->firstWhere('id', $data['ticket_type_id']) : $ticketTypes->first();
                @endphp
                <div class="bg-gray-900 rounded-2xl shadow-2xl p-8 animate-in fade-in-up duration-500">
                    <h2 class="text-2xl font-bold text-gray-200 mb-6 flex items-center"><span class="mr-3 text-3xl">🎫</span> {{ __('Select Ticket Type & Quantity') }}</h2>

                    <div class="mb-8">
                        <p class="text-sm text-gray-400 mb-4">{{ __('Click a ticket type to select it, then choose your quantity.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        @foreach ($ticketTypes as $type)
                        <div class="group">
                            <div class="{{ ($data['ticket_type_id'] ?? '') == $type->id ? 'border-red-500 bg-red-900/20 scale-105' : 'border-gray-700 bg-gray-900 hover:border-red-500 hover:scale-105 hover:shadow-xl' }} border-2 rounded-xl p-6 text-center cursor-pointer transition-all duration-300">
                                <input type="radio" name="ticket_type_id" value="{{ $type->id }}" {{ ($data['ticket_type_id'] ?? '') == $type->id ? 'checked' : '' }} class="sr-only" id="type_{{ $type->id }}">
                                <label for="type_{{ $type->id }}" class="cursor-pointer">
                                    <h3 class="font-bold text-lg text-gray-200 mb-3 group-hover:text-red-400 transition-colors">{{ $type->name_en }}</h3>
                                    <p class="text-3xl font-bold text-red-400 my-3">{{ number_format($type->price) }} EGP</p>
                                    <p class="text-sm text-gray-400">{{ \Illuminate\Support\Str::limit($type->description_en, 80) }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ __('Capacity') }}: {{ $type->capacity }}</p>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('ticket_type_id') <p class="text-red-400 text-xs mt-2 flex items-center animate-in fade-in"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror

                    <div class="bg-gray-950 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-lg font-bold text-gray-200 mb-4">{{ __('Quantity') }}</h3>
                        <div class="text-center mb-6">
                            <p class="text-sm text-gray-400 mb-2">{{ __('Ticket Price') }}</p>
                            <p class="text-3xl font-bold text-red-400" id="unit-price-display">{{ number_format($selectedType ? $selectedType->price : 0) }} EGP</p>
                        </div>
                        <div class="flex items-center justify-center space-x-8">
                            <button type="button" id="minus" class="w-14 h-14 bg-gray-900 text-gray-400 rounded-full text-2xl font-bold hover:bg-gray-800 hover:text-red-400 hover:scale-110 transition-all duration-200 btn border-2 border-gray-700 hover:border-red-500 flex items-center justify-center">−</button>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $data['quantity'] ?? 1) }}" min="1" max="10" class="w-24 text-center text-2xl font-bold bg-gray-900 border-2 border-gray-700 rounded-xl py-3 text-gray-200 transition-all duration-300" readonly>
                            <button type="button" id="plus" class="w-14 h-14 bg-red-500 text-white rounded-full text-2xl font-bold hover:bg-red-600 hover:scale-110 transition-all duration-200 btn border-2 border-red-400 flex items-center justify-center">+</button>
                        </div>
                        <div class="text-center mt-6">
                            <p class="text-sm text-gray-400 mb-2">{{ __('Total') }}</p>
                            <p class="text-4xl font-extrabold text-red-400" id="total-display">{{ number_format(($selectedType ? $selectedType->price : 0) * ($data['quantity'] ?? 1)) }} EGP</p>
                        </div>
                    </div>
                    @error('quantity') <p class="text-red-400 text-xs mt-2 text-center flex items-center justify-center animate-in fade-in"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror
                </div>
            @elseif ($step === 3)
                <div class="bg-gray-900 rounded-2xl shadow-2xl p-8 animate-in fade-in-up duration-500">
                    <h2 class="text-2xl font-bold text-gray-200 mb-6 flex items-center"><span class="mr-3 text-3xl">💳</span> {{ __('Payment Method') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($paymentMethods as $method)
                        <div class="group">
                            <label class="{{ ($data['payment_method'] ?? '') == $method['id'] ? 'border-red-500 bg-red-900/20 scale-105' : 'border-gray-700 bg-gray-900 hover:border-red-500 hover:scale-105 hover:shadow-xl' }} border-2 rounded-xl p-6 flex items-center space-x-4 cursor-pointer transition-all duration-300">
                                <input type="radio" name="payment_method" value="{{ $method['id'] }}" {{ ($data['payment_method'] ?? '') == $method['id'] ? 'checked' : '' }} class="sr-only">
                                <div class="text-3xl transition-transform duration-300 group-hover:scale-110">
                                    @if ($method['id'] === 'vodafone_cash')
                                        📱
                                    @else
                                        💳
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-200 group-hover:text-red-400 transition-colors">{{ __($method['name_en']) }}</h3>
                                    @if ($method['id'] === 'vodafone_cash')
                                        <p class="text-sm text-gray-400 font-mono">{{ \App\Models\Setting::cached('payment', 'vodafone_cash_number') }}</p>
                                    @else
                                        <p class="text-sm text-gray-400 font-mono">{{ \App\Models\Setting::cached('payment', 'instapay_account') }}</p>
                                    @endif
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('payment_method') <p class="text-red-400 text-xs mt-2 flex items-center animate-in fade-in"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror
                </div>
            @elseif ($step === 4)
                @php
                    $selectedType = ($data['ticket_type_id'] ?? null) ? $ticketTypes->firstWhere('id', $data['ticket_type_id']) : null;
                    $totalAmount = $selectedType ? $selectedType->price * ($data['quantity'] ?? 1) : 0;
                @endphp
                <div class="bg-gray-900 rounded-2xl shadow-2xl p-8 animate-in fade-in-up duration-500">
                    <h2 class="text-2xl font-bold text-gray-200 mb-6 flex items-center"><span class="mr-3 text-3xl">📤</span> {{ __('Payment Details') }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="bg-gray-950 rounded-xl p-4 border border-gray-700 transition-all duration-300 hover:border-gray-600">
                            <p class="text-xs text-gray-500 uppercase">{{ __('Customer Name') }}</p>
                            <p class="font-bold text-gray-200 mt-1">{{ $data['full_name'] ?? '' }}</p>
                        </div>
                        <div class="bg-gray-950 rounded-xl p-4 border border-gray-700 transition-all duration-300 hover:border-gray-600">
                            <p class="text-xs text-gray-500 uppercase">{{ __('Ticket Type') }}</p>
                            <p class="font-bold text-gray-200 mt-1">{{ $selectedType ? $selectedType->name_en : '' }}</p>
                        </div>
                        <div class="bg-gray-950 rounded-xl p-4 border border-gray-700 transition-all duration-300 hover:border-gray-600">
                            <p class="text-xs text-gray-500 uppercase">{{ __('Quantity') }}</p>
                            <p class="font-bold text-gray-200 mt-1">{{ $data['quantity'] ?? 1 }} {{ __('ticket(s)') }}</p>
                        </div>
                        <div class="bg-gray-950 rounded-xl p-4 border border-gray-700 transition-all duration-300 hover:border-red-500 hover:bg-red-900/10">
                            <p class="text-xs text-gray-500 uppercase">{{ __('Total Amount') }}</p>
                            <p class="font-bold text-2xl text-red-400 mt-1">{{ number_format($totalAmount) }} EGP</p>
                        </div>
                        <div class="bg-gray-950 rounded-xl p-4 border border-gray-700 transition-all duration-300 hover:border-gray-600">
                            <p class="text-xs text-gray-500 uppercase">{{ __('Payment Method') }}</p>
                            <p class="font-bold text-gray-200 mt-1">
                                @if (($data['payment_method'] ?? '') === 'vodafone_cash')
                                    {{ __('Vodafone Cash') }}
                                @else
                                    {{ __('InstaPay') }}
                                @endif
                            </p>
                        </div>
                        <div class="bg-gray-950 rounded-xl p-4 border border-gray-700 transition-all duration-300 hover:border-gray-600">
                            <p class="text-xs text-gray-500 uppercase">{{ __('Payment To') }}</p>
                            <p class="font-bold text-gray-200 font-mono mt-1 text-sm break-all">{{ \App\Models\Setting::cached('payment', ($data['payment_method'] ?? '') === 'vodafone_cash' ? 'vodafone_cash_number' : 'instapay_account') }}</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="group">
                            <label class="block text-sm font-medium mb-2 text-gray-400 transition-colors group-focus-within:text-red-400">{{ __('Transfer Phone') }}</label>
                            <p class="text-xs text-gray-500 mb-2">{{ __('Phone number/account you transferred the money from') }}</p>
                            <input type="text" name="transfer_phone" value="{{ old('transfer_phone', $data['transfer_phone'] ?? '') }}" required class="w-full px-4 py-3 bg-gray-950 border border-gray-700 rounded-xl text-gray-200 transition-all duration-300 input-focus placeholder-gray-600">
                            @error('transfer_phone') <p class="text-red-400 text-xs mt-1 flex items-center animate-in fade-in"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 0j2 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-400">{{ __('Transfer Screenshot') }}</label>
                            <p class="text-xs text-gray-500 mb-2">{{ __('Upload a clear screenshot of the transfer') }}</p>
                            <div class="border-2 border-dashed border-gray-700 rounded-xl p-6 text-center hover:border-red-500 hover:bg-gray-950/50 transition-all duration-300">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V8m0 0l-3 3m3-3l3 3M7 8h14a2 2 0 012 2v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-.5"></path></svg>
                                <input type="file" name="screenshot" accept="image/jpeg,image/png,image/webp,image/jpg" required class="w-full text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-500 file:text-white file:hover:bg-red-600 file:transition-colors">
                                <p class="text-xs text-gray-500 mt-2">{{ __('PNG, JPG, WEBP - Max 5MB') }}</p>
                                @error('screenshot') <p class="text-red-400 text-xs mt-1 flex items-center justify-center animate-in fade-in"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($step === 4)
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold text-xl py-4 px-6 rounded-2xl shadow-lg shadow-red-500/30 hover:shadow-red-500/40 transition-all duration-200 btn animate-in fade-in-up">
                    {{ __('Submit Request') }}
                </button>
            @else
                <div class="flex justify-between">
                    @if ($step > 1)
                        <a href="{{ route('booking.step', $step - 1) }}" class="bg-gray-900 hover:bg-gray-800 text-gray-300 hover:text-white font-bold py-3 px-8 rounded-xl border border-gray-700 hover:border-gray-600 transition-all duration-200 btn flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        {{ __('Previous') }}
                        </a>
                    @else
                        <div></div>
                    @endif
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-10 rounded-xl shadow-lg shadow-red-500/30 hover:shadow-red-500/40 transition-all duration-200 btn flex items-center">
                        {{ __('Next') }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>

@if ($step === 2)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ticketTypes = @json($ticketTypes->map(function($t) { return ['id' => $t->id, 'price' => $t->price]; }));
    const minus = document.getElementById('minus');
    const plus = document.getElementById('plus');
    const quantityInput = document.getElementById('quantity');
    let quantity = parseInt(quantityInput.value) || 1;

    function getSelectedPrice() {
        const selectedRadio = document.querySelector('input[name="ticket_type_id"]:checked');
        if (!selectedRadio) return 0;
        const type = ticketTypes.find(t => String(t.id) === selectedRadio.value);
        return type ? type.price : 0;
    }

    function updateTotal() {
        const price = getSelectedPrice();
        const total = price * quantity;
        const unitDisplay = document.getElementById('unit-price-display');
        if (unitDisplay) {
            unitDisplay.innerHTML = new Intl.NumberFormat('en-US').format(price) + ' EGP';
            unitDisplay.classList.add('animate-pulse');
            setTimeout(() => unitDisplay.classList.remove('animate-pulse'), 300);
        }
        const totalDisplay = document.getElementById('total-display');
        if (totalDisplay) {
            totalDisplay.innerHTML = new Intl.NumberFormat('en-US').format(total) + ' EGP';
            totalDisplay.classList.add('animate-pulse');
            setTimeout(() => totalDisplay.classList.remove('animate-pulse'), 300);
        }
    }

    minus.addEventListener('click', function() {
        if (quantity > 1) {
            quantity--;
            quantityInput.value = quantity;
            quantityInput.classList.add('scale-105');
            setTimeout(() => quantityInput.classList.remove('scale-105'), 200);
            updateTotal();
        }
    });

    plus.addEventListener('click', function() {
        if (quantity < 10) {
            quantity++;
            quantityInput.value = quantity;
            quantityInput.classList.add('scale-105');
            setTimeout(() => quantityInput.classList.remove('scale-105'), 200);
            updateTotal();
        }
    });

    document.querySelectorAll('input[name="ticket_type_id"]').forEach(radio => {
        radio.addEventListener('change', updateTotal);
    });

    updateTotal();
});
</script>
@endpush
@endif
@endsection
