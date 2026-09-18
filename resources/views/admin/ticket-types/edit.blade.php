@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-500">{{ __('Edit Ticket Type') }}</h1>

    <form method="POST" action="{{ route('admin.ticket-types.update', $ticketType) }}" class="bg-black rounded-xl shadow-lg p-6 space-y-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium mb-1">{{ __('Name (English)') }}</label><input type="text" name="name_en" value="{{ $ticketType->name_en }}" required class="w-full px-3 py-2 border border-gray-700 rounded-lg"></div>
            <div><label class="block text-sm font-medium mb-1">{{ __('Name (Arabic)') }}</label><input type="text" name="name_ar" value="{{ $ticketType->name_ar }}" required class="w-full px-3 py-2 border border-gray-700 rounded-lg" dir="rtl"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium mb-1">{{ __('Description (English)') }}</label><textarea name="description_en" rows="3" class="w-full px-3 py-2 border border-gray-700 rounded-lg">{{ $ticketType->description_en }}</textarea></div>
            <div><label class="block text-sm font-medium mb-1">{{ __('Description (Arabic)') }}</label><textarea name="description_ar" rows="3" class="w-full px-3 py-2 border border-gray-700 rounded-lg" dir="rtl">{{ $ticketType->description_ar }}</textarea></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div><label class="block text-sm font-medium mb-1">{{ __('Price (EGP)') }}</label><input type="number" name="price" value="{{ $ticketType->price }}" required min="1" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></div>
            <div><label class="block text-sm font-medium mb-1">{{ __('Capacity') }}</label><input type="number" name="capacity" value="{{ $ticketType->capacity }}" required min="1" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></div>
            <div><label class="block text-sm font-medium mb-1">{{ __('Active') }}</label><select name="is_active" class="w-full px-3 py-2 border border-gray-700 rounded-lg"><option value="1" {{ $ticketType->is_active ? 'selected' : '' }}>Yes</option><option value="0" {{ ! $ticketType->is_active ? 'selected' : '' }}>No</option></select></div>
            <div><label class="block text-sm font-medium mb-1">{{ __('Sort Order') }}</label><input type="number" name="sort_order" value="{{ $ticketType->sort_order }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></div>
        </div>
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-bold">{{ __('Save Changes') }}</button>
    </form>
</div>
@endsection
