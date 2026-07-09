@extends('layouts.dashboard')

@section('title', 'Dashboard - ' . config('app.name', 'Laravel'))
@section('page_title', 'Dashboard Overview')

@section('content')
<style>
    .card-sm { transition: all 0.2s cubic-bezier(0.4,0,0.2,1); }
    .card-sm:hover { transform: translateY(-2px); box-shadow: 0 8px 30px -8px rgba(0,0,0,0.12); }
    .circular-chart { display: block; margin: 0 auto; max-width: 100%; max-height: 140px; }
    .circle-bg { fill: none; stroke: #e5e7eb; stroke-width: 3; }
    .circle { fill: none; stroke-width: 3; stroke-linecap: round; animation: progress 1s ease-out forwards; }
    @keyframes progress { 0% { stroke-dasharray: 0 100; } }
    .percentage { fill: #111827; font-weight: 700; font-size: 0.5rem; }
    .label { fill: #6b7280; font-size: 0.22rem; }
</style>

@php
    $isAdmin = $user->isAdmin();
    $isDoctor = $user->isDoctor();
    $isReception = $user->isReception();

    $fmt = fn($n) => 'TSh ' . number_format($n, 0);
@endphp

{{-- Welcome Header --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Hello {{ $user->name }} 👋</h1>
        <p class="text-sm text-gray-500 mt-0.5">Here's what's happening at {{ config('app.name', 'Laravel') }} today.</p>
    </div>
    <div class="flex items-center gap-2">
        @if($isAdmin)
            <a href="#" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors inline-flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Appointment
            </a>
        @endif
        <a href="#" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Add Patient
        </a>
    </div>
</div>

{{-- KPI Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @foreach([
        ['label'=>'Total Patients','value'=>number_format($totalPatients),'change'=>'+'.$newPatientsThisWeek.' this week','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','from'=>'emerald-600','to'=>'emerald-700','border'=>'emerald-500','text'=>'emerald-100','sub'=>'emerald-200'],
        ['label'=>'Today\'s Appointments','value'=>$appointmentsToday,'change'=>$appointmentsThisWeek.' this week','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','from'=>'gold-400','to'=>'gold-500','border'=>'gold-300','text'=>'amber-50','sub'=>'amber-100'],
        ['label'=>'Treatments Done','value'=>number_format($treatmentsDone),'change'=>$treatmentTarget.' monthly target','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','from'=>'sky-500','to'=>'sky-600','border'=>'sky-400','text'=>'sky-100','sub'=>'sky-200'],
        ['label'=>'Weekly Revenue','value'=>$fmt($revenueThisWeek),'change'=>'Today: '.$fmt($revenueToday),'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','from'=>'violet-500','to'=>'violet-600','border'=>'violet-400','text'=>'violet-100','sub'=>'violet-200']
    ] as $card)
    <div class="card-sm bg-gradient-to-br from-{{ $card['from'] }} to-{{ $card['to'] }} rounded-xl border border-{{ $card['border'] }} p-4 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-2">
                <span class="text-[10px] font-medium {{ $card['text'] }}">{{ $card['label'] }}</span>
                <svg class="w-4 h-4 {{ $card['sub'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
            <p class="text-xl font-bold tracking-tight text-white">{{ $card['value'] }}</p>
            <p class="text-[10px] {{ $card['sub'] }} font-medium mt-1">{{ $card['change'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Charts & Progress Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Revenue Bar Chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Weekly Revenue</h3>
                <p class="text-xs text-gray-400">Last 7 days</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-semibold text-gray-900">{{ $fmt($revenueThisWeek) }}</div>
                <div class="text-xs text-emerald-600 font-medium">+12.5%</div>
            </div>
        </div>
        @php $maxRev = $revenueDays->max() ?: 1; @endphp
        <div class="flex items-end gap-[6px] h-52">
            @foreach($revenueDays as $i => $rev)
            @php $pct = min(100, ($rev / $maxRev) * 100); $isToday = $i === count($revenueDays) - 1; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer" title="{{ $dayLabels[$i] }}: {{ $fmt($rev) }}">
                <div class="w-full bg-gray-50 rounded-t-md relative h-44 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 {{ $isToday ? 'bg-emerald-500' : 'bg-emerald-300 group-hover:bg-emerald-400' }}" style="height: {{ max($pct, 4) }}%"></div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">{{ $dayLabels[$i] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Circle Progress Cards --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm space-y-6">
        <h3 class="text-sm font-semibold text-gray-900">Performance</h3>

        @php
            $circles = [
                ['label' => 'Success Rate', 'value' => $successRate, 'color' => '#10b981'],
                ['label' => 'Clinic Occupancy', 'value' => $occupancyRate, 'color' => '#f59e0b'],
                ['label' => 'Treatment Target', 'value' => $treatmentTargetPct, 'color' => '#8b5cf6'],
            ];
        @endphp

        <div class="grid grid-cols-3 gap-2">
            @foreach($circles as $c)
            <div class="text-center">
                <svg viewBox="0 0 36 36" class="circular-chart">
                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                    <path class="circle" stroke="{{ $c['color'] }}" stroke-dasharray="{{ $c['value'] }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                    <text x="18" y="17" class="percentage" text-anchor="middle">{{ $c['value'] }}%</text>
                    <text x="18" y="22" class="label" text-anchor="middle">{{ $c['label'] }}</text>
                </svg>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Role-specific sections --}}
@if($isAdmin)
{{-- Admin: Upcoming Appointments & Recent Patients --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Today's Appointments</h3>
            <a href="#" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-2.5 font-medium">Time</th>
                    <th class="px-5 py-2.5 font-medium">Patient</th>
                    <th class="px-5 py-2.5 font-medium">Service</th>
                    <th class="px-5 py-2.5 font-medium">Status</th>
                </tr></thead>
                <tbody>
                    @forelse($todayAppointments as $appt)
                    <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-2.5 text-xs text-gray-600 font-medium">{{ optional($appt->start_time)->format('H:i') }}</td>
                        <td class="px-5 py-2.5 text-xs text-gray-900">{{ $appt->patient->name ?? '-' }}</td>
                        <td class="px-5 py-2.5 text-xs text-gray-600">{{ $appt->service->name ?? '-' }}</td>
                        <td class="px-5 py-2.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $appt->statusColor() }}-50 text-{{ $appt->statusColor() }}-700 border border-{{ $appt->statusColor() }}-100">{{ $appt->statusLabel() }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-6 text-center text-gray-400 text-xs">No appointments today</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Recent Patients</h3>
            <a href="#" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">View All</a>
        </div>
        <div class="p-5 space-y-3">
            @forelse($recentPatients as $p)
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs">
                    {{ strtoupper(substr($p->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $p->name }}</p>
                    <p class="text-xs text-gray-500">{{ $p->phone ?? $p->file_number }}</p>
                </div>
                <span class="text-[10px] text-gray-400 shrink-0">{{ $p->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No patients yet</p>
            @endforelse
        </div>
    </div>
</div>
@endif

@if($isReception)
{{-- Reception: Quick Check-in & Today's Schedule --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900">Reception Schedule</h3>
            </div>
            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md border border-emerald-100">{{ $appointmentsToday }} today</span>
        </div>
        <div class="space-y-3">
            @forelse($receptionSchedule as $s)
            <div class="flex items-center gap-3 p-3 rounded-lg border {{ $s->checkin_status === 'checked-in' ? 'border-emerald-200 bg-emerald-50/30' : 'border-gray-100 hover:bg-gray-50' }} transition-colors">
                <div class="w-12 text-center shrink-0">
                    <p class="text-xs font-bold text-gray-800">{{ optional($s->start_time)->format('H:i') }}</p>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">{{ $s->patient->name ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $s->service->name ?? '-' }}</p>
                </div>
                @if($s->checkin_status === 'checked-in')
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-100 text-emerald-700">Checked In</span>
                @elseif($s->checkin_status === 'waiting')
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-gold-100 text-gold-700">Waiting</span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-600">Upcoming</span>
                @endif
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No schedule entries today</p>
            @endforelse
        </div>
    </div>

    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-xl border border-emerald-500 p-5 text-white shadow-sm">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-white">Quick Actions</h3>
        </div>
        <div class="space-y-2">
            <a href="#" class="flex items-center gap-2 p-2.5 rounded-lg bg-white/5 hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-xs font-medium">Register Patient</span>
            </a>
            <a href="#" class="flex items-center gap-2 p-2.5 rounded-lg bg-white/5 hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-xs font-medium">Book Appointment</span>
            </a>
            <a href="#" class="flex items-center gap-2 p-2.5 rounded-lg bg-white/5 hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-xs font-medium">Record Payment</span>
            </a>
        </div>
    </div>
</div>
@endif
@endsection
