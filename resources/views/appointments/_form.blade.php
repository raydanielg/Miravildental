@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Patient</label>
        <select name="patient_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            <option value="">Select patient</option>
            @foreach($patients as $p)
            <option value="{{ $p->id }}" @selected(old('patient_id', $appointment->patient_id ?? ($preselectedPatient->id ?? '')) == $p->id)>{{ $p->name }} ({{ $p->file_number }})</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Doctor</label>
        <select name="doctor_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            <option value="">Select doctor</option>
            @foreach($doctors as $d)
            <option value="{{ $d->id }}" @selected(old('doctor_id', $appointment->doctor_id ?? '') == $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Service</label>
        <select name="service_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            <option value="">Select service</option>
            @foreach($services as $s)
            <option value="{{ $s->id }}" @selected(old('service_id', $appointment->service_id ?? '') == $s->id)>{{ $s->name }} ({{ number_format($s->price) }} TZS, {{ $s->duration_minutes }}m)</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Room / Chair</label>
        <select name="room_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            <option value="">Select room</option>
            @foreach($rooms as $r)
            <option value="{{ $r->id }}" @selected(old('room_id', $appointment->room_id ?? '') == $r->id)>{{ $r->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
        <input type="date" name="appointment_date" value="{{ old('appointment_date', isset($appointment) ? $appointment->appointment_date->format('Y-m-d') : today()->toDateString()) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Start Time</label>
        <input type="time" name="start_time" value="{{ old('start_time', isset($appointment) && $appointment->start_time ? $appointment->start_time->format('H:i') : '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
        <select name="status" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            @foreach($statuses as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $appointment->status ?? 'booked') == $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
        <textarea name="notes" rows="3" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('notes', $appointment->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-6 flex items-center gap-2">
    <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Appointment</button>
    <a href="{{ route('appointments.index') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
</div>
