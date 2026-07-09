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
            <form method="GET" action="{{ route('patients.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search patients..." class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                <button type="submit" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>
            <a href="{{ route('patients.create') }}" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Patient
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
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
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
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
                        <a href="{{ route('patients.show', $patient) }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700 mr-3">View</a>
                        <a href="{{ route('patients.edit', $patient) }}" class="text-xs font-medium text-gray-600 hover:text-gray-800">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-xs">No patients found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $patients->links() }}</div>
    @endif
</div>
@endsection
