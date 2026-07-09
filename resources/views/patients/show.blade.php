@extends('layouts.dashboard')

@section('title', $patient->name . ' - ' . config('app.name'))
@section('page_title', 'Patient Profile')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Patient Info --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-lg">{{ strtoupper(substr($patient->name, 0, 1)) }}</div>
            <div>
                <h3 class="text-base font-bold text-gray-900">{{ $patient->name }}</h3>
                <p class="text-xs text-gray-500">{{ $patient->file_number }}</p>
            </div>
        </div>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Phone</span><span class="text-gray-900">{{ $patient->phone ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Email</span><span class="text-gray-900">{{ $patient->email ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Gender</span><span class="text-gray-900 capitalize">{{ $patient->gender ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">DOB</span><span class="text-gray-900">{{ $patient->date_of_birth?->format('M d, Y') ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Address</span><span class="text-gray-900 text-right">{{ $patient->address ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Type</span><span class="text-gray-900">{{ $patient->new_patient ? 'New Patient' : 'Returning' }}</span></div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
            <a href="{{ route('patients.edit', $patient) }}" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Edit</a>
            <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Book Appointment</a>
        </div>
    </div>

    {{-- Medical History --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Medical & Dental History</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-gray-500 mb-1">Medical History</p><p class="text-gray-900">{{ $patient->medical_history ?? 'None recorded' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Dental History</p><p class="text-gray-900">{{ $patient->dental_history ?? 'None recorded' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Allergies</p><p class="text-gray-900">{{ $patient->allergies ?? 'None recorded' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Conditions</p><p class="text-gray-900">{{ $patient->conditions ?? 'None recorded' }}</p></div>
        </div>

        <h3 class="text-sm font-semibold text-gray-900 mt-6 mb-3">Appointments</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-3 py-2 font-medium">Date</th>
                    <th class="px-3 py-2 font-medium">Service</th>
                    <th class="px-3 py-2 font-medium">Status</th>
                </tr></thead>
                <tbody>
                    @forelse($patient->appointments as $appt)
                    <tr class="border-t border-gray-100">
                        <td class="px-3 py-2 text-xs text-gray-900">{{ $appt->appointment_date?->format('M d, Y') }} {{ optional($appt->start_time)->format('H:i') }}</td>
                        <td class="px-3 py-2 text-xs text-gray-600">{{ $appt->service?->name ?? '-' }}</td>
                        <td class="px-3 py-2"><span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $appt->statusColor() }}-50 text-{{ $appt->statusColor() }}-700 border border-{{ $appt->statusColor() }}-100">{{ $appt->statusLabel() }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-3 py-4 text-center text-gray-400 text-xs">No appointments</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="text-sm font-semibold text-gray-900 mt-6 mb-3">Treatment Records</h3>
        <div class="space-y-2">
            @forelse($patient->clinicalRecords as $record)
            <div class="p-3 rounded-lg border border-gray-100">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-gray-900">{{ $record->service?->name ?? 'General' }} — {{ $record->record_date?->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-500">Dr. {{ $record->doctor?->name ?? '-' }}</p>
                </div>
                <p class="text-xs text-gray-600 mt-1">{{ Str::limit($record->clinical_notes, 120) }}</p>
            </div>
            @empty
            <p class="text-xs text-gray-400">No treatment records</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
