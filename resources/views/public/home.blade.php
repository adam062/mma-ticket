@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-gray-900 via-red-900 to-black text-white py-20 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center opacity-15" style="background-image: url('https://images.unsplash.com/photo-1544131330517-dedff4f3e7e7?auto=format&fit=crop&w=2000&q=80&sat=0&contrast=100')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black opacity-60"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <div class="mb-12 animate-in fade-in-up duration-700">
                @php
                    $logo = \App\Models\Setting::cached('event', 'logo');
                @endphp
                <div class="mx-auto mb-8 flex items-center justify-center animate-bounce">
                    <svg class="w-40 h-40 drop-shadow-2xl" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="48" fill="#ef4444" stroke="#fbbf24" stroke-width="3" class="animate-pulse"/>
                        <circle cx="50" cy="50" r="32" fill="#000000" stroke="#ef4444" stroke-width="2"/>
                        <path d="M30 40 C30 32 38 26 50 26 C62 26 70 32 70 40 C70 48 62 52 54 56 C48 59 48 62 48 66" stroke="#fbbf24" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="50" cy="74" r="3" fill="#fbbf24"/>
                    </svg>
                    @if ($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ __('Event Logo') }}" class="absolute w-40 h-40 object-contain drop-shadow-2xl">
                    @else
                        <div class="absolute text-5xl font-black text-red-400 drop-shadow-2xl">MMA</div>
                    @endif
                </div>
                <h1 class="text-5xl md:text-7xl font-extrabold text-red-400 mb-4 drop-shadow-lg animate-in fade-in-up duration-700 delay-100">{{ $eventNameEn }}</h1>
                <p class="text-2xl text-gray-300 mb-2 flex items-center justify-center"><span class="mr-2">📅</span> {{ $eventDate }} • {{ $eventTime }}</p>
                <p class="text-lg text-gray-400 mb-8 flex items-center justify-center"><span class="mr-2">📍</span> {{ $eventLocationEn }}</p>
            </div>

            <div class="animate-in fade-in-up duration-700 delay-200">
                <a href="{{ route('booking.create') }}" class="inline-flex items-center space-x-3 bg-red-500 hover:bg-red-600 text-white font-bold text-xl py-5 px-12 rounded-full shadow-2xl shadow-red-500/40 transform hover:scale-105 transition-all duration-300 group">
                    <span>{{ __('Book Ticket') }}</span>
                    <span>🏆</span>
                </a>
            </div>

            <div class="mt-12 animate-in fade-in-up duration-700 delay-300">
                <a href="{{ route('home') }}#ticket-types" class="text-gray-400 hover:text-red-400 transition-colors duration-300 flex items-center justify-center group">
                    <span class="mr-2 transform group-hover:translate-y-1">↓</span>
                    <span>{{ __('See Ticket Types') }}</span>
                    <span class="ml-2 transform group-hover:translate-y-1">↓</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Event Info -->
    <section class="py-20 bg-black">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="animate-in fade-in-left duration-700">
                    <h2 class="text-3xl font-bold text-gray-200 mb-6">{{ __('About the Event') }}</h2>
                    @if (app()->getLocale() === 'ar')
                        <p class="text-lg text-gray-400 leading-relaxed">{{ $eventDescAr ?: $eventDescEn }}</p>
                    @else
                        <p class="text-lg text-gray-400 leading-relaxed">{{ $eventDescEn }}</p>
                    @endif
                </div>
                <div class="animate-in fade-in-right duration-700 delay-100">
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 space-y-4 hover:border-gray-700 transition-all duration-300">
                        <div class="flex">
                            <span class="w-32 font-semibold text-gray-400">{{ __('Date') }}</span>
                            <span class="text-gray-200">{{ $eventDate }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 font-semibold text-gray-400">{{ __('Time') }}</span>
                            <span class="text-gray-200">{{ $eventTime }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 font-semibold text-gray-400">{{ __('Location') }}</span>
                            <span class="text-gray-200">{{ $eventLocationEn }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ticket Types -->
    <section id="ticket-types" class="py-20 bg-black">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-in fade-in-up duration-500">
                <h2 class="text-4xl font-bold text-gray-100 mb-4">{{ __('Choose Your Ticket') }}</h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">{{ __('Select from our premium ticket options and secure your seat for the ultimate MMA experience.') }}</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach ($ticketTypes as $type)
                <div class="group animate-in fade-in-up duration-500" style="animation-delay: {{ $loop->index * 100 }}ms">
                    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden transform hover:scale-105 hover:shadow-2xl hover:shadow-red-500/20 transition-all duration-300">
                        <div class="bg-gradient-to-r from-red-500 to-red-700 text-white p-6">
                            <h3 class="text-2xl font-bold">{{ $type->name_en }}</h3>
                            <p class="text-3xl font-extrabold mt-2">{{ number_format($type->price) }} EGP</p>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-400 mb-4">{{ Str::limit($type->description_en, 120) }}</p>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm text-gray-500">{{ __('Capacity') }}: {{ $type->capacity }}</span>
                                <span class="text-sm {{ $type->getRemainingCapacity() > 0 ? 'text-green-400' : 'text-red-500' }} flex items-center">
                                    <span class="w-2 h-2 {{ $type->getRemainingCapacity() > 0 ? 'bg-green-400' : 'bg-red-500' }} rounded-full mr-1 animate-pulse"></span>
                                    {{ $type->getRemainingCapacity() > 0 ? __('Available') : __('Sold Out') }}
                                </span>
                            </div>
                            @if ($type->getRemainingCapacity() > 0)
                                <a href="{{ route('booking.create') }}?type={{ $type->id }}" class="block w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-xl text-center transition-all duration-200 flex items-center justify-center space-x-2 group-hover:scale-105">
                                    <span>{{ __('Book Now') }}</span>
                                </a>
                            @else
                                <div class="block w-full bg-gray-700 text-gray-500 font-bold py-3 px-4 rounded-xl text-center">{{ __('Sold Out') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Payment Explanation -->
    <section class="py-20 bg-black">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-in fade-in-up duration-500">
                <h2 class="text-4xl font-bold text-gray-100 mb-4">{{ __('Payment Information') }}</h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">{{ __('Multiple convenient payment methods are available for your booking.') }}</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="group animate-in fade-in-up duration-500 delay-100">
                    <div class="bg-gray-900 border border-gray-800 group-hover:border-gray-700 group-hover:shadow-xl transition-all duration-300 rounded-2xl p-10 text-center">
                        <div class="text-5xl mb-6 group-hover:scale-110 transition-transform duration-300">📱</div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-200">{{ __('Vodafone Cash') }}</h3>
                        <p class="text-gray-400 mb-2">{{ __('Number') }}: <span class="font-mono font-bold text-gray-300">{{ \App\Models\Setting::cached('payment', 'vodafone_cash_number') }}</span></p>
                        <p class="text-gray-400">{{ __('Account') }}: {{ \App\Models\Setting::cached('payment', 'vodafone_cash_name') }}</p>
                    </div>
                </div>
                <div class="group animate-in fade-in-up duration-500 delay-200">
                    <div class="bg-gray-900 border border-gray-800 group-hover:border-gray-700 group-hover:shadow-xl transition-all duration-300 rounded-2xl p-10 text-center">
                        <div class="text-5xl mb-6 group-hover:scale-110 transition-transform duration-300">💳</div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-200">{{ __('InstaPay') }}</h3>
                        <p class="text-gray-400 mb-2">{{ __('Account') }}: <span class="font-mono font-bold text-gray-300">{{ \App\Models\Setting::cached('payment', 'instapay_account') }}</span></p>
                        <p class="text-gray-400">{{ __('Name') }}: {{ \App\Models\Setting::cached('payment', 'instapay_name') }}</p>
                    </div>
                </div>
            </div>
            <p class="text-center text-sm text-gray-500 mt-12 animate-in fade-in duration-500 delay-300">{{ __('Your ticket is NOT confirmed until the administration verifies that the payment has actually been received.') }}</p>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-20 bg-gray-950">
        <div class="container mx-auto px-4 max-w-3xl">
            <div class="text-center mb-16 animate-in fade-in-up duration-500">
                <h2 class="text-4xl font-bold text-gray-100 mb-4">{{ __('Frequently Asked Questions') }}</h2>
                <p class="text-gray-400 text-lg">{{ __('Find answers to common questions about booking and tickets.') }}</p>
            </div>
            <div class="space-y-6">
                @foreach ([
                    ['q' => 'faq_q1', 'a' => 'faq_a1'],
                    ['q' => 'faq_q2', 'a' => 'faq_a2'],
                    ['q' => 'faq_q3', 'a' => 'faq_a3'],
                    ['q' => 'faq_q4', 'a' => 'faq_a4'],
                    ['q' => 'faq_q5', 'a' => 'faq_a5'],
                ] as $item)
                <div class="group bg-gray-900 border border-gray-800 group-hover:border-gray-700 group-hover:shadow-xl rounded-2xl p-6 transition-all duration-300 animate-in fade-in-up" style="animation-delay: {{ $loop->index * 100 }}ms">
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <span class="text-red-400 font-bold">{{ $loop->iteration }}</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg mb-2 text-gray-200 group-hover:text-red-400 transition-colors">{{ __($item['q']) }}</h3>
                            <p class="text-gray-400 leading-relaxed">{{ __($item['a']) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-gradient-to-r from-red-600 via-red-700 to-red-800 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <h2 class="text-3xl md:text-5xl font-bold mb-6 animate-bounce">{{ __('Ready to witness the action?') }}</h2>
            <p class="text-xl mb-8 text-gray-200">{{ __('Join us for the ultimate MMA experience!') }}</p>
            <a href="{{ route('booking.create') }}" class="inline-flex items-center space-x-3 bg-black hover:bg-gray-900 text-red-400 font-bold text-xl py-5 px-12 rounded-full shadow-2xl transform hover:scale-105 transition-all duration-300 group">
                <span>{{ __('Book Ticket') }}</span>
                <span>{{ __('احجز تذكرتك') }}</span>
            </a>
        </div>
    </section>
</div>
@endsection
