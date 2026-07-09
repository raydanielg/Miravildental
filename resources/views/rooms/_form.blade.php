@csrf
<div class="space-y-4">
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Room / Chair Name</label>
        <input type="text" name="name" value="{{ old('name', $room->name ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Type</label>
        <select name="type" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
            <option value="">Select type</option>
            <option value="treatment" @selected(old('type', $room->type ?? '') == 'treatment')>Treatment</option>
            <option value="surgery" @selected(old('type', $room->type ?? '') == 'surgery')>Surgery</option>
            <option value="consultation" @selected(old('type', $room->type ?? '') == 'consultation')>Consultation</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('description', $room->description ?? '') }}</textarea>
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $room->is_active ?? true)) class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
        <label for="is_active" class="text-xs text-gray-700">Active</label>
    </div>
</div>
<div class="mt-6 flex items-center gap-2">
    <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Room</button>
    <a href="{{ route('rooms.index') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
</div>
