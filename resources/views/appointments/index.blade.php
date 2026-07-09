@extends('layouts.dashboard')

@section('title', 'Appointments - ' . config('app.name'))
@section('page_title', auth()->user()->isDoctor() ? 'My Schedule' : 'Appointments')

@section('content')
@php
    $doctorsList = \App\Models\User::where('role', 'doctor')->get();
    $servicesList = \App\Models\Service::where('is_active', true)->get();
    $roomsList = \App\Models\Room::where('is_active', true)->get();
    $patientsList = \App\Models\Patient::orderBy('name')->get();
@endphp

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
@php
    $currentDate = \Carbon\Carbon::parse($date);
    $weekStart = $currentDate->copy()->startOfWeek();
    $weekDays = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));
@endphp

    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-semibold text-gray-900">Appointments</h3>
                <input type="date" id="appointmentDate" value="{{ $date }}" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-emerald-500">
            </div>
            <div class="flex items-center gap-1 mt-1">
                <button onclick="changeWeek(-7)" class="p-1 rounded hover:bg-gray-100 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="flex items-center gap-1">
                    @foreach($weekDays as $day)
                    <a href="{{ route('appointments.index', ['date' => $day->toDateString()]) }}" class="flex flex-col items-center justify-center w-10 h-10 rounded-lg text-[10px] {{ $day->isSameDay($currentDate) ? 'bg-emerald-600 text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                        <span class="font-medium">{{ $day->format('D') }}</span>
                        <span class="font-bold">{{ $day->format('d') }}</span>
                    </a>
                    @endforeach
                </div>
                <button onclick="changeWeek(7)" class="p-1 rounded hover:bg-gray-100 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-1">
                <button onclick="exportAppointments('csv')" class="p-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100" title="Export Excel/CSV">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </button>
                <button onclick="window.print()" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200" title="Print/PDF">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </button>
            </div>
            @if(!auth()->user()->isDoctor())
            <button onclick="openAppointmentModal()" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Book Appointment
            </button>
            @endif
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="appointmentsTable">
            <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                <th class="px-5 py-2.5 font-medium">Time</th>
                <th class="px-5 py-2.5 font-medium">Patient</th>
                <th class="px-5 py-2.5 font-medium">Service</th>
                <th class="px-5 py-2.5 font-medium">Doctor</th>
                <th class="px-5 py-2.5 font-medium">Room</th>
                <th class="px-5 py-2.5 font-medium">Status</th>
                <th class="px-5 py-2.5 font-medium text-right">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($appointments as $appt)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors" data-id="{{ $appt->id }}">
                    <td class="px-5 py-2.5 text-xs text-gray-900 font-medium">{{ optional($appt->start_time)->format('H:i') }} - {{ optional($appt->end_time)->format('H:i') }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ $appt->patient?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $appt->service?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $appt->doctor?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $appt->room?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $appt->statusColor() }}-50 text-{{ $appt->statusColor() }}-700 border border-{{ $appt->statusColor() }}-100">{{ $appt->statusLabel() }}</span>
                    </td>
                    <td class="px-5 py-2.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('appointments.show', $appt) }}" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100" title="View">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <button type="button" onclick="openEditAppointmentPanel('{{ route('appointments.edit-form', $appt) }}', '{{ route('appointments.update', $appt) }}', {{ $appt->id }})" class="p-1.5 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.432-4.364l-6.364-6.364a2.015 2.015 0 00-2.828 0l-1.768 1.768a2.015 2.015 0 000 2.828l6.364 6.364M16 7h6m-3-3v6"/></svg>
                            </button>
                            <form method="POST" action="{{ route('appointments.destroy', $appt) }}" data-confirm="Delete this appointment?" class="inline delete-appointment-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="noAppointmentsRow"><td colspan="7" class="px-5 py-8 text-center text-gray-400 text-xs">No appointments for this date</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($appointments->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $appointments->links() }}</div>
    @endif
</div>

{{-- Add Appointment Slide-over --}}
<div id="appointmentModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeAppointmentModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-xl bg-white shadow-2xl transform transition-transform translate-x-full duration-300 ease-in-out flex flex-col" id="appointmentSlidePanel">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <h3 class="text-sm font-semibold text-gray-900">Book Appointment</h3>
            <button onclick="closeAppointmentModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5">
            <form id="appointmentForm" method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Patient</label>
                        <input type="text" id="apptPatientSearch" placeholder="Tafuta kwa jina, simu au file..." class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500 mb-1">
                        <select name="patient_id" id="apptPatientSelect" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                            <option value="">Chagua mgonjwa</option>
                            @foreach($patientsList as $p)
                            <option value="{{ $p->id }}" data-search="{{ strtolower($p->name . ' ' . ($p->phone ?? '') . ' ' . $p->file_number) }}">{{ $p->name }} ({{ $p->phone ?? 'No phone' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Doctor</label>
                        <select name="doctor_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                            <option value="">Chagua daktari</option>
                            @foreach($doctorsList as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Service</label>
                        <select name="service_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                            <option value="">Chagua huduma</option>
                            @foreach($servicesList as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ number_format($s->price) }} TZS, {{ $s->duration_minutes }}m)</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Room</label>
                        <select name="room_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                            <option value="">Chagua chumba</option>
                            @foreach($roomsList as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="appointment_date" id="modalApptDate" value="{{ $date }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Start Time</label>
                        <input type="time" name="start_time" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                            @foreach(\App\Models\Appointment::STATUS_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected($value === 'booked')>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Appointment</button>
                    <button type="button" onclick="closeAppointmentModal()" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Appointment Slide-over --}}
<div id="editAppointmentModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeEditAppointmentPanel()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-xl bg-white shadow-2xl transform transition-transform translate-x-full duration-300 ease-in-out flex flex-col" id="editAppointmentSlidePanel">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <h3 class="text-sm font-semibold text-gray-900">Edit Appointment</h3>
            <button onclick="closeEditAppointmentPanel()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5" id="editAppointmentFormContainer">
            <div class="flex items-center justify-center py-10"><div class="w-6 h-6 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></div></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openAppointmentModal() {
    const modal = document.getElementById('appointmentModal');
    const panel = document.getElementById('appointmentSlidePanel');
    modal.classList.remove('hidden');
    document.getElementById('modalApptDate').value = document.getElementById('appointmentDate').value;
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
    document.body.style.overflow = 'hidden';
}
function closeAppointmentModal() {
    const modal = document.getElementById('appointmentModal');
    const panel = document.getElementById('appointmentSlidePanel');
    panel.classList.add('translate-x-full');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

let currentEditAppointmentId = null;

function openEditAppointmentPanel(loadUrl, submitUrl, appointmentId) {
    currentEditAppointmentId = appointmentId;
    const modal = document.getElementById('editAppointmentModal');
    const panel = document.getElementById('editAppointmentSlidePanel');
    const container = document.getElementById('editAppointmentFormContainer');
    container.innerHTML = '<div class="flex items-center justify-center py-10"><div class="w-6 h-6 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></div></div>';
    modal.classList.remove('hidden');
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
    document.body.style.overflow = 'hidden';

    fetch(loadUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text())
        .then(html => {
            container.innerHTML = `<form id="editAppointmentForm" method="POST" action="${submitUrl}">${html}</form>`;
            bindEditAppointmentForm();
            bindPatientFilters(container);
        });
}

function closeEditAppointmentPanel() {
    const modal = document.getElementById('editAppointmentModal');
    const panel = document.getElementById('editAppointmentSlidePanel');
    panel.classList.add('translate-x-full');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        currentEditAppointmentId = null;
    }, 300);
}

function bindEditAppointmentForm() {
    const form = document.getElementById('editAppointmentForm');
    if (!form) return;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Inahifadhi...';

        const formData = new FormData(form);
        const csrf = formData.get('_token');

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 4000, timerProgressBar: true });
                const row = document.querySelector(`#appointmentsTable tbody tr[data-id="${currentEditAppointmentId}"]`);
                if (row) {
                    row.children[0].textContent = data.appointment.time;
                    row.children[1].textContent = data.appointment.patient;
                    row.children[2].textContent = data.appointment.service;
                    row.children[3].textContent = data.appointment.doctor;
                    row.children[4].textContent = data.appointment.room;
                    row.children[5].innerHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-${data.appointment.statusColor}-50 text-${data.appointment.statusColor}-700 border border-${data.appointment.statusColor}-100">${data.appointment.statusLabel}</span>`;
                }
                closeEditAppointmentPanel();
            } else {
                throw new Error(data.message || 'Imeshindwa kuhifadhi mabadiliko.');
            }
        })
        .catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 5000 }))
        .finally(() => { btn.disabled = false; btn.textContent = originalText; });
    });
}

// Date change reload
document.getElementById('appointmentDate').addEventListener('change', function() {
    window.location.href = `{{ route('appointments.index') }}?date=${this.value}`;
});

function changeWeek(days) {
    const current = new Date(document.getElementById('appointmentDate').value);
    current.setDate(current.getDate() + days);
    const y = current.getFullYear();
    const m = String(current.getMonth() + 1).padStart(2, '0');
    const d = String(current.getDate()).padStart(2, '0');
    window.location.href = `{{ route('appointments.index') }}?date=${y}-${m}-${d}`;
}

// Filter patient dropdown by name/phone/file
function bindPatientFilters(root = document) {
    root.querySelectorAll('[data-patient-filter]').forEach(input => {
        input.removeEventListener('input', filterPatientOptions);
        input.addEventListener('input', filterPatientOptions);
    });
}
function filterPatientOptions() {
    const q = this.value.toLowerCase();
    const select = this.nextElementSibling;
    if (!select) return;
    Array.from(select.querySelectorAll('option')).forEach(opt => {
        if (!opt.value) { opt.style.display = 'block'; return; }
        opt.style.display = opt.dataset.search?.includes(q) ? 'block' : 'none';
    });
}
bindPatientFilters();

// AJAX create appointment
const appointmentForm = document.getElementById('appointmentForm');
appointmentForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = appointmentForm.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Inahifadhi...';

    const formData = new FormData(appointmentForm);
    const csrf = formData.get('_token');

    fetch(appointmentForm.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 4000, timerProgressBar: true });
            const tbody = document.querySelector('#appointmentsTable tbody');
            const noRow = document.getElementById('noAppointmentsRow');
            if (noRow) noRow.remove();
            const row = document.createElement('tr');
            row.className = 'border-t border-gray-100 hover:bg-gray-50/50 transition-colors';
            row.setAttribute('data-id', data.appointment.id);
            row.innerHTML = `
                <td class="px-5 py-2.5 text-xs text-gray-900 font-medium">${data.appointment.time}</td>
                <td class="px-5 py-2.5 text-xs text-gray-900">${data.appointment.patient}</td>
                <td class="px-5 py-2.5 text-xs text-gray-600">${data.appointment.service}</td>
                <td class="px-5 py-2.5 text-xs text-gray-600">${data.appointment.doctor}</td>
                <td class="px-5 py-2.5 text-xs text-gray-600">${data.appointment.room}</td>
                <td class="px-5 py-2.5"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-${data.appointment.statusColor}-50 text-${data.appointment.statusColor}-700 border border-${data.appointment.statusColor}-100">${data.appointment.statusLabel}</span></td>
                <td class="px-5 py-2.5 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="${data.appointment.show_url}" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100" title="View"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                        <button type="button" onclick="openEditAppointmentPanel('${data.appointment.edit_form_url}', '${data.appointment.update_url}', ${data.appointment.id})" class="p-1.5 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100" title="Edit"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.432-4.364l-6.364-6.364a2.015 2.015 0 00-2.828 0l-1.768 1.768a2.015 2.015 0 000 2.828l6.364 6.364M16 7h6m-3-3v6"/></svg></button>
                        <form method="POST" action="${data.appointment.delete_url}" data-confirm="Delete this appointment?" class="inline delete-appointment-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Delete"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </form>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
            rebindApptDeleteForms();
            appointmentForm.reset();
            closeAppointmentModal();
        } else {
            throw new Error(data.message || 'Imeshindwa kuweka miadi.');
        }
    })
    .catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 5000 }))
    .finally(() => { btn.disabled = false; btn.textContent = originalText; });
});

function rebindApptDeleteForms() {
    document.querySelectorAll('.delete-appointment-form').forEach(form => {
        form.removeEventListener('submit', handleApptDelete);
        form.addEventListener('submit', handleApptDelete);
    });
}

function handleApptDelete(e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        title: 'Una uhakika?',
        text: form.dataset.confirm,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ndiyo, futa!',
        cancelButtonText: 'Ghairi',
    }).then(result => {
        if (!result.isConfirmed) return;
        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 3000 });
                form.closest('tr').remove();
            } else {
                throw new Error(data.message);
            }
        })
        .catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 4000 }));
    });
}

function exportAppointments(type) {
    const rows = [['Time','Patient','Service','Doctor','Room','Status']];
    document.querySelectorAll('#appointmentsTable tbody tr').forEach(row => {
        if (row.id === 'noAppointmentsRow') return;
        const cols = row.querySelectorAll('td');
        rows.push([
            cols[0].textContent.trim(), cols[1].textContent.trim(), cols[2].textContent.trim(),
            cols[3].textContent.trim(), cols[4].textContent.trim(), cols[5].textContent.trim(),
        ]);
    });
    const csv = rows.map(r => r.map(cell => '"' + String(cell).replace(/"/g, '""') + '"').join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'appointments-' + document.getElementById('appointmentDate').value + '.csv';
    link.click();
}

rebindApptDeleteForms();
</script>
@endpush
