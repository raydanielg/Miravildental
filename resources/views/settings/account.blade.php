@extends('layouts.dashboard')

@section('title', 'Account Settings - ' . config('app.name'))
@section('page_title', 'Account Settings')

@section('content')
@include('settings._nav', ['active' => 'account'])

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
    {{-- View Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Account Overview</h3>
        </div>
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center text-2xl font-bold mb-3">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h4 class="text-base font-bold text-gray-900">{{ $user->name }}</h4>
            <p class="text-xs text-gray-500">{{ ucfirst($user->role) }}</p>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <span class="text-gray-500">Email</span>
                <span class="font-medium text-gray-900">{{ $user->email }}</span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <span class="text-gray-500">Phone</span>
                <span class="font-medium text-gray-900">{{ $user->phone ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <span class="text-gray-500">Member Since</span>
                <span class="font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Edit Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.432-4.364l-6.364-6.364a2.015 2.015 0 00-2.828 0l-1.768 1.768a2.015 2.015 0 000 2.828l6.364 6.364M16 7h6m-3-3v6"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Edit Account</h3>
        </div>
        <form id="accountForm" method="POST" action="{{ route('staff.profile.update') }}">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Full Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full text-sm border border-gray-200 rounded-lg pl-10 pr-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full text-sm border border-gray-200 rounded-lg pl-10 pr-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </span>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg pl-10 pr-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">New Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input type="password" name="password" placeholder="••••••••" class="w-full text-sm border border-gray-200 rounded-lg pl-10 pr-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full text-sm border border-gray-200 rounded-lg pl-10 pr-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" id="accountSaveBtn" class="w-full px-4 py-2.5 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center justify-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span id="accountBtnText">Update Account</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('accountForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('accountSaveBtn');
    const txt = document.getElementById('accountBtnText');
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
