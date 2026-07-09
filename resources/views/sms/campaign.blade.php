@extends('layouts.dashboard')

@section('title', 'SMS Campaign - ' . config('app.name'))
@section('page_title', 'SMS Campaign')

@section('content')
@php
    use App\Models\Patient;
    $counts = [
        'all' => Patient::whereNotNull('phone')->count(),
        'new' => Patient::where('new_patient', true)->whereNotNull('phone')->count(),
        'returning' => Patient::where('new_patient', false)->whereNotNull('phone')->count(),
        'today' => Patient::whereHas('appointments', fn($q) => $q->today())->whereNotNull('phone')->count(),
        'upcoming' => Patient::whereHas('appointments', fn($q) => $q->upcoming())->whereNotNull('phone')->count(),
    ];
    $totalLogs = \App\Models\SmsLog::where('trigger', 'campaign')->count();
    $lastCampaign = \App\Models\SmsLog::where('trigger', 'campaign')->latest()->first();
@endphp

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Total Campaigns</p>
        <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($totalLogs) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Reachable Patients</p>
        <p class="text-xl font-bold text-emerald-600 mt-1">{{ number_format($counts['all']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">New Patients</p>
        <p class="text-xl font-bold text-gold-500 mt-1">{{ number_format($counts['new']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Last Campaign</p>
        <p class="text-sm font-bold text-gray-900 mt-1">{{ $lastCampaign ? $lastCampaign->created_at->diffForHumans() : 'Never' }}</p>
    </div>
</div>

{{-- Main Cards --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Campaign Compose Card --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Compose Campaign</h3>
        </div>
        <form method="POST" action="{{ route('sms.campaign.send') }}">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Target Group</label>
                        <select name="group" id="groupSelect" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                            @foreach($groups as $key => $label)
                            <option value="{{ $key }}" data-count="{{ $counts[$key] ?? 0 }}">{{ $label }}</option>
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
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" id="messageInput" rows="5" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required maxlength="1600"></textarea>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-[10px] text-gray-400"><span id="charCount">0</span> / 1600 characters</p>
                        <p class="text-[10px] text-gray-400"><span id="smsParts">1</span> SMS part(s)</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center gap-2">
                <button type="submit" id="sendCampaignBtn" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span id="btnText">Send Campaign</span>
                </button>
                <a href="{{ route('sms.send') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Single SMS</a>
                <a href="{{ route('sms.logs') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">View Logs</a>
            </div>
        </form>
    </div>

    {{-- Campaign Preview Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Campaign Preview</h3>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 mb-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs font-bold">M</div>
                <div>
                    <p class="text-xs font-semibold text-gray-900">{{ \App\Models\ClinicSetting::current()?->sender_id ?? 'MIRAVIL' }}</p>
                    <p class="text-[10px] text-gray-500">Campaign Broadcast</p>
                </div>
                <span class="ml-auto text-[10px] text-gray-400">Now</span>
            </div>
            <div class="bg-white rounded-lg rounded-tl-none p-3 shadow-sm">
                <p class="text-sm text-gray-800 whitespace-pre-wrap" id="previewMessage">Your campaign message preview will appear here...</p>
            </div>
        </div>

        <div class="space-y-3">
            <div class="p-3 rounded-lg bg-emerald-50/50 border border-emerald-100">
                <p class="text-[10px] text-emerald-700 font-medium mb-1">Estimated Recipients</p>
                <p class="text-2xl font-bold text-gray-900" id="recipientCount">{{ number_format($counts['all']) }}</p>
            </div>
            <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                <p class="text-[10px] text-gray-500 font-medium mb-1">Sender ID</p>
                <p class="text-xs text-gray-900">{{ \App\Models\ClinicSetting::current()?->sender_id ?? 'MIRAVIL' }}</p>
            </div>
            <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                <p class="text-[10px] text-gray-500 font-medium mb-1">Available Placeholders</p>
                <div class="flex flex-wrap gap-1.5">
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600">@{{ name }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Group Cards --}}
<div class="mt-6 grid grid-cols-2 lg:grid-cols-5 gap-3">
    @foreach($groups as $key => $label)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center group cursor-pointer hover:border-emerald-300 transition-colors" onclick="selectGroup('{{ $key }}')">
        <p class="text-[10px] text-gray-500 mb-1">{{ $label }}</p>
        <p class="text-xl font-bold text-gray-900">{{ number_format($counts[$key] ?? 0) }}</p>
    </div>
    @endforeach
</div>

<script>
const templates = @json(\App\Models\SmsTemplate::where('is_active', true)->pluck('body', 'trigger'));
const counts = @json($counts);
const messageInput = document.getElementById('messageInput');
const previewMessage = document.getElementById('previewMessage');
const groupSelect = document.getElementById('groupSelect');
const recipientCount = document.getElementById('recipientCount');

function updatePreview() {
    let text = messageInput.value || 'Your campaign message preview will appear here...';
    text = text.replace(new RegExp('\\{\\{name\\}\\}', 'g'), 'Patient Name');
    previewMessage.textContent = text;
    const len = messageInput.value.length;
    document.getElementById('charCount').textContent = len;
    document.getElementById('smsParts').textContent = len === 0 ? 0 : (len <= 160 ? 1 : Math.ceil(len / 153));
}

function selectGroup(key) {
    groupSelect.value = key;
    updateRecipientCount();
}

function updateRecipientCount() {
    recipientCount.textContent = counts[groupSelect.value].toLocaleString();
}

document.getElementById('templateSelect').addEventListener('change', function() {
    messageInput.value = templates[this.value] || '';
    updatePreview();
});

messageInput.addEventListener('input', updatePreview);
groupSelect.addEventListener('change', updateRecipientCount);
updatePreview();
updateRecipientCount();

// AJAX campaign submission
const campaignForm = document.querySelector('form[action="{{ route('sms.campaign.send') }}"]');
const sendBtn = document.getElementById('sendCampaignBtn');
const btnText = document.getElementById('btnText');

campaignForm.addEventListener('submit', function(e) {
    e.preventDefault();
    if (!confirm('Una uhakika kutuma campaign kwa kundi lililochaguliwa?')) return;

    const formData = new FormData(campaignForm);
    const csrf = formData.get('_token');

    sendBtn.disabled = true;
    sendBtn.classList.add('opacity-75', 'cursor-not-allowed');
    btnText.textContent = 'Inatuma...';

    fetch(campaignForm.action, {
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
            messageInput.value = '';
            updatePreview();
        } else {
            throw new Error(data.message || 'Tumeshindwa kutuma campaign.');
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
        btnText.textContent = 'Send Campaign';
    });
});
</script>
@endsection
