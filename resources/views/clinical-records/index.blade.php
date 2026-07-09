@extends('layouts.dashboard')

@section('title', auth()->user()->isDoctor() ? 'Treatment Records - ' . config('app.name') : 'Clinical Records - ' . config('app.name'))
@section('page_title', auth()->user()->isDoctor() ? 'Treatment Records' : 'Clinical Records')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="text-sm font-semibold text-gray-900">Records</h3>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('clinical-records.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search patient..." class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-emerald-500">
                <button type="submit" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>
            <a href="{{ route('clinical-records.create') }}" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Record
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                <th class="px-5 py-2.5 font-medium">Date</th>
                <th class="px-5 py-2.5 font-medium">Patient</th>
                <th class="px-5 py-2.5 font-medium">Service</th>
                <th class="px-5 py-2.5 font-medium">Doctor</th>
                <th class="px-5 py-2.5 font-medium">Cost</th>
                <th class="px-5 py-2.5 font-medium text-right">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($records as $record)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ $record->record_date?->format('M d, Y') }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ $record->patient?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $record->service?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $record->doctor?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ number_format($record->cost ?? 0) }} TZS</td>
                    <td class="px-5 py-2.5 text-right">
                        <a href="{{ route('clinical-records.show', $record) }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700 mr-2">View</a>
                        <a href="{{ route('clinical-records.edit', $record) }}" class="text-xs font-medium text-gray-600 hover:text-gray-800">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-xs">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $records->links() }}</div>
    @endif
</div>
@endsection
