@extends('layouts.dashboard')

@section('title', 'Online Bookings - ' . config('app.name'))
@section('page_title', 'Online Bookings')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-sm font-semibold text-gray-900">Website Appointment Requests</h3>
            <p class="text-xs text-gray-500 mt-1">Review and approve bookings submitted from the landing page.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-medium text-gray-600">Filter:</span>
            <a href="{{ route('appointments.online', ['status' => \App\Models\Appointment::STATUS_BOOKED]) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg border {{ $status === \App\Models\Appointment::STATUS_BOOKED ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                Pending
            </a>
            <a href="{{ route('appointments.online', ['status' => \App\Models\Appointment::STATUS_CONFIRMED]) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg border {{ $status === \App\Models\Appointment::STATUS_CONFIRMED ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                Approved
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Patient</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Phone</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Service</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Date & Time</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Booked At</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($appointments as $appointment)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $appointment->patient?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $appointment->patient?->phone ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $appointment->service?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $appointment->appointment_date->format('M d, Y') }}<br>
                            <span class="text-xs text-gray-500">{{ $appointment->start_time?->format('H:i') }} - {{ $appointment->end_time?->format('H:i') }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $appointment->statusColor() }}-100 text-{{ $appointment->statusColor() }}-700">
                                {{ $appointment->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $appointment->created_at?->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('appointments.show', $appointment) }}" class="px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">View</a>
                                @if ($appointment->status === \App\Models\Appointment::STATUS_BOOKED)
                                    <form method="POST" action="{{ route('appointments.approve', $appointment) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-emerald-600 to-blue-600 rounded-lg hover:from-emerald-700 hover:to-blue-700 transition shadow-sm">
                                            Approve
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">
                            No {{ $status === \App\Models\Appointment::STATUS_BOOKED ? 'pending' : 'approved' }} online bookings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($appointments->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $appointments->links() }}
        </div>
    @endif
</div>
@endsection
