@extends('layouts.dashboard')

@section('title', 'Reports - ' . config('app.name'))
@section('page_title', 'Reports & Analytics')

@section('content')
@php
    $completionRate = $totalAppointments > 0 ? round(($completed / $totalAppointments) * 100) : 0;
    $cancellationRate = $totalAppointments > 0 ? round(($cancelled / $totalAppointments) * 100) : 0;
    $noShowRate = $totalAppointments > 0 ? round(($noShows / $totalAppointments) * 100) : 0;
    $labels = $appointmentsByDay->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->values();
    $values = $appointmentsByDay->values();
@endphp

<div class="space-y-6">
    {{-- Filters & Actions --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex flex-wrap items-end justify-between gap-3">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end gap-3" id="reportFilterForm">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start" value="{{ $start }}" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end" value="{{ $end }}" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-emerald-500">
            </div>
            <button type="submit" class="px-4 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Generate</button>
        </form>
        <div class="flex items-center gap-2">
            <button onclick="printReport()" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print
            </button>
            <a href="{{ route('reports.export', ['start' => $start, 'end' => $end]) }}" class="px-3 py-1.5 text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
            <button onclick="openEmailModal()" class="px-3 py-1.5 text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200 rounded-lg hover:bg-sky-100 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Email Report
            </button>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @foreach([
            ['label'=>'Total Appointments','value'=>$totalAppointments,'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['label'=>'Completed','value'=>$completed,'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'Revenue','value'=>'TSh ' . number_format($revenue),'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'New Patients','value'=>$newPatients,'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ] as $card)
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-xl border border-emerald-500 p-4 text-white relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-[10px] font-medium text-emerald-100">{{ $card['label'] }}</span>
                <p class="text-xl font-bold tracking-tight text-white mt-1">{{ $card['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Circular Progress Charts --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex flex-col items-center justify-center">
            <h3 class="text-xs font-semibold text-gray-700 mb-3">Completion Rate</h3>
            <div class="relative w-32 h-32">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="42" stroke="#e2e8f0" stroke-width="10" fill="none"/>
                    <circle cx="50" cy="50" r="42" stroke="#10b981" stroke-width="10" fill="none" stroke-dasharray="264" stroke-dashoffset="{{ 264 - (264 * $completionRate / 100) }}" class="progress-ring transition-all duration-1000 ease-out"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-xl font-bold text-emerald-600" data-animate="{{ $completionRate }}">0%</span>
                </div>
            </div>
            <p class="text-[10px] text-gray-500 mt-2">{{ $completed }} of {{ $totalAppointments }} completed</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex flex-col items-center justify-center">
            <h3 class="text-xs font-semibold text-gray-700 mb-3">Cancellation Rate</h3>
            <div class="relative w-32 h-32">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="42" stroke="#e2e8f0" stroke-width="10" fill="none"/>
                    <circle cx="50" cy="50" r="42" stroke="#f59e0b" stroke-width="10" fill="none" stroke-dasharray="264" stroke-dashoffset="{{ 264 - (264 * $cancellationRate / 100) }}" class="progress-ring transition-all duration-1000 ease-out"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-xl font-bold text-amber-500" data-animate="{{ $cancellationRate }}">0%</span>
                </div>
            </div>
            <p class="text-[10px] text-gray-500 mt-2">{{ $cancelled }} of {{ $totalAppointments }} cancelled</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex flex-col items-center justify-center">
            <h3 class="text-xs font-semibold text-gray-700 mb-3">No-show Rate</h3>
            <div class="relative w-32 h-32">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="42" stroke="#e2e8f0" stroke-width="10" fill="none"/>
                    <circle cx="50" cy="50" r="42" stroke="#ef4444" stroke-width="10" fill="none" stroke-dasharray="264" stroke-dashoffset="{{ 264 - (264 * $noShowRate / 100) }}" class="progress-ring transition-all duration-1000 ease-out"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-xl font-bold text-red-500" data-animate="{{ $noShowRate }}">0%</span>
                </div>
            </div>
            <p class="text-[10px] text-gray-500 mt-2">{{ $noShows }} of {{ $totalAppointments }} no-shows</p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Appointments by Day</h3>
            <div class="relative h-64 w-full">
                <canvas id="appointmentsChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Top Services</h3>
            <div class="space-y-4">
                @forelse($servicesByCount as $service)
                @php $pct = $totalAppointments > 0 ? round($service->count / $totalAppointments * 100) : 0; @endphp
                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-gray-700 font-medium">{{ $service->name }}</span>
                        <span class="font-semibold text-gray-900">{{ $service->count }} <span class="text-gray-400">({{ number_format($service->revenue) }} TZS)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-gold-500 h-2 rounded-full transition-all duration-1000" style="width: 0%" data-width="{{ $pct }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No service data</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Bottom Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
            <p class="text-xs text-gray-500">Cancelled</p>
            <p class="text-2xl font-bold text-gray-900">{{ $cancelled }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
            <p class="text-xs text-gray-500">No-shows</p>
            <p class="text-2xl font-bold text-gray-900">{{ $noShows }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
            <p class="text-xs text-gray-500">SMS Sent</p>
            <p class="text-2xl font-bold text-gray-900">{{ $smsSent }}</p>
        </div>
    </div>
</div>

{{-- Email Modal --}}
<div id="emailReportModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEmailModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-xl w-full max-w-md">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Email Report</h3>
                <button onclick="closeEmailModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form method="POST" action="{{ route('reports.email') }}" class="p-5">
                @csrf
                <input type="hidden" name="start" value="{{ $start }}">
                <input type="hidden" name="end" value="{{ $end }}">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Recipient Email</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 text-xs font-medium bg-sky-600 text-white rounded-lg hover:bg-sky-700">Send Email</button>
                    <button type="button" onclick="closeEmailModal()" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($labels);
const dataValues = @json($values);

if (labels.length) {
    new Chart(document.getElementById('appointmentsChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Appointments',
                data: dataValues,
                backgroundColor: '#10b981',
                borderRadius: 4,
                barThickness: 'flex',
                maxBarThickness: 24,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                x: {
                    grid: { display: false },
                    ticks: { maxRotation: 45, minRotation: 0, autoSkip: true, maxTicksLimit: 10 }
                }
            },
            animation: { duration: 1200, easing: 'easeOutQuart' }
        }
    });
}

// Animate percentages and bars
setTimeout(() => {
    document.querySelectorAll('[data-animate]').forEach(el => {
        const target = parseInt(el.dataset.animate);
        let current = 0;
        const step = Math.max(1, Math.floor(target / 30));
        const timer = setInterval(() => {
            current += step;
            if (current >= target) { current = target; clearInterval(timer); }
            el.textContent = current + '%';
        }, 30);
    });
    document.querySelectorAll('[data-width]').forEach(bar => {
        bar.style.width = bar.dataset.width;
    });
}, 300);

function printReport() { window.print(); }
function openEmailModal() { document.getElementById('emailReportModal').classList.remove('hidden'); }
function closeEmailModal() { document.getElementById('emailReportModal').classList.add('hidden'); }
</script>
@endpush
