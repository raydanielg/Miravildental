@extends('layouts.dashboard')

@section('title', 'Appointments - ' . config('app.name'))
@section('page_title', auth()->user()->isDoctor() ? 'My Schedule' : 'Appointments')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <h3 class="text-sm font-semibold text-gray-900">Appointments</h3>
            <form method="GET" action="{{ route('appointments.index') }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-emerald-500">
            </form>
        </div>
        @if(!auth()->user()->isDoctor())
        <a href="{{ route('appointments.create') }}" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Book Appointment
        </a>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                <th class="px-5 py-2.5 font-medium">Time</th>
                <th class="px-5 py-2.5 font-medium">Patient</th>
                <th class="px-5 py-2.5 font-medium">Service</th>
                <th class="px-5 py-2.5 font-medium">Doctor</th>
                <th class="px-5 py-2.5 font-medium">Room</th>
                <th class="px-5 py-2.5 font-medium">Status</th>
                <th class="px-5 py-2.5 font-medium text-right">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($appointments as $appt)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-2.5 text-xs text-gray-900 font-medium">{{ optional($appt->start_time)->format('H:i') }} - {{ optional($appt->end_time)->format('H:i') }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ $appt->patient?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $appt->service?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $appt->doctor?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $appt->room?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $appt->statusColor() }}-50 text-{{ $appt->statusColor() }}-700 border border-{{ $appt->statusColor() }}-100">{{ $appt->statusLabel() }}</span>
                    </td>
                    <td class="px-5 py-2.5 text-right">
                        <a href="{{ route('appointments.show', $appt) }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700 mr-2">View</a>
                        <a href="{{ route('appointments.edit', $appt) }}" class="text-xs font-medium text-gray-600 hover:text-gray-800">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400 text-xs">No appointments for this date</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($appointments->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $appointments->links() }}</div>
    @endif
</div>
@endsection
