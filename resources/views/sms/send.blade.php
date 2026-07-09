@extends('layouts.dashboard')

@section('title', 'Send SMS - ' . config('app.name'))
@section('page_title', 'Send SMS')

@section('content')
@php
    $totalLogs = \App\Models\SmsLog::count();
    $sentToday = \App\Models\SmsLog::whereDate('created_at', today())->count();
    $pendingCount = \App\Models\SmsLog::where('status', 'pending')->count();
    $failedCount = \App\Models\SmsLog::where('status', 'failed')->count();
@endphp

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Total Sent</p>
        <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($totalLogs) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Sent Today</p>
        <p class="text-xl font-bold text-emerald-600 mt-1">{{ number_format($sentToday) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Pending</p>
        <p class="text-xl font-bold text-amber-500 mt-1">{{ number_format($pendingCount) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Failed</p>
        <p class="text-xl font-bold text-red-500 mt-1">{{ number_format($failedCount) }}</p>
    </div>
</div>

{{-- Main Cards --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Send SMS Card --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Compose Message</h3>
        </div>
        <form id="smsSendForm" method="POST" action="{{ route('sms.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Select Patient</label>
                    <select id="patientSelect" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                        <option value="">Select patient</option>
                        @foreach($patients as $p)
                        <option value="{{ $p->id }}" data-phone="{{ $p->phone }}" @selected(($preselectedPatient->id ?? null) == $p->id)>{{ $p->name }} ({{ $p->phone ?? 'No phone' }})</option>
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
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-[10px] text-gray-400"><span id="charCount">0</span> / 1600 characters</p>
                        <p class="text-[10px] text-gray-400"><span id="smsParts">1</span> SMS part(s)</p>
                    </div>
                </div>
                <input type="hidden" name="patient_id" id="patientIdInput" value="{{ $preselectedPatient->id ?? '' }}">
                <input type="hidden" name="appointment_id" value="{{ $preselectedAppointment->id ?? '' }}">
            </div>
            <div class="mt-6 flex items-center gap-2">
                <button type="submit" id="sendSmsBtn" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span id="btnText">Send SMS</span>
                </button>
                <a href="{{ route('sms.campaign') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Campaign</a>
                <a href="{{ route('sms.logs') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">View Logs</a>
            </div>
        </form>
    </div>

    {{-- Preview Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Preview</h3>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs font-bold">M</div>
                <div>
                    <p class="text-xs font-semibold text-gray-900">{{ \App\Models\ClinicSetting::current()?->sender_id ?? 'MIRAVIL' }}</p>
                    <p class="text-[10px] text-gray-500" id="previewPhone">{{ $preselectedPatient->phone ?? '+255 XXX XXX XXX' }}</p>
                </div>
                <span class="ml-auto text-[10px] text-gray-400">Now</span>
            </div>
            <div class="bg-white rounded-lg rounded-tl-none p-3 shadow-sm">
                <p class="text-sm text-gray-800 whitespace-pre-wrap" id="previewMessage">Your message preview will appear here...</p>
            </div>
        </div>

        <div class="mt-5 space-y-3">
            <div class="p-3 rounded-lg bg-emerald-50/50 border border-emerald-100">
                <p class="text-[10px] text-emerald-700 font-medium mb-1">Sender ID</p>
                <p class="text-xs text-gray-900">{{ \App\Models\ClinicSetting::current()?->sender_id ?? 'MIRAVIL' }}</p>
            </div>
            <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                <p class="text-[10px] text-gray-500 font-medium mb-1">Available Placeholders</p>
                <div class="flex flex-wrap gap-1.5">
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600">@{{ name }}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600">@{{ date }}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600">@{{ time }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Logs --}}
<div class="mt-6 bg-white rounded-xl border border-gray-100 shadow-sm p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-900">Recent Messages</h3>
        <a href="{{ route('sms.logs') }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table id="recentLogsTable" class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                <th class="px-3 py-2 font-medium">Time</th>
                <th class="px-3 py-2 font-medium">Phone</th>
                <th class="px-3 py-2 font-medium">Message</th>
                <th class="px-3 py-2 font-medium">Status</th>
            </tr></thead>
            <tbody>
                @forelse(\App\Models\SmsLog::latest()->limit(5)->get() as $log)
                <tr class="border-t border-gray-100">
                    <td class="px-3 py-2 text-xs text-gray-900">{{ $log->created_at->format('M d, H:i') }}</td>
                    <td class="px-3 py-2 text-xs text-gray-900">{{ $log->phone }}</td>
                    <td class="px-3 py-2 text-xs text-gray-600 truncate max-w-xs">{{ $log->message }}</td>
                    <td class="px-3 py-2">
                        @php $color = match($log->status) { 'sent' => 'emerald', 'delivered' => 'emerald', 'pending' => 'amber', 'failed' => 'red', default => 'gray' }; @endphp
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst($log->status) }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-xs">No recent messages</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
const templates = @json(\App\Models\SmsTemplate::where('is_active', true)->pluck('body', 'trigger'));
const patients = @json($patients->mapWithKeys(fn($p) => [$p->id => ['name' => $p->name, 'phone' => $p->phone]]));
const patientSelect = document.getElementById('patientSelect');
const phoneInput = document.getElementById('phoneInput');
const patientIdInput = document.getElementById('patientIdInput');
const messageInput = document.getElementById('messageInput');
const previewPhone = document.getElementById('previewPhone');
const previewMessage = document.getElementById('previewMessage');

function updatePreview() {
    let text = messageInput.value || 'Your message preview will appear here...';
    const patient = patients[patientSelect.value] || { name: 'Patient', phone: phoneInput.value || '+255 XXX XXX XXX' };
    text = text.replace(new RegExp('\\{\\{name\\}\\}', 'g'), patient.name)
               .replace(new RegExp('\\{\\{date\\}\\}', 'g'), new Date().toLocaleDateString())
               .replace(new RegExp('\\{\\{time\\}\\}', 'g'), new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}));
    previewMessage.textContent = text;
    previewPhone.textContent = phoneInput.value || patient.phone || '+255 XXX XXX XXX';
    const len = messageInput.value.length;
    document.getElementById('charCount').textContent = len;
    document.getElementById('smsParts').textContent = len === 0 ? 0 : (len <= 160 ? 1 : Math.ceil(len / 153));
}

patientSelect.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    phoneInput.value = selected.dataset.phone || '';
    patientIdInput.value = this.value;
    updatePreview();
});

document.getElementById('templateSelect').addEventListener('change', function() {
    messageInput.value = templates[this.value] || '';
    updatePreview();
});

messageInput.addEventListener('input', updatePreview);
phoneInput.addEventListener('input', updatePreview);
updatePreview();

// AJAX form submission
const smsForm = document.getElementById('smsSendForm');
const sendBtn = document.getElementById('sendSmsBtn');
const btnText = document.getElementById('btnText');

document.getElementById('smsSendForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(smsForm);
    const csrf = formData.get('_token');

    sendBtn.disabled = true;
    sendBtn.classList.add('opacity-75', 'cursor-not-allowed');
    btnText.textContent = 'Inatuma...';

    fetch(smsForm.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: data.message,
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
            });
            // Prepend new log to recent table
            const tbody = document.querySelector('#recentLogsTable tbody');
            if (tbody && data.log) {
                const row = document.createElement('tr');
                row.className = 'border-t border-gray-100';
                row.innerHTML = `
                    <td class="px-3 py-2 text-xs text-gray-900">${data.log.time}</td>
                    <td class="px-3 py-2 text-xs text-gray-900">${data.log.phone}</td>
                    <td class="px-3 py-2 text-xs text-gray-600 truncate max-w-xs">${data.log.message}</td>
                    <td class="px-3 py-2"><span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">${data.log.status}</span></td>
                `;
                tbody.insertBefore(row, tbody.firstChild);
            }
            // Reset message but keep phone/patient
            messageInput.value = '';
            updatePreview();
        } else {
            throw new Error(data.message || 'Tumeshindwa kutuma SMS.');
        }
    })
    .catch(error => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: error.message || 'Hitilafu imetokea.',
            showConfirmButton: false,
            timer: 4000,
        });
    })
    .finally(() => {
        sendBtn.disabled = false;
        sendBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        btnText.textContent = 'Send SMS';
    });
});
</script>
@endsection
