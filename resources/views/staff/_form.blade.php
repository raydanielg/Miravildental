@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Full Name</label>
        <input type="text" name="name" value="{{ old('name', $staff->name ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $staff->email ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $staff->phone ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
        <select name="role" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
            <option value="">Select role</option>
            <option value="admin" @selected(old('role', $staff->role ?? '') == 'admin')>Admin</option>
            <option value="doctor" @selected(old('role', $staff->role ?? '') == 'doctor')>Doctor</option>
            <option value="reception" @selected(old('role', $staff->role ?? '') == 'reception')>Reception</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Password {{ isset($staff) ? '(leave blank to keep current)' : '' }}</label>
        <input type="password" name="password" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" {{ isset($staff) ? '' : 'required' }}>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" {{ isset($staff) ? '' : 'required' }}>
    </div>
</div>
<div class="mt-6 flex items-center gap-2">
    <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Staff</button>
    <a href="{{ route('staff.index') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
</div>
