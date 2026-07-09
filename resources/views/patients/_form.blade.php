@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-1">Full Name</label>
        <input type="text" name="name" value="{{ old('name', $patient->name ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $patient->phone ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $patient->email ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
        <select name="gender" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            <option value="">Select</option>
            <option value="male" @selected(old('gender', $patient->gender ?? '') == 'male')>Male</option>
            <option value="female" @selected(old('gender', $patient->gender ?? '') == 'female')>Female</option>
            <option value="other" @selected(old('gender', $patient->gender ?? '') == 'other')>Other</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Date of Birth</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', isset($patient) ? optional($patient->date_of_birth)->format('Y-m-d') : '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
    </div>
    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-1">Address</label>
        <textarea name="address" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">{{ old('address', $patient->address ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Emergency Contact Name</label>
        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Emergency Contact Phone</label>
        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Medical History</label>
        <textarea name="medical_history" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">{{ old('medical_history', $patient->medical_history ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Dental History</label>
        <textarea name="dental_history" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">{{ old('dental_history', $patient->dental_history ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Allergies</label>
        <textarea name="allergies" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">{{ old('allergies', $patient->allergies ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Conditions</label>
        <textarea name="conditions" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">{{ old('conditions', $patient->conditions ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
        <textarea name="notes" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">{{ old('notes', $patient->notes ?? '') }}</textarea>
    </div>
    @if(isset($patient))
    <div class="md:col-span-2 flex items-center gap-2">
        <input type="checkbox" name="new_patient" value="1" id="new_patient" @checked(old('new_patient', $patient->new_patient ?? false)) class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
        <label for="new_patient" class="text-xs text-gray-700">Mark as new patient (eligible for complimentary oral health consultation)</label>
    </div>
    @endif
</div>
<div class="mt-6 flex items-center gap-2">
    <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Patient</button>
    <a href="{{ route('patients.index') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
</div>
