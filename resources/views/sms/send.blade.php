@extends('layouts.dashboard')

@section('title', 'Send SMS - ' . config('app.name'))
@section('page_title', 'Send SMS')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('sms.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Select Patient</label>
                <select id="patientSelect" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                    <option value="">Select patient</option>
                    @foreach($patients as $p)
                    <option value="{{ $p->phone }}" @selected(($preselectedPatient->id ?? null) == $p->id)>{{ $p->name }} ({{ $p->phone ?? 'No phone' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Template</label>
                <select id="templateSelect" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                    <option value="">Select template</option>
                    @foreach($templates as $trigger => $name)
                    <option value="{{ $trigger }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" id="phoneInput" value="{{ old('phone', $preselectedPatient->phone ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Message</label>
                <textarea name="message" id="messageInput" rows="5" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required maxlength="1600">{{ old('message') }}</textarea>
                <p class="text-[10px] text-gray-400 mt-1"><span id="charCount">0</span> / 1600 characters</p>
            </div>
            <input type="hidden" name="patient_id" value="{{ $preselectedPatient->id ?? '' }}">
            <input type="hidden" name="appointment_id" value="{{ $preselectedAppointment->id ?? '' }}">
        </div>
        <div class="mt-6 flex items-center gap-2">
            <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Send SMS</button>
            <a href="{{ route('sms.logs') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">View Logs</a>
        </div>
    </form>
</div>

<script>
const templates = @json(\App\Models\SmsTemplate::where('is_active', true)->pluck('body', 'trigger'));
document.getElementById('patientSelect').addEventListener('change', function() {
    document.getElementById('phoneInput').value = this.value;
});
document.getElementById('templateSelect').addEventListener('change', function() {
    const body = templates[this.value] || '';
    document.getElementById('messageInput').value = body;
    updateCount();
});
const msgInput = document.getElementById('messageInput');
function updateCount() { document.getElementById('charCount').textContent = msgInput.value.length; }
msgInput.addEventListener('input', updateCount);
updateCount();
</script>
@endsection
