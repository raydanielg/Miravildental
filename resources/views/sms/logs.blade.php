@extends('layouts.dashboard')

@section('title', 'SMS Logs - ' . config('app.name'))
@section('page_title', 'SMS Logs & Delivery')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-900">Sent Messages</h3>
        <a href="{{ route('sms.send') }}" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Send SMS</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                <th class="px-5 py-2.5 font-medium">Time</th>
                <th class="px-5 py-2.5 font-medium">Recipient</th>
                <th class="px-5 py-2.5 font-medium">Message</th>
                <th class="px-5 py-2.5 font-medium">Trigger</th>
                <th class="px-5 py-2.5 font-medium">Status</th>
                <th class="px-5 py-2.5 font-medium">Sent By</th>
            </tr></thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ $log->created_at->format('M d, H:i') }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ $log->phone }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600 max-w-xs truncate">{{ $log->message }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600 capitalize">{{ $log->trigger ?? 'manual' }}</td>
                    <td class="px-5 py-2.5">
                        @php $color = match($log->status) { 'sent' => 'emerald', 'delivered' => 'emerald', 'pending' => 'amber', 'failed' => 'red', default => 'gray' }; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst($log->status) }}</span>
                    </td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $log->sender?->name ?? 'System' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-xs">No SMS logs yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
