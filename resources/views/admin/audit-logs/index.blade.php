@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-500">{{ __('Audit Log') }}</h1>

    <form method="GET" class="mb-4">
        <input type="text" name="action" placeholder="{{ __('Filter by action...') }}" value="{{ request('action') }}" class="px-4 py-2 border border-gray-700 rounded-lg">
        <button class="px-4 py-2 bg-red-600 text-white rounded-lg">{{ __('Filter') }}</button>
    </form>

    <div class="bg-black rounded-xl shadow-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-black">
                <tr>
                    <th class="px-4 py-3 text-left">{{ __('Admin') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Action') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Related') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Note') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('IP') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Date') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($logs as $log)
                <tr>
                    <td class="px-4 py-3">{{ $log->admin?->name ?? 'System' }}</td>
                    <td class="px-4 py-3">{{ $log->action }}</td>
                    <td class="px-4 py-3">{{ $log->related_type ? class_basename($log->related_type) . ' #' . $log->related_id : '-' }}</td>
                    <td class="px-4 py-3">{{ $log->note ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $log->ip_address }}</td>
                    <td class="px-4 py-3">{{ $log->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">{{ __('No logs found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
</div>
@endsection
