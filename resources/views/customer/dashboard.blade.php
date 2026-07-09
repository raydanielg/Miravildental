@extends('layouts.dashboard')

@section('title', 'My Account - ' . config('app.name'))
@section('page_title', 'My Account')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    {{-- Welcome banner --}}
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl p-5 sm:p-6 text-white shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold">Welcome, {{ $user->name }}</h2>
                    <p class="text-emerald-100 text-xs mt-0.5">Customer ID: {{ $patient?->file_number ?? 'N/A' }}</p>
                </div>
            </div>
            <button onclick="openBookModal()" class="px-4 py-2 text-xs font-bold bg-white text-emerald-700 rounded-lg shadow hover:bg-emerald-50 inline-flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Book Appointment
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- Profile card --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900">My Profile</h3>
            </div>

            <form id="profileForm" method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" value="{{ $user->name }}" disabled class="w-full text-sm border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled class="w-full text-sm border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $patient?->phone ?? $user->phone) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                        <select name="gender" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 bg-white">
                            <option value="">Select</option>
                            <option value="male" @selected(old('gender', $patient?->gender) == 'male')>Male</option>
                            <option value="female" @selected(old('gender', $patient?->gender) == 'female')>Female</option>
                            <option value="other" @selected(old('gender', $patient?->gender) == 'other')>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient?->date_of_birth?->format('Y-m-d')) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('address', $patient?->address) }}</textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Profile</button>
            </form>
        </div>

        {{-- Appointments --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Upcoming --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-sky-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900">Upcoming Appointments</h3>
                    </div>
                    <span class="text-xs text-gray-500">{{ $upcoming->count() }}</span>
                </div>
                <div class="p-5">
                    @forelse($upcoming as $appt)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-lg border border-gray-100 bg-gray-50/50 mb-3 last:mb-0">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex flex-col items-center justify-center text-[10px] font-bold leading-tight">
                                <span>{{ $appt->appointment_date->format('M') }}</span>
                                <span class="text-sm">{{ $appt->appointment_date->format('d') }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $appt->service?->name ?? 'Appointment' }}</p>
                                <p class="text-xs text-gray-500">Dr. {{ $appt->doctor?->name ?? '-' }} &bull; {{ $appt->start_time->format('H:i') }} - {{ $appt->end_time->format('H:i') }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $appt->statusColor() }}-50 text-{{ $appt->statusColor() }}-700">{{ $appt->statusLabel() }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400 text-sm">
                        <p>No upcoming appointments.</p>
                        <button onclick="openBookModal()" class="mt-2 text-emerald-600 text-xs font-medium hover:underline">Book your first appointment</button>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Past --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900">Past Appointments</h3>
                    </div>
                    <span class="text-xs text-gray-500">{{ $past->count() }}</span>
                </div>
                <div class="p-5">
                    @forelse($past as $appt)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-lg border border-gray-100 bg-gray-50/50 mb-3 last:mb-0">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-500 flex flex-col items-center justify-center text-[10px] font-bold leading-tight">
                                <span>{{ $appt->appointment_date->format('M') }}</span>
                                <span class="text-sm">{{ $appt->appointment_date->format('d') }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $appt->service?->name ?? 'Appointment' }}</p>
                                <p class="text-xs text-gray-500">Dr. {{ $appt->doctor?->name ?? '-' }} &bull; {{ $appt->start_time->format('H:i') }} - {{ $appt->end_time->format('H:i') }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $appt->statusColor() }}-50 text-{{ $appt->statusColor() }}-700">{{ $appt->statusLabel() }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400 text-sm">No past appointments.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Book Appointment Slide-over --}}
<div id="bookModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeBookModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-md bg-white shadow-2xl transform transition-transform translate-x-full duration-300 ease-in-out flex flex-col" id="bookSlidePanel">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Book Appointment</h3>
            <button onclick="closeBookModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5">
            <form id="bookForm" method="POST" action="{{ route('customer.appointments.book') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Service</label>
                        <select name="service_id" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 bg-white">
                            <option value="">Select service</option>
                            @foreach($services as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Doctor</label>
                        <select name="doctor_id" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 bg-white">
                            <option value="">Select doctor</option>
                            @foreach($doctors as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Preferred Date</label>
                        <input type="date" name="appointment_date" required min="{{ today()->toDateString() }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Preferred Time</label>
                        <input type="time" name="start_time" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" placeholder="Any special requests..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Request Appointment</button>
                    <button type="button" onclick="closeBookModal()" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openBookModal() {
    const modal = document.getElementById('bookModal');
    const panel = document.getElementById('bookSlidePanel');
    modal.classList.remove('hidden');
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
    document.body.style.overflow = 'hidden';
}
function closeBookModal() {
    const modal = document.getElementById('bookModal');
    const panel = document.getElementById('bookSlidePanel');
    panel.classList.add('translate-x-full');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
}

function handleAjaxForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const original = btn.textContent;
        btn.disabled = true; btn.textContent = 'Saving...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: new FormData(form),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 3000 });
                if (formId === 'bookForm') {
                    closeBookModal();
                    form.reset();
                }
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error(data.message || 'Failed.');
            }
        })
        .catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 4000 }))
        .finally(() => { btn.disabled = false; btn.textContent = original; });
    });
}

handleAjaxForm('bookForm');
handleAjaxForm('profileForm');
</script>
@endpush
