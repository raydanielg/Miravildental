@extends('layouts.dashboard')

@section('title', auth()->user()->isDoctor() ? 'Treatment Records - ' . config('app.name') : 'Clinical Records - ' . config('app.name'))
@section('page_title', auth()->user()->isDoctor() ? 'Treatment Records' : 'Clinical Records')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="text-sm font-semibold text-gray-900">Records</h3>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('clinical-records.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search patient..." class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-emerald-500">
                <button type="submit" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>
            <button type="button" onclick="openClinicalRecordModal()" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Record
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="clinicalRecordsTable">
            <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                <th class="px-5 py-2.5 font-medium">Date</th>
                <th class="px-5 py-2.5 font-medium">Patient</th>
                <th class="px-5 py-2.5 font-medium">Service</th>
                <th class="px-5 py-2.5 font-medium">Doctor</th>
                <th class="px-5 py-2.5 font-medium">Cost</th>
                <th class="px-5 py-2.5 font-medium text-right">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($records as $record)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors" data-id="{{ $record->id }}">
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ $record->record_date?->format('M d, Y') }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ $record->patient?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $record->service?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $record->doctor?->name ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-900">{{ number_format($record->cost ?? 0) }} TZS</td>
                    <td class="px-5 py-2.5 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('clinical-records.show', $record) }}" class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <button type="button" onclick="openEditClinicalRecordModal({{ $record->id }})" class="p-1.5 rounded-lg text-sky-600 hover:bg-sky-50" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.432-4.364l-6.364-6.364a2.015 2.015 0 00-2.828 0l-1.768 1.768a2.015 2.015 0 000 2.828l6.364 6.364M16 7h6m-3-3v6"/></svg>
                            </button>
                            <button type="button" onclick="deleteClinicalRecord({{ $record->id }})" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-xs">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $records->links() }}</div>
    @endif
</div>

{{-- Add Record Slide-over --}}
<div id="clinicalRecordModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeClinicalRecordModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-xl bg-white shadow-2xl transform transition-transform translate-x-full duration-300 ease-in-out flex flex-col" id="clinicalRecordSlidePanel">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <h3 class="text-sm font-semibold text-gray-900">Add Treatment Record</h3>
            <button onclick="closeClinicalRecordModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5">
            <form id="clinicalRecordForm" method="POST" action="{{ route('clinical-records.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Patient</label>
                        <select name="patient_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                            <option value="">Select patient</option>
                            @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Service</label>
                        <select name="service_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                            <option value="">Select service</option>
                            @foreach($services as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Record Date</label>
                        <input type="date" name="record_date" value="{{ today()->toDateString() }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Cost (TZS)</label>
                        <input type="number" name="cost" value="0" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Chief Complaint</label>
                        <textarea name="chief_complaint" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Clinical Notes</label>
                        <textarea name="clinical_notes" rows="3" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Diagnosis</label>
                        <textarea name="diagnosis" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Prescription</label>
                        <textarea name="prescription" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Treatment Plan</label>
                        <textarea name="treatment_plan" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Record</button>
                    <button type="button" onclick="closeClinicalRecordModal()" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Record Slide-over --}}
<div id="editClinicalRecordModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeEditClinicalRecordModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-xl bg-white shadow-2xl transform transition-transform translate-x-full duration-300 ease-in-out flex flex-col" id="editClinicalRecordSlidePanel">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <h3 class="text-sm font-semibold text-gray-900">Edit Treatment Record</h3>
            <button onclick="closeEditClinicalRecordModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5" id="editClinicalRecordFormContainer">
            <div class="text-center text-sm text-gray-400 py-10">Loading...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openClinicalRecordModal() {
    const modal = document.getElementById('clinicalRecordModal');
    const panel = document.getElementById('clinicalRecordSlidePanel');
    modal.classList.remove('hidden');
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
    document.body.style.overflow = 'hidden';
}
function closeClinicalRecordModal() {
    const modal = document.getElementById('clinicalRecordModal');
    const panel = document.getElementById('clinicalRecordSlidePanel');
    panel.classList.add('translate-x-full');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
}

function openEditClinicalRecordModal(id) {
    const modal = document.getElementById('editClinicalRecordModal');
    const panel = document.getElementById('editClinicalRecordSlidePanel');
    const container = document.getElementById('editClinicalRecordFormContainer');
    container.innerHTML = '<div class="text-center text-sm text-gray-400 py-10">Loading...</div>';
    modal.classList.remove('hidden');
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
    document.body.style.overflow = 'hidden';

    fetch(`/clinical-records/${id}/edit-form`)
        .then(r => r.text())
        .then(html => {
            container.innerHTML = `<form id="editClinicalRecordForm" method="POST" action="/clinical-records/${id}">${html}</form>`;
            bindEditForm();
        })
        .catch(() => container.innerHTML = '<div class="text-center text-sm text-red-500 py-10">Imeshindwa kupakia fomu.</div>');
}
function closeEditClinicalRecordModal() {
    const modal = document.getElementById('editClinicalRecordModal');
    const panel = document.getElementById('editClinicalRecordSlidePanel');
    panel.classList.add('translate-x-full');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
}

function bindEditForm() {
    const form = document.getElementById('editClinicalRecordForm');
    if (!form) return;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const original = btn.textContent;
        btn.disabled = true; btn.textContent = 'Inahifadhi...';

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
                closeEditClinicalRecordModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error(data.message || 'Imeshindwa.');
            }
        })
        .catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 4000 }))
        .finally(() => { btn.disabled = false; btn.textContent = original; });
    });
}

document.getElementById('clinicalRecordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const original = btn.textContent;
    btn.disabled = true; btn.textContent = 'Inahifadhi...';

    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('#clinicalRecordForm input[name="_token"]').value,
            'Accept': 'application/json',
        },
        body: new FormData(this),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 3000 });
            closeClinicalRecordModal();
            this.reset();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(data.message || 'Imeshindwa.');
        }
    })
    .catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 4000 }))
    .finally(() => { btn.disabled = false; btn.textContent = original; });
});

function deleteClinicalRecord(id) {
    Swal.fire({
        title: 'Una uhakika?',
        text: 'Rekodi hii itafutwa kabisa!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#d1d5db',
        confirmButtonText: 'Ndio, futa',
        cancelButtonText: 'Ghairi'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/clinical-records/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData()
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 3000 });
                    document.querySelector(`tr[data-id="${id}"]`)?.remove();
                } else {
                    throw new Error(data.message || 'Imeshindwa.');
                }
            }).catch(err => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: err.message, showConfirmButton: false, timer: 4000 }));
        }
    });
}
</script>
@endpush
