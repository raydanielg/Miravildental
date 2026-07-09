@extends('layouts.dashboard')

@section('title', 'SMS Templates - ' . config('app.name'))
@section('page_title', 'SMS Templates & Automation')

@section('content')
<div class="space-y-4">
    @foreach($templates as $template)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <form method="POST" action="{{ route('sms.templates.update', $template) }}">
            @csrf @method('PUT')
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900">{{ $template->name }}</h3>
                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-1.5 text-xs text-gray-600">
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
