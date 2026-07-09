@extends('layouts.dashboard')

@section('title', 'Appointment - ' . config('app.name'))
@section('page_title', 'Appointment Details')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-xs text-gray-500">{{ $appointment->appointment_date?->format('l, M d, Y') }}</p>
            <h3 class="text-lg font-bold text-gray-900">{{ optional($appointment->start_time)->format('H:i') }} - {{ optional($appointment->end_time)->format('H:i') }}</h3>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-{{ $appointment->statusColor() }}-100 text-{{ $appointment->statusColor() }}-700 border border-{{ $appointment->statusColor() }}-200">{{ $appointment->statusLabel() }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
        <div><p class="text-xs text-gray-500">Patient</p><p class="font-medium text-gray-900">{{ $appointment->patient?->name ?? '-' }}</p></div>
        <div><p class="text-xs text-gray-500">Doctor</p><p class="font-medium text-gray-900">{{ $appointment->doctor?->name ?? '-' }}</p></div>
        <div><p class="text-xs text-gray-500">Service</p><p class="font-medium text-gray-900">{{ $appointment->service?->name ?? '-' }}</p></div>
        <div><p class="text-xs text-gray-500">Room</p><p class="font-medium text-gray-900">{{ $appointment->room?->name ?? '-' }}</p></div>
        <div><p class="text-xs text-gray-500">Cost</p><p class="font-medium text-gray-900">{{ number_format($appointment->cost ?? 0) }} TZS</p></div>
        <div><p class="text-xs text-gray-500">Booked By</p><p class="font-medium text-gray-900">{{ $appointment->bookedBy?->name ?? '-' }}</p></div>
    </div>

    @if($appointment->notes)
    <div class="mb-6"><p class="text-xs text-gray-500 mb-1">Notes</p><p class="text-sm text-gray-700">{{ $appointment->notes }}</p></div>
    @endif

    <div class="flex flex-wrap items-center gap-2">
        @if(auth()->user()->isAdmin() || auth()->user()->isReception())
        <a href="{{ route('appointments.edit', $appointment) }}" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Edit</a>
        @endif
        @if(auth()->user()->isDoctor() || auth()->user()->isAdmin())
            @if(in_array($appointment->status, [\App\Models\Appointment::STATUS_ARRIVED, \App\Models\Appointment::STATUS_IN_TREATMENT]))
            <a href="{{ route('clinical-records.create-from-appointment', $appointment) }}" class="px-4 py-2 text-xs font-medium bg-gold-500 text-white rounded-lg hover:bg-gold-600">Add Treatment Record</a>
            @endif
        @endif
        <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" data-confirm="Delete this appointment?" class="inline">
            @csrf @method('DELETE')
            <button type="submit" class="px-4 py-2 text-xs font-medium border border-red-300 text-red-600 rounded-lg hover:bg-red-50">Delete</button>
        </form>
    </div>

    @if(auth()->user()->isAdmin() || auth()->user()->isReception())
    <div class="mt-6 pt-6 border-t border-gray-100">
        <p class="text-xs font-medium text-gray-700 mb-2">Quick Status Change</p>
        <div class="flex flex-wrap gap-2">
            @foreach(\App\Models\Appointment::STATUS_LABELS as $value => $label)
            <form method="POST" action="{{ route('appointments.status', $appointment) }}" class="inline">
                @csrf
                <input type="hidden" name="status" value="{{ $value }}">
                <button type="submit" class="px-2.5 py-1 text-[10px] font-medium border rounded-lg {{ $appointment->status === $value ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">{{ $label }}</button>
            </form>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
