@extends('layouts.dashboard')

@section('title', 'Treatment Record - ' . config('app.name'))
@section('page_title', 'Treatment Record')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    {{-- Patient Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Patient Details</h3>
        </div>
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center text-2xl font-bold mb-3">
                {{ strtoupper(substr($clinicalRecord->patient?->name ?? 'U', 0, 1)) }}
            </div>
            <h4 class="text-base font-bold text-gray-900">{{ $clinicalRecord->patient?->name ?? '-' }}</h4>
            <p class="text-xs text-gray-500">{{ $clinicalRecord->patient?->phone ?? '-' }}</p>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <span class="text-gray-500">File No</span>
                <span class="font-medium text-gray-900">{{ $clinicalRecord->patient?->file_number ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <span class="text-gray-500">Email</span>
                <span class="font-medium text-gray-900">{{ $clinicalRecord->patient?->email ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <span class="text-gray-500">Gender</span>
                <span class="font-medium text-gray-900">{{ ucfirst($clinicalRecord->patient?->gender ?? '-') }}</span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <span class="text-gray-500">Date of Birth</span>
                <span class="font-medium text-gray-900">{{ $clinicalRecord->patient?->date_of_birth?->format('M d, Y') ?? '-' }}</span>
            </div>
        </div>
        <div class="mt-5">
            <a href="{{ route('patients.show', $clinicalRecord->patient_id) }}" class="block w-full text-center px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">View Full Patient Profile</a>
        </div>
    </div>

    {{-- Record Card --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between mb-5">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Treatment Record</h3>
                    <p class="text-[10px] text-gray-500">{{ $clinicalRecord->record_date?->format('M d, Y') }} &bull; Dr. {{ $clinicalRecord->doctor?->name ?? '-' }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Cost</p>
                <p class="text-lg font-bold text-emerald-600">{{ number_format($clinicalRecord->cost ?? 0) }} TZS</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
            <div class="p-3 rounded-lg bg-gray-50">
                <p class="text-[10px] text-gray-500 uppercase">Service</p>
                <p class="text-sm font-semibold text-gray-900">{{ $clinicalRecord->service?->name ?? '-' }}</p>
            </div>
            <div class="p-3 rounded-lg bg-gray-50">
                <p class="text-[10px] text-gray-500 uppercase">Doctor</p>
                <p class="text-sm font-semibold text-gray-900">{{ $clinicalRecord->doctor?->name ?? '-' }}</p>
            </div>
            <div class="p-3 rounded-lg bg-gray-50">
                <p class="text-[10px] text-gray-500 uppercase">Appointment</p>
                <p class="text-sm font-semibold text-gray-900">{{ $clinicalRecord->appointment_id ? '#' . $clinicalRecord->appointment_id : 'Walk-in' }}</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="p-4 rounded-lg border border-gray-100 bg-gray-50/50">
                <p class="text-xs font-medium text-gray-700 mb-1">Chief Complaint</p>
                <p class="text-sm text-gray-700">{{ $clinicalRecord->chief_complaint ?? 'None' }}</p>
            </div>
            <div class="p-4 rounded-lg border border-gray-100 bg-gray-50/50">
                <p class="text-xs font-medium text-gray-700 mb-1">Clinical Notes</p>
                <p class="text-sm text-gray-700">{{ $clinicalRecord->clinical_notes ?? 'None' }}</p>
            </div>
            <div class="p-4 rounded-lg border border-gray-100 bg-gray-50/50">
                <p class="text-xs font-medium text-gray-700 mb-1">Diagnosis</p>
                <p class="text-sm text-gray-700">{{ $clinicalRecord->diagnosis ?? 'None' }}</p>
            </div>
            <div class="p-4 rounded-lg border border-gray-100 bg-gray-50/50">
                <p class="text-xs font-medium text-gray-700 mb-1">Prescription</p>
                <p class="text-sm text-gray-700">{{ $clinicalRecord->prescription ?? 'None' }}</p>
            </div>
            <div class="p-4 rounded-lg border border-gray-100 bg-gray-50/50">
                <p class="text-xs font-medium text-gray-700 mb-1">Treatment Plan</p>
                <p class="text-sm text-gray-700">{{ $clinicalRecord->treatment_plan ?? 'None' }}</p>
            </div>
            @if($clinicalRecord->notes)
            <div class="p-4 rounded-lg border border-gray-100 bg-gray-50/50">
                <p class="text-xs font-medium text-gray-700 mb-1">Additional Notes</p>
                <p class="text-sm text-gray-700">{{ $clinicalRecord->notes }}</p>
            </div>
            @endif
        </div>

        <div class="mt-6 flex items-center gap-2">
            <a href="{{ route('clinical-records.edit', $clinicalRecord) }}" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.432-4.364l-6.364-6.364a2.015 2.015 0 00-2.828 0l-1.768 1.768a2.015 2.015 0 000 2.828l6.364 6.364M16 7h6m-3-3v6"/></svg>
                Edit Record
            </a>
            <button type="button" onclick="window.history.back()" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Back</button>
        </div>
    </div>
</div>
@endsection
