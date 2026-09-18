@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-500">{{ __('Ticket Types') }}</h1>
        <a href="{{ route('admin.ticket-types.create') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-bold transition">{{ __('Add New') }}</a>
    </div>

    <div class="bg-black rounded-xl shadow-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-black">
                <tr>
                    <th class="px-4 py-3 text-left">{{ __('Name (EN)') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Name (AR)') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('Price (EGP)') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('Capacity') }}</th>
                    <th class="px-4 py-3 text-center">{{ __('Active') }}</th>
                    <th class="px-4 py-3 text-center">{{ __('Sort Order') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($ticketTypes as $type)
                <tr>
                    <td class="px-4 py-3">{{ $type->name_en }}</td>
                    <td class="px-4 py-3">{{ $type->name_ar }}</td>
                    <td class="px-4 py-3 text-right">{{ number_format($type->price) }}</td>
                    <td class="px-4 py-3 text-right">{{ $type->capacity }}</td>
                    <td class="px-4 py-3 text-center">{{ $type->is_active ? '✅' : '❌' }}</td>
                    <td class="px-4 py-3 text-center">{{ $type->sort_order }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.ticket-types.edit', $type) }}" class="text-red-600 hover:text-red-800">{{ __('Edit') }}</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
