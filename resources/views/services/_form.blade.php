@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-1">Service Name</label>
        <input type="text" name="name" value="{{ old('name', $service->name ?? '') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
    </div>
    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ old('description', $service->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Price (TZS)</label>
        <input type="number" name="price" value="{{ old('price', $service->price ?? 0) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Duration (minutes)</label>
        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes ?? 30) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Color</label>
        <input type="color" name="color" value="{{ old('color', $service->color ?? '#10b981') }}" class="w-full h-10 text-sm border border-gray-200 rounded-lg px-2 outline-none focus:border-emerald-500">
    </div>
    <div class="flex items-center gap-2 pt-6">
        <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $service->is_active ?? true)) class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
        <label for="is_active" class="text-xs text-gray-700">Active</label>
    </div>
</div>
<div class="mt-6 flex items-center gap-2">
    <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Service</button>
    <a href="{{ route('services.index') }}" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
</div>
