@extends('layouts.dashboard')

@section('title', 'My Profile - ' . config('app.name'))
@section('page_title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('staff.profile.update') }}">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
                <input type="text" value="{{ ucfirst($user->role) }}" disabled class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 text-gray-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="password" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Update Profile</button>
        </div>
    </form>
</div>
@endsection
