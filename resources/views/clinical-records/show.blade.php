@extends('layouts.dashboard')

@section('title', 'Treatment Record - ' . config('app.name'))
@section('page_title', 'Treatment Record')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <div class="mb-6">
        <p class="text-xs text-gray-500">{{ $clinicalRecord->record_date?->format('M d, Y') }}</p>
        <h3 class="text-lg font-bold text-gray-900">{{ $clinicalRecord->patient?->name ?? '-' }}</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
        <div><p class="text-xs text-gray-500">Service</p><p class="font-medium text-gray-900">{{ $clinicalRecord->service?->name ?? '-' }}</p></div>
        <div><p class="text-xs text-gray-500">Doctor</p><p class="font-medium text-gray-900">{{ $clinicalRecord->doctor?->name ?? '-' }}</p></div>
        <div><p class="text-xs text-gray-500">Cost</p><p class="font-medium text-gray-900">{{ number_format($clinicalRecord->cost ?? 0) }} TZS</p></div>
        <div><p class="text-xs text-gray-500">Appointment</p><p class="font-medium text-gray-900">{{ $clinicalRecord->appointment_id ? '#' . $clinicalRecord->appointment_id : 'Walk-in' }}</p></div>
    </div>

    <div class="space-y-4 text-sm">
        <div><p class="text-xs text-gray-500 mb-1">Chief Complaint</p><p class="text-gray-700">{{ $clinicalRecord->chief_complaint ?? 'None' }}</p></div>
        <div><p class="text-xs text-gray-500 mb-1">Clinical Notes</p><p class="text-gray-700">{{ $clinicalRecord->clinical_notes ?? 'None' }}</p></div>
        <div><p class="text-xs text-gray-500 mb-1">Diagnosis</p><p class="text-gray-700">{{ $clinicalRecord->diagnosis ?? 'None' }}</p></div>
        <div><p class="text-xs text-gray-500 mb-1">Prescription</p><p class="text-gray-700">{{ $clinicalRecord->prescription ?? 'None' }}</p></div>
        <div><p class="text-xs text-gray-500 mb-1">Treatment Plan</p><p class="text-gray-700">{{ $clinicalRecord->treatment_plan ?? 'None' }}</p></div>
    </div>

    <div class="mt-6 flex items-center gap-2">
        <a href="{{ route('clinical-records.edit', $clinicalRecord) }}" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Edit</a>
        <a href="{{ route('patients.show', $clinicalRecord->patient_id) }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">View Patient</a>
    </div>
</div>
@endsection
