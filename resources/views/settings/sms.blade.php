@extends('layouts.dashboard')

@section('title', 'SMS Configuration - ' . config('app.name'))
@section('page_title', 'SMS Configuration')

@section('content')
@include('settings._nav', ['active' => 'sms'])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    {{-- Main SMS Settings --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">SMS & Reminder Settings</h3>
        </div>
        <form id="smsForm" method="POST" action="{{ route('settings.sms.update') }}">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Sender ID</label>
                <input type="text" name="sender_id" value="{{ old('sender_id', $settings->sender_id) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">SMS Provider</label>
                <select name="sms_provider" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                    <option value="" @selected(old('sms_provider', $settings->sms_provider) == '')>Select provider</option>
                    <option value="nextsms" @selected(old('sms_provider', $settings->sms_provider) == 'nextsms')>NextSMS</option>
                    <option value="mobile_sms" @selected(old('sms_provider', $settings->sms_provider) == 'mobile_sms')>Mobile SMS (messaging-service.co.tz)</option>
                    <option value="twilio" @selected(old('sms_provider', $settings->sms_provider) == 'twilio')>Twilio</option>
                    <option value="africastalking" @selected(old('sms_provider', $settings->sms_provider) == 'africastalking')>Africa's Talking</option>
                    <option value="beem" @selected(old('sms_provider', $settings->sms_provider) == 'beem')>Beem</option>
                    <option value="custom" @selected(old('sms_provider', $settings->sms_provider) == 'custom')>Custom API</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">API Username</label>
                <input type="text" name="sms_api_username" value="{{ old('sms_api_username', $settings->sms_api_username) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">API Password / Token</label>
                <input type="password" name="sms_api_password" value="{{ old('sms_api_password', $settings->sms_api_password) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">API Key</label>
                <input type="text" name="sms_api_key" value="{{ old('sms_api_key', $settings->sms_api_key) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">API Secret</label>
                <input type="password" name="sms_api_secret" value="{{ old('sms_api_secret', $settings->sms_api_secret) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">API Endpoint URL</label>
                <input type="url" name="sms_api_url" value="{{ old('sms_api_url', $settings->sms_api_url) }}" placeholder="https://api.example.com/v1/sms/send" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Default Appointment Duration (min)</label>
                <input type="number" name="default_appointment_duration" value="{{ old('default_appointment_duration', $settings->default_appointment_duration) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">24h Reminder (hours before)</label>
                <input type="number" name="reminder_24h_before" value="{{ old('reminder_24h_before', $settings->reminder_24h_before) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">2h Reminder (hours before)</label>
                <input type="number" name="reminder_2h_before" value="{{ old('reminder_2h_before', $settings->reminder_2h_before) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Recall After (days)</label>
                <input type="number" name="recall_after_days" value="{{ old('recall_after_days', $settings->recall_after_days) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" id="smsSaveBtn" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span id="smsBtnText">Save SMS Settings</span>
            </button>
        </div>
    </form>
</div>

{{-- SMS Test Card --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center gap-2 mb-5">
        <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center">
            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900">Test SMS</h3>
    </div>
    <form id="smsTestForm" method="POST" action="{{ route('sms.test') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('sms_test_phone', $settings->sms_test_phone) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Message</label>
                <input type="text" name="message" value="Habari, ujumbe wa kupima kutoka {{ config('app.name') }}." class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" id="smsTestBtn" class="px-4 py-2 text-xs font-medium bg-sky-600 text-white rounded-lg hover:bg-sky-700 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                <span id="smsTestBtnText">Send Test SMS</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('smsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('smsSaveBtn');
    const txt = document.getElementById('smsBtnText');
    const original = txt.textContent;
    btn.disabled = true;
    txt.textContent = 'Inahifadhi...';

    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
        },
        body: new FormData(this),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        } else {
            throw new Error(data.message || 'Imeshindwa.');
        }
    })
    .catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 4000 }))
    .finally(() => { btn.disabled = false; txt.textContent = original; });
});

document.getElementById('smsTestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('smsTestBtn');
    const txt = document.getElementById('smsTestBtnText');
    const original = txt.textContent;
    btn.disabled = true;
    txt.textContent = 'Inatuma...';

    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
        },
        body: new FormData(this),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        } else {
            throw new Error(data.message || 'Imeshindwa.');
        }
    })
    .catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 4000 }))
    .finally(() => { btn.disabled = false; txt.textContent = original; });
});
</script>
@endpush
