@props(['icon' => null, 'label', 'href' => null, 'route' => null, 'routeParams' => [], 'method' => 'get', 'count' => null, 'active' => null])

@php
    $active = $active ?? ($route && request()->routeIs($route));
    $href = $route ? route($route, $routeParams) : ($href ?? '#');
    $method = strtoupper($method);

    $submenuItemClass = 'v:flex v:items-center v:w-full v:gap-3 v:px-3 v:py-2 v:rounded v:text-sm v:text-left ' . ($active
        ? 'active v:bg-primary-50 v:dark:bg-primary-900/40 v:text-primary-700 v:dark:text-primary-300 v:font-medium v:hover:bg-primary-100 v:dark:hover:bg-primary-900/60'
        : 'v:text-gray-700 v:dark:text-gray-200 v:hover:bg-gray-100 v:dark:hover:bg-gray-700 v:hover:text-gray-900 v:dark:hover:text-white');

    $submenuItemIconClass = ($icon && str_starts_with($icon, 'brands:')
        ? 'fa-brands fa-' . substr($icon, 7)
        : 'fa-solid fa-' . $icon) . ' v:flex-none v:w-4 v:text-center ' . ($active ? 'v:text-primary-600 v:dark:text-primary-400' : 'v:text-gray-400');

    $submenuItemCountClass = 'v:flex-none v:min-w-5 v:px-1.5 v:py-0.5 v:rounded-lg v:text-xs v:font-semibold v:text-center ' . ($active
        ? 'v:bg-primary-600 v:text-white'
        : 'v:bg-primary-100 v:dark:bg-primary-900/60 v:text-primary-700 v:dark:text-primary-300');
@endphp

@if($method === 'GET')
    <a href="{{ $href }}" role="menuitem" {{ $attributes->merge(['class' => $submenuItemClass]) }}>
        @if($icon)
            <i class="{{ $submenuItemIconClass }}"></i>
        @endif
        <span class="v:flex-1 v:truncate">{!! $label !!}</span>
        @if($count)
            <span class="{{ $submenuItemCountClass }}">{{ $count }}</span>
        @endif
    </a>
@else
    <form method="POST" action="{{ $href }}">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif
        <button type="submit" role="menuitem" {{ $attributes->merge(['class' => $submenuItemClass]) }}>
            @if($icon)
                <i class="{{ $submenuItemIconClass }}"></i>
            @endif
            <span class="v:flex-1 v:truncate">{!! $label !!}</span>
            @if($count)
                <span class="{{ $submenuItemCountClass }}">{{ $count }}</span>
            @endif
        </button>
    </form>
@endif