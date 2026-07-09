@php
    $items = [
        ['route' => 'settings.clinic', 'label' => 'Clinic Profile', 'key' => 'clinic'],
        ['route' => 'settings.working-hours', 'label' => 'Working Hours', 'key' => 'working-hours'],
        ['route' => 'settings.sms', 'label' => 'SMS Settings', 'key' => 'sms'],
        ['route' => 'settings.account', 'label' => 'Account', 'key' => 'account'],
    ];
@endphp
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-2 mb-6">
    <nav class="flex flex-wrap gap-1">
        @foreach($items as $item)
        <a href="{{ route($item['route']) }}" class="px-4 py-2 text-xs font-medium rounded-lg {{ $active === $item['key'] ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            {{ $item['label'] }}
        </a>
        @endforeach
    </nav>
</div>
