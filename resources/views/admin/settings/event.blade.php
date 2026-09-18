@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-500">{{ __('Event Settings') }}</h1>

    <form method="POST" action="{{ route('admin.settings.event.update') }}" enctype="multipart/form-data" class="bg-black rounded-xl shadow-lg p-6 space-y-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium mb-1">{{ __('Event Name (English)') }}</label><input type="text" name="name_en" value="{{ $settings['name_en'] }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></div>
            <div><label class="block text-sm font-medium mb-1">{{ __('Event Name (Arabic)') }}</label><input type="text" name="name_ar" value="{{ $settings['name_ar'] }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg" dir="rtl"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium mb-1">{{ __('Event Date') }}</label><input type="date" name="date" value="{{ $settings['date'] }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></div>
            <div><label class="block text-sm font-medium mb-1">{{ __('Event Time') }}</label><input type="time" name="time" value="{{ $settings['time'] }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium mb-1">{{ __('Location (English)') }}</label><input type="text" name="location_en" value="{{ $settings['location_en'] }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg"></div>
            <div><label class="block text-sm font-medium mb-1">{{ __('Location (Arabic)') }}</label><input type="text" name="location_ar" value="{{ $settings['location_ar'] }}" class="w-full px-3 py-2 border border-gray-700 rounded-lg" dir="rtl"></div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Description (English)') }}</label>
            <textarea name="description_en" rows="3" class="w-full px-3 py-2 border border-gray-700 rounded-lg">{{ $settings['description_en'] }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Description (Arabic)') }}</label>
            <textarea name="description_ar" rows="3" class="w-full px-3 py-2 border border-gray-700 rounded-lg" dir="rtl">{{ $settings['description_ar'] }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Event Logo') }}</label>
            <input type="file" name="logo" accept="image/*" class="w-full px-3 py-2 border border-gray-700 rounded-lg">
            @if ($settings['logo'])
                <img src="{{ asset('storage/' . $settings['logo']) }}" class="h-16 mt-2" alt="Logo">
            @endif
        </div>
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-bold">{{ __('Save Changes') }}</button>
    </form>
</div>
@endsection
