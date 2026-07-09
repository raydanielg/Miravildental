@extends('layouts.dashboard')

@section('title', 'Working Hours - ' . config('app.name'))
@section('page_title', 'Working Hours')

@section('content')
@include('settings._nav', ['active' => 'working-hours'])

@php
    $today = now();
    $year = $today->year;
    $month = $today->month;
    $daysInMonth = $today->daysInMonth;
    $firstDayOfMonth = $today->copy()->startOfMonth();
    $startOffset = $firstDayOfMonth->dayOfWeekIso - 1;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    {{-- Calendar Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Calendar</h3>
        </div>
        <div class="text-center mb-3">
            <span class="text-sm font-bold text-gray-800">{{ $today->format('F Y') }}</span>
        </div>
        <div class="grid grid-cols-7 gap-1 text-center text-[10px] text-gray-400 mb-1">
            <div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div><div>S</div>
        </div>
        <div class="grid grid-cols-7 gap-1">
            @for($i = 0; $i < $startOffset; $i++)
                <div></div>
            @endfor
            @for($d = 1; $d <= $daysInMonth; $d++)
                @php $dayDate = \Carbon\Carbon::createFromDate($year, $month, $d); @endphp
                <div class="aspect-square flex items-center justify-center rounded-lg text-xs {{ $dayDate->isToday() ? 'bg-emerald-600 text-white font-bold shadow' : 'bg-gray-50 text-gray-700 hover:bg-emerald-50' }}">
                    {{ $d }}
                </div>
            @endfor
        </div>
    </div>

    {{-- Working Hours Card --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Weekly Schedule</h3>
        </div>
        <form id="hoursForm" method="POST" action="{{ route('settings.working-hours.update') }}">
        @csrf @method('PUT')
        <div class="space-y-3">
            @foreach($days as $day)
            @php $hour = $globalHours->get($day); @endphp
            <div class="grid grid-cols-4 gap-3 items-center p-2 rounded-lg hover:bg-gray-50">
                <div class="text-sm font-medium text-gray-700 capitalize">{{ $day }}</div>
                <div>
                    <input type="time" name="hours[{{ $day }}][start_time]" value="{{ old('hours.'.$day.'.start_time', $hour?->start_time?->format('H:i') ?? '08:00') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                </div>
                <div>
                    <input type="time" name="hours[{{ $day }}][end_time]" value="{{ old('hours.'.$day.'.end_time', $hour?->end_time?->format('H:i') ?? '17:00') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="hours[{{ $day }}][is_open]" value="1" @checked(old('hours.'.$day.'.is_open', $hour?->is_open ?? true)) class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <label class="text-xs text-gray-700">Open</label>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">
            <button type="submit" id="hoursSaveBtn" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span id="hoursBtnText">Save Working Hours</span>
            </button>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('hoursForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('hoursSaveBtn');
    const txt = document.getElementById('hoursBtnText');
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
</script>
@endpush
