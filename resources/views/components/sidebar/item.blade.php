@props(['icon', 'route' => null, 'label', 'active' => null])

@php
    $active = $active ?? ($route && request()->routeIs($route));
    $route = $route ? route($route) : '#';
@endphp

<li class="v-list-none">
    <a href="{{ $route }}"
        {{ $attributes->merge(['class' => 'v-rounded v-flex v-items-center ' . ($active ? 'active v-bg-primary-700 v-text-white' : 'v-text-gray-600') . ' v-py-3 v-px-4 hover:v-bg-gray-100 hover:v-text-gray-900']) }}>
        <i class="fas fa-{{ $icon }} v-flex-none v-w-6"></i>
        <span class="v-flex-1 v-ml-2">{{ $label }}</span>
        @if($slot->isNotEmpty())
            <i class="fas fa-chevron-down v-ml-auto"></i>
        @endif
    </a>
</li>

{{ $slot }}
