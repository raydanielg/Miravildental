@extends('layouts.dashboard')

@section('title', 'Staff & Roles - ' . config('app.name'))
@section('page_title', 'Staff & Roles')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-900">Staff Members</h3>
        <a href="{{ route('staff.create') }}" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Staff
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                <th class="px-5 py-2.5 font-medium">Name</th>
                <th class="px-5 py-2.5 font-medium">Email</th>
                <th class="px-5 py-2.5 font-medium">Role</th>
                <th class="px-5 py-2.5 font-medium">Joined</th>
                <th class="px-5 py-2.5 font-medium text-right">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($staff as $user)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-2.5 text-xs text-gray-900 font-medium">{{ $user->name }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-2.5"><span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $user->role === 'admin' ? 'emerald' : ($user->role === 'doctor' ? 'sky' : 'gold') }}-50 text-{{ $user->role === 'admin' ? 'emerald' : ($user->role === 'doctor' ? 'sky' : 'gold') }}-700 border border-{{ $user->role === 'admin' ? 'emerald' : ($user->role === 'doctor' ? 'sky' : 'gold') }}-100 capitalize">{{ $user->role }}</span></td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-2.5 text-right">
                        <a href="{{ route('staff.edit', $user) }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700 mr-2">Edit</a>
                        <form method="POST" action="{{ route('staff.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete this staff member?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-700">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400 text-xs">No staff found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($staff->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $staff->links() }}</div>
    @endif
</div>
@endsection
