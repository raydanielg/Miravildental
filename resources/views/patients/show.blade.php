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

        {{-- Tabs --}}
        <div class="mt-6 border-b border-gray-100">
            <nav class="flex gap-4" aria-label="Tabs">
                <button onclick="switchTab('appointments')" id="tab-appointments" class="tab-btn px-1 py-2 text-xs font-medium text-emerald-600 border-b-2 border-emerald-600">Appointments</button>
                <button onclick="switchTab('records')" id="tab-records" class="tab-btn px-1 py-2 text-xs font-medium text-gray-500 hover:text-gray-700">Treatment Records</button>
                <button onclick="switchTab('documents')" id="tab-documents" class="tab-btn px-1 py-2 text-xs font-medium text-gray-500 hover:text-gray-700">Documents</button>
            </nav>
        </div>

        <div id="panel-appointments" class="tab-panel mt-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                        <th class="px-3 py-2 font-medium">Date</th>
                        <th class="px-3 py-2 font-medium">Service</th>
                        <th class="px-3 py-2 font-medium">Doctor</th>
                        <th class="px-3 py-2 font-medium">Cost</th>
                        <th class="px-3 py-2 font-medium">Status</th>
                    </tr></thead>
                    <tbody>
                        @forelse($patient->appointments as $appt)
                        <tr class="border-t border-gray-100">
                            <td class="px-3 py-2 text-xs text-gray-900">{{ $appt->appointment_date?->format('M d, Y') }} {{ optional($appt->start_time)->format('H:i') }}</td>
                            <td class="px-3 py-2 text-xs text-gray-600">{{ $appt->service?->name ?? '-' }}</td>
                            <td class="px-3 py-2 text-xs text-gray-600">{{ $appt->doctor?->name ?? '-' }}</td>
                            <td class="px-3 py-2 text-xs text-gray-900">{{ number_format($appt->cost ?? 0) }} TZS</td>
                            <td class="px-3 py-2"><span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $appt->statusColor() }}-50 text-{{ $appt->statusColor() }}-700 border border-{{ $appt->statusColor() }}-100">{{ $appt->statusLabel() }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-3 py-4 text-center text-gray-400 text-xs">No appointments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="panel-records" class="tab-panel mt-4 hidden">
            <div class="space-y-3">
                @forelse($patient->clinicalRecords as $record)
                <div class="p-4 rounded-lg border border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-xs font-bold text-gray-900">{{ $record->service?->name ?? 'General' }} — {{ $record->record_date?->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">Dr. {{ $record->doctor?->name ?? '-' }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-gray-600">
                        <p><span class="font-medium">Complaint:</span> {{ $record->chief_complaint ?? '-' }}</p>
                        <p><span class="font-medium">Diagnosis:</span> {{ $record->diagnosis ?? '-' }}</p>
                        <p><span class="font-medium">Prescription:</span> {{ $record->prescription ?? '-' }}</p>
                        <p><span class="font-medium">Cost:</span> {{ number_format($record->cost ?? 0) }} TZS</p>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">{{ $record->clinical_notes }}</p>
                </div>
                @empty
                <p class="text-xs text-gray-400">No treatment records</p>
                @endforelse
            </div>
        </div>

        <div id="panel-documents" class="tab-panel mt-4 hidden">
            <form method="POST" action="{{ route('patients.documents.store', $patient) }}" enctype="multipart/form-data" class="mb-4 p-3 bg-gray-50 rounded-lg">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-700 mb-1">Type</label>
                        <select name="document_type" class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 outline-none focus:border-emerald-500">
                            <option value="xray">X-Ray</option>
                            <option value="prescription">Prescription</option>
                            <option value="lab">Lab Report</option>
                            <option value="insurance">Insurance</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-medium text-gray-700 mb-1">File</label>
                        <input type="file" name="file" class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5" required>
                    </div>
                    <div>
                        <button type="submit" class="w-full px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Upload</button>
                    </div>
                </div>
            </form>
            <div class="space-y-2">
                @forelse($patient->documents as $doc)
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100">
                    <div>
                        <p class="text-xs font-medium text-gray-900">{{ $doc->title }}</p>
                        <p class="text-[10px] text-gray-500 capitalize">{{ $doc->document_type }} — {{ number_format($doc->file_size / 1024, 1) }} KB</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">View</a>
                        <form method="POST" action="{{ route('patients.documents.destroy', $doc) }}" data-confirm="Delete this document?" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-700">Delete</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400">No documents uploaded</p>
                @endforelse
            </div>
        </div>

        <div class="mt-6 flex items-center gap-2">
            <button onclick="window.print()" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print History
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(name) {
    document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
    document.getElementById('panel-' + name).classList.remove('hidden');
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('text-emerald-600', 'border-b-2', 'border-emerald-600');
        el.classList.add('text-gray-500');
    });
    const active = document.getElementById('tab-' + name);
    active.classList.remove('text-gray-500');
    active.classList.add('text-emerald-600', 'border-b-2', 'border-emerald-600');
}
</script>
@endpush
