@extends('layouts.dashboard')

@section('title', 'Patients - ' . config('app.name'))
@section('page_title', 'Patients')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-sm font-semibold text-gray-900">All Patients</h3>
            <p class="text-xs text-gray-500">Search by name, phone or file number</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="patientSearch" value="{{ $search ?? '' }}" placeholder="Search patients..." class="pl-9 text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 w-48">
            </div>
            <div class="flex items-center gap-1">
                <button onclick="exportTable('csv')" class="p-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100" title="Export Excel/CSV">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </button>
                <button onclick="window.print()" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200" title="Print/PDF">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </button>
            </div>
            <button onclick="openPatientModal()" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Patient
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="patientsTable">
            <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                <th class="px-5 py-2.5 font-medium">File #</th>
                <th class="px-5 py-2.5 font-medium">Name</th>
                <th class="px-5 py-2.5 font-medium">Phone</th>
                <th class="px-5 py-2.5 font-medium">Gender</th>
                <th class="px-5 py-2.5 font-medium">Type</th>
                <th class="px-5 py-2.5 font-medium text-right">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($patients as $patient)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors" data-id="{{ $patient->id }}">
                    <td class="px-5 py-2.5 text-xs font-medium text-emerald-700">{{ $patient->file_number }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ $patient->name }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $patient->phone ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600 capitalize">{{ $patient->gender ?? '-' }}</td>
                    <td class="px-5 py-2.5">
                        @if($patient->new_patient)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">New</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-600">Returning</span>
                        @endif
                    </td>
                    <td class="px-5 py-2.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('patients.show', $patient) }}" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100" title="View">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <button type="button" onclick="openEditPatientPanel('{{ route('patients.edit-form', $patient) }}', '{{ route('patients.update', $patient) }}', {{ $patient->id }})" class="p-1.5 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.432-4.364l-6.364-6.364a2.015 2.015 0 00-2.828 0l-1.768 1.768a2.015 2.015 0 000 2.828l6.364 6.364M16 7h6m-3-3v6"/></svg>
                            </button>
                            <form method="POST" action="{{ route('patients.destroy', $patient) }}" data-confirm="Delete this patient?" class="inline delete-patient-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="noPatientsRow"><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-xs">No patients found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $patients->links() }}</div>
    @endif
</div>

{{-- Add Patient Slide-over --}}
<div id="patientModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closePatientModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-xl bg-white shadow-2xl transform transition-transform translate-x-full duration-300 ease-in-out flex flex-col" id="patientSlidePanel">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <h3 class="text-sm font-semibold text-gray-900">Register New Patient</h3>
            <button onclick="closePatientModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5">
            <form id="patientForm" method="POST" action="{{ route('patients.store') }}">
                @include('patients._form')
            </form>
        </div>
    </div>
</div>

{{-- Edit Patient Slide-over --}}
<div id="editPatientModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeEditPatientPanel()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-xl bg-white shadow-2xl transform transition-transform translate-x-full duration-300 ease-in-out flex flex-col" id="editPatientSlidePanel">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <h3 class="text-sm font-semibold text-gray-900">Edit Patient</h3>
            <button onclick="closeEditPatientPanel()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5" id="editPatientFormContainer">
            <div class="flex items-center justify-center py-10"><div class="w-6 h-6 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></div></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openPatientModal() {
    const modal = document.getElementById('patientModal');
    const panel = document.getElementById('patientSlidePanel');
    modal.classList.remove('hidden');
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
    document.body.style.overflow = 'hidden';
}
function closePatientModal() {
    const modal = document.getElementById('patientModal');
    const panel = document.getElementById('patientSlidePanel');
    panel.classList.add('translate-x-full');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

let currentEditPatientId = null;
let currentEditPatientUrl = null;

function openEditPatientPanel(loadUrl, submitUrl, patientId) {
    currentEditPatientUrl = submitUrl;
    currentEditPatientId = patientId;
    const modal = document.getElementById('editPatientModal');
    const panel = document.getElementById('editPatientSlidePanel');
    const container = document.getElementById('editPatientFormContainer');
    container.innerHTML = '<div class="flex items-center justify-center py-10"><div class="w-6 h-6 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></div></div>';
    modal.classList.remove('hidden');
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
    document.body.style.overflow = 'hidden';

    fetch(loadUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text())
        .then(html => {
            container.innerHTML = `<form id="editPatientForm" method="POST" action="${submitUrl}">${html}</form>`;
            bindEditPatientForm();
        });
}

function closeEditPatientPanel() {
    const modal = document.getElementById('editPatientModal');
    const panel = document.getElementById('editPatientSlidePanel');
    panel.classList.add('translate-x-full');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        currentEditPatientId = null;
        currentEditPatientUrl = null;
    }, 300);
}

function bindEditPatientForm() {
    const form = document.getElementById('editPatientForm');
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
                // Update row
                const row = document.querySelector(`#patientsTable tbody tr[data-id="${currentEditPatientId}"]`);
                if (row) {
                    const typeBadge = data.patient.new_patient
                        ? '<span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">New</span>'
                        : '<span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-600">Returning</span>';
                    row.children[1].textContent = data.patient.name;
                    row.children[2].textContent = data.patient.phone;
                    row.children[3].textContent = data.patient.gender;
                    row.children[4].innerHTML = typeBadge;
                }
                closeEditPatientPanel();
            } else {
                throw new Error(data.message || 'Imeshindwa kuhifadhi mabadiliko.');
            }
        })
        .catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 4000 }))
        .finally(() => { btn.disabled = false; btn.textContent = originalText; });
    });
}

// AJAX search
let searchTimeout;
document.getElementById('patientSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const q = this.value;
        fetch(`{{ route('patients.index') }}?search=${encodeURIComponent(q)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newBody = doc.querySelector('#patientsTable tbody');
            if (newBody) document.querySelector('#patientsTable tbody').innerHTML = newBody.innerHTML;
            rebindDeleteForms();
        });
    }, 300);
});

// AJAX create patient
const patientForm = document.getElementById('patientForm');
patientForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = patientForm.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Inahifadhi...';

    const formData = new FormData(patientForm);
    const csrf = formData.get('_token');

    fetch(patientForm.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(r => {
        if (!r.ok && r.status === 422) {
            return r.json().then(errData => {
                const errors = errData.errors || {};
                const firstError = Object.values(errors)[0];
                throw new Error(firstError || errData.message || 'Validation failed.');
            });
        }
        return r.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: data.message, showConfirmButton: false, timer: 4000, timerProgressBar: true,
            });
            // Add row to table
            const tbody = document.querySelector('#patientsTable tbody');
            const noRow = document.getElementById('noPatientsRow');
            if (noRow) noRow.remove();
            const typeBadge = data.patient.new_patient
                ? '<span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">New</span>'
                : '<span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-600">Returning</span>';
            const row = document.createElement('tr');
            row.className = 'border-t border-gray-100 hover:bg-gray-50/50 transition-colors';
            row.setAttribute('data-id', data.patient.id);
            row.innerHTML = `
                <td class="px-5 py-2.5 text-xs font-medium text-emerald-700">${data.patient.file_number}</td>
                <td class="px-5 py-2.5 text-xs text-gray-900">${data.patient.name}</td>
                <td class="px-5 py-2.5 text-xs text-gray-600">${data.patient.phone}</td>
                <td class="px-5 py-2.5 text-xs text-gray-600 capitalize">${data.patient.gender}</td>
                <td class="px-5 py-2.5">${typeBadge}</td>
                <td class="px-5 py-2.5 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="${data.patient.show_url}" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100" title="View"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                        <button type="button" onclick="openEditPatientPanel('${data.patient.edit_form_url}', '${data.patient.update_url}', ${data.patient.id})" class="p-1.5 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100" title="Edit"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.432-4.364l-6.364-6.364a2.015 2.015 0 00-2.828 0l-1.768 1.768a2.015 2.015 0 000 2.828l6.364 6.364M16 7h6m-3-3v6"/></svg></button>
                        <form method="POST" action="${data.patient.delete_url}" data-confirm="Delete this patient?" class="inline delete-patient-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Delete"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </form>
                    </div>
                </td>
            `;
            tbody.insertBefore(row, tbody.firstChild);
            rebindDeleteForms();
            patientForm.reset();
            closePatientModal();
        } else {
            throw new Error(data.message || 'Imeshindwa kumsajili mgonjwa.');
        }
    })
    .catch(err => {
        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 4000 });
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = originalText;
    });
});

function rebindDeleteForms() {
    document.querySelectorAll('.delete-patient-form').forEach(form => {
        form.removeEventListener('submit', handleDelete);
        form.addEventListener('submit', handleDelete);
    });
}

function handleDelete(e) {
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

function exportTable(type) {
    const rows = [['File #','Name','Phone','Gender','Type']];
    document.querySelectorAll('#patientsTable tbody tr').forEach(row => {
        if (row.id === 'noPatientsRow') return;
        const cols = row.querySelectorAll('td');
        rows.push([
            cols[0].textContent.trim(),
            cols[1].textContent.trim(),
            cols[2].textContent.trim(),
            cols[3].textContent.trim(),
            cols[4].textContent.trim(),
        ]);
    });
    let csv = rows.map(r => r.map(cell => '"' + String(cell).replace(/"/g, '""') + '"').join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'patients-' + new Date().toISOString().slice(0,10) + '.csv';
    link.click();
}

rebindDeleteForms();
</script>
@endpush
