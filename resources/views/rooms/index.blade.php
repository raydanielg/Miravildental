@extends('layouts.dashboard')

@section('title', 'Rooms - ' . config('app.name'))
@section('page_title', 'Rooms / Chairs')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-900">Rooms & Chairs</h3>
        <a href="{{ route('rooms.create') }}" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Room
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                <th class="px-5 py-2.5 font-medium">Name</th>
                <th class="px-5 py-2.5 font-medium">Type</th>
                <th class="px-5 py-2.5 font-medium">Description</th>
                <th class="px-5 py-2.5 font-medium">Status</th>
                <th class="px-5 py-2.5 font-medium text-right">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($rooms as $room)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-2.5 text-xs text-gray-900 font-medium">{{ $room->name }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600 capitalize">{{ $room->type ?? '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $room->description ?? '-' }}</td>
                    <td class="px-5 py-2.5">
                        @if($room->is_active)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-600">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-2.5 text-right">
                        <a href="{{ route('rooms.edit', $room) }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400 text-xs">No rooms found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rooms->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $rooms->links() }}</div>
    @endif
</div>
@endsection
