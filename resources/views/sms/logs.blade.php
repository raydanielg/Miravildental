@extends('layouts.dashboard')

@section('title', 'SMS Logs - ' . config('app.name'))
@section('page_title', 'SMS Logs & Delivery')

@section('content')
@php
    $total = \App\Models\SmsLog::count();
    $sent = \App\Models\SmsLog::where('status', 'sent')->count();
    $failed = \App\Models\SmsLog::where('status', 'failed')->count();
    $pending = \App\Models\SmsLog::where('status', 'pending')->count();
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Total Messages</p>
        <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($total) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Sent</p>
        <p class="text-xl font-bold text-emerald-600 mt-1">{{ number_format($sent) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Pending</p>
        <p class="text-xl font-bold text-amber-500 mt-1">{{ number_format($pending) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Failed</p>
        <p class="text-xl font-bold text-red-500 mt-1">{{ number_format($failed) }}</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="text-sm font-semibold text-gray-900">Sent Messages</h3>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('sms.logs') }}" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 outline-none focus:border-emerald-500">
                    <option value="">All Status</option>
                    <option value="sent" @selected(request('status') == 'sent')>Sent</option>
                    <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                    <option value="failed" @selected(request('status') == 'failed')>Failed</option>
                </select>
            </form>
            <a href="{{ route('sms.send') }}" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Send SMS</a>
        </div>
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
