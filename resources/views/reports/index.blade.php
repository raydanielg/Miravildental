@extends('layouts.dashboard')

@section('title', 'Reports - ' . config('app.name'))
@section('page_title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end gap-3">
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
    </div>

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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Appointments by Day</h3>
            @php $maxCount = max($appointmentsByDay->max() ?? 1, 1); @endphp
            <div class="flex items-end gap-2 h-44">
                @foreach($appointmentsByDay as $date => $count)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full bg-gray-50 rounded-t-md relative h-36 overflow-hidden">
                        <div class="absolute bottom-0 left-0 right-0 bg-emerald-500 rounded-t-md" style="height: {{ max(($count / $maxCount) * 100, 4) }}%"></div>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
                </div>
                @endforeach
                @if($appointmentsByDay->isEmpty())
                <p class="text-sm text-gray-400 w-full text-center py-10">No data</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Top Services</h3>
            <div class="space-y-3">
                @forelse($servicesByCount as $service)
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-gray-700">{{ $service->name }}</span>
                        <span class="font-medium text-gray-900">{{ $service->count }} ({{ number_format($service->revenue) }} TZS)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-gold-500 h-1.5 rounded-full" style="width: {{ $totalAppointments > 0 ? ($service->count / $totalAppointments * 100) : 0 }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No service data</p>
                @endforelse
            </div>
        </div>
    </div>

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
@endsection
