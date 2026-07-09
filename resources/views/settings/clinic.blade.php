@extends('layouts.dashboard')

@section('title', 'Clinic Profile - ' . config('app.name'))
@section('page_title', 'Clinic Profile')

@section('content')
@include('settings._nav', ['active' => 'clinic'])

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
    {{-- View Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Clinic Overview</h3>
        </div>
        <div class="flex flex-col items-center text-center mb-6">
            @if($settings->logo_path)
                <img src="{{ asset('storage/' . $settings->logo_path) }}" class="w-24 h-24 object-contain rounded-lg border border-gray-100 mb-3" alt="Clinic Logo">
            @else
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center text-3xl font-bold mb-3">
                    {{ strtoupper(substr($settings->clinic_name, 0, 1)) }}
                </div>
            @endif
            <h4 class="text-base font-bold text-gray-900">{{ $settings->clinic_name }}</h4>
            <p class="text-xs text-gray-500">{{ $settings->currency }} / {{ $settings->timezone }}</p>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <span class="text-gray-500">Phone</span>
                <span class="font-medium text-gray-900">{{ $settings->phone ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <span class="text-gray-500">Email</span>
                <span class="font-medium text-gray-900">{{ $settings->email ?? '-' }}</span>
            </div>
            <div class="flex items-start justify-between p-3 rounded-lg bg-gray-50">
                <span class="text-gray-500">Address</span>
                <span class="font-medium text-gray-900 text-right">{{ $settings->address ?? '-' }}</span>
            </div>
        </div>
        @if(!empty($settings->photos))
        <div class="mt-5">
            <p class="text-xs font-medium text-gray-700 mb-2">Clinic Photos</p>
            <div class="grid grid-cols-3 gap-2">
                @foreach($settings->photos as $photo)
                <img src="{{ asset('storage/' . $photo) }}" class="w-full h-20 object-cover rounded-lg border border-gray-100" alt="Clinic photo">
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Edit Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.432-4.364l-6.364-6.364a2.015 2.015 0 00-2.828 0l-1.768 1.768a2.015 2.015 0 000 2.828l6.364 6.364M16 7h6m-3-3v6"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Edit Clinic Profile</h3>
        </div>
        <form id="clinicForm" method="POST" action="{{ route('settings.clinic.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Clinic Name</label>
                    <input type="text" name="clinic_name" value="{{ old('clinic_name', $settings->clinic_name) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $settings->email) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('address', $settings->address) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Currency</label>
                    <select name="currency" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                        <option value="TZS" @selected(old('currency', $settings->currency) == 'TZS')>TZS - Tanzanian Shilling</option>
                        <option value="USD" @selected(old('currency', $settings->currency) == 'USD')>USD - US Dollar</option>
                        <option value="EUR" @selected(old('currency', $settings->currency) == 'EUR')>EUR - Euro</option>
                        <option value="GBP" @selected(old('currency', $settings->currency) == 'GBP')>GBP - British Pound</option>
                        <option value="KES" @selected(old('currency', $settings->currency) == 'KES')>KES - Kenyan Shilling</option>
                        <option value="UGX" @selected(old('currency', $settings->currency) == 'UGX')>UGX - Ugandan Shilling</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Timezone</label>
                    <select name="timezone" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                        <option value="Africa/Dar_es_Salaam" @selected(old('timezone', $settings->timezone) == 'Africa/Dar_es_Salaam')>Africa/Dar es Salaam</option>
                        <option value="Africa/Nairobi" @selected(old('timezone', $settings->timezone) == 'Africa/Nairobi')>Africa/Nairobi</option>
                        <option value="UTC" @selected(old('timezone', $settings->timezone) == 'UTC')>UTC</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Clinic Logo</label>
                    <input type="file" name="logo" accept="image/*" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 file:mr-3 file:px-3 file:py-1 file:rounded file:border-0 file:bg-emerald-50 file:text-emerald-700">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Clinic Photos</label>
                    <input type="file" name="photos[]" accept="image/*" multiple class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 file:mr-3 file:px-3 file:py-1 file:rounded file:border-0 file:bg-emerald-50 file:text-emerald-700">
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" id="clinicSaveBtn" class="w-full px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span id="clinicBtnText">Save Profile</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('clinicForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('clinicSaveBtn');
    const txt = document.getElementById('clinicBtnText');
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
            if (data.clinic) {
                document.querySelector('.grid > div:first-child h4').textContent = data.clinic.name;
                const phoneEl = document.querySelector('.grid > div:first-child .space-y-3 > div:nth-child(1) span:last-child');
                const emailEl = document.querySelector('.grid > div:first-child .space-y-3 > div:nth-child(2) span:last-child');
                const addressEl = document.querySelector('.grid > div:first-child .space-y-3 > div:nth-child(3) span:last-child');
                if (phoneEl) phoneEl.textContent = data.clinic.phone || '-';
                if (emailEl) emailEl.textContent = data.clinic.email || '-';
                if (addressEl) addressEl.textContent = data.clinic.address || '-';

                const avatarContainer = document.querySelector('.grid > div:first-child .flex.flex-col.items-center');
                if (data.clinic.logo_url) {
                    avatarContainer.innerHTML = `<img src="${data.clinic.logo_url}" class="w-24 h-24 object-contain rounded-lg border border-gray-100 mb-3" alt="Clinic Logo"><h4 class="text-base font-bold text-gray-900">${data.clinic.name}</h4><p class="text-xs text-gray-500">${data.clinic.currency} / ${data.clinic.timezone}</p>`;
                } else {
                    avatarContainer.innerHTML = `<div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center text-3xl font-bold mb-3">${data.clinic.name.charAt(0).toUpperCase()}</div><h4 class="text-base font-bold text-gray-900">${data.clinic.name}</h4><p class="text-xs text-gray-500">${data.clinic.currency} / ${data.clinic.timezone}</p>`;
                }

                if (data.clinic.photos && data.clinic.photos.length) {
                    const photosContainer = document.querySelector('.grid > div:first-child .grid.grid-cols-3');
                    if (photosContainer) {
                        photosContainer.innerHTML = data.clinic.photos.map(url => `<img src="${url}" class="w-full h-20 object-cover rounded-lg border border-gray-100" alt="Clinic photo">`).join('');
                    }
                }
            }
        } else {
            throw new Error(data.message || 'Imeshindwa.');
        }
    })
    .catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 4000 }))
    .finally(() => { btn.disabled = false; txt.textContent = original; });
});
</script>
@endpush
