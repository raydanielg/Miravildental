@extends('layouts.dashboard')

@section('title', 'SMS Templates - ' . config('app.name'))
@section('page_title', 'SMS Templates & Automation')

@section('content')
<div class="space-y-5">
    {{-- Filter + Add --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex flex-wrap items-end justify-between gap-3">
        <form method="GET" action="{{ route('sms.templates') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Filter by Category</label>
                <select name="category" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-emerald-500">
                    <option value="all" @selected($category == 'all')>All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected($category == $cat)>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('sms.templates') }}" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Reset</a>
        </form>
        <button onclick="openNewTemplateModal()" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Template
        </button>
    </div>

    {{-- New Template Slide-over --}}
    <div id="newTemplateModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeNewTemplateModal()"></div>
        <div class="absolute inset-y-0 right-0 w-full max-w-xl bg-white shadow-2xl transform transition-transform translate-x-full duration-300 ease-in-out flex flex-col" id="newTemplateSlidePanel">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
                <h3 class="text-sm font-semibold text-gray-900">New SMS Template</h3>
                <button onclick="closeNewTemplateModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-5">
                <form method="POST" action="{{ route('sms.templates.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Trigger Key</label>
                            <input type="text" name="trigger" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                                <option value="dental">Dental</option>
                                <option value="holiday">Holiday</option>
                                <option value="promotion">Promotion</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="reminder">Reminder</option>
                                <option value="greeting">Greeting</option>
                            </select>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 text-xs font-medium text-gray-700 mb-1 mt-5">
                                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                Active
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Body</label>
                        <textarea name="body" rows="5" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Hours Before (optional)</label>
                            <input type="number" name="send_before_hours" placeholder="e.g. 24" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Days After (optional)</label>
                            <input type="number" name="send_after_days" placeholder="e.g. 7" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-6">
                        <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Template</button>
                        <button type="button" onclick="closeNewTemplateModal()" class="px-4 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($templates as $template)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <form method="POST" action="{{ route('sms.templates.update', $template) }}">
            @csrf @method('PUT')
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-semibold text-gray-900">{{ $template->name }}</h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium {{ $template->category === 'holiday' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">{{ ucfirst($template->category) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-1.5 text-xs text-gray-600 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" @checked($template->is_active) class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        Active
                    </label>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Body</label>
                    <textarea name="body" rows="3" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">{{ $template->body }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Timing</label>
                    <div class="space-y-2">
                        <input type="number" name="send_before_hours" value="{{ $template->send_before_hours }}" placeholder="Hours before" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                        <input type="number" name="send_after_days" value="{{ $template->send_after_days }}" placeholder="Days after" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Update Template</button>
                <span class="text-[10px] text-gray-400">Trigger key: {{ $template->trigger }}</span>
            </div>
        </form>
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
function openNewTemplateModal() {
    const modal = document.getElementById('newTemplateModal');
    const panel = document.getElementById('newTemplateSlidePanel');
    modal.classList.remove('hidden');
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
    document.body.style.overflow = 'hidden';
}
function closeNewTemplateModal() {
    const modal = document.getElementById('newTemplateModal');
    const panel = document.getElementById('newTemplateSlidePanel');
    panel.classList.add('translate-x-full');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
}
</script>
@endpush
