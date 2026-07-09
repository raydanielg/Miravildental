@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Patient</label>
        <select name="patient_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            <option value="">Select patient</option>
            @foreach($patients as $p)
            <option value="{{ $p->id }}" @selected(old('patient_id', $clinicalRecord->patient_id ?? ($appointment?->patient_id ?? '')) == $p->id)>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Service</label>
        <select name="service_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            <option value="">Select service</option>
            @foreach($services as $s)
            <option value="{{ $s->id }}" @selected(old('service_id', $clinicalRecord->service_id ?? ($appointment?->service_id ?? '')) == $s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Record Date</label>
        <input type="date" name="record_date" value="{{ old('record_date', isset($clinicalRecord) ? $clinicalRecord->record_date->format('Y-m-d') : ($appointment?->appointment_date?->format('Y-m-d') ?? today()->toDateString())) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Cost (TZS)</label>
        <input type="number" name="cost" value="{{ old('cost', $clinicalRecord->cost ?? ($appointment?->cost ?? 0)) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
    </div>
    @if($appointment)
    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
    @else
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Linked Appointment (optional)</label>
        <input type="text" disabled value="{{ $clinicalRecord->appointment_id ? '#' . $clinicalRecord->appointment_id : 'None' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 text-gray-500">
    </div>
    @endif
    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-1">Chief Complaint</label>
        <textarea name="chief_complaint" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('chief_complaint', $clinicalRecord->chief_complaint ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-1">Clinical Notes</label>
        <textarea name="clinical_notes" rows="3" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('clinical_notes', $clinicalRecord->clinical_notes ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Diagnosis</label>
        <textarea name="diagnosis" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('diagnosis', $clinicalRecord->diagnosis ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Prescription</label>
        <textarea name="prescription" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('prescription', $clinicalRecord->prescription ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-1">Treatment Plan</label>
        <textarea name="treatment_plan" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('treatment_plan', $clinicalRecord->treatment_plan ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
        <textarea name="notes" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('notes', $clinicalRecord->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-6 flex items-center gap-2">
    <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Record</button>
    <a href="{{ route('clinical-records.index') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
</div>
