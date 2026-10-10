@props(['icon' => null, 'label', 'href' => null, 'route' => null, 'routeParams' => [], 'method' => 'get'])

@php
    $href = $route ? route($route, $routeParams) : ($href ?? '#');
    $method = strtoupper($method);

    $submenuItemClass = 'v:flex v:items-center v:w-full v:gap-3 v:px-3 v:py-2 v:rounded v:text-sm v:text-left v:text-gray-700 v:dark:text-gray-200 v:hover:bg-gray-100 v:dark:hover:bg-gray-700 v:hover:text-gray-900 v:dark:hover:text-white';

    $submenuItemIconClass = $icon && str_starts_with($icon, 'brands:')
        ? 'fa-brands fa-' . substr($icon, 7)
        : 'fa-solid fa-' . $icon;
@endphp

@if($method === 'GET')
    <a href="{{ $href }}" role="menuitem" {{ $attributes->merge(['class' => $submenuItemClass]) }}>
        @if($icon)
            <i class="{{ $submenuItemIconClass }} v:flex-none v:w-4 v:text-center v:text-gray-400"></i>
        @endif
        <span class="v:flex-1 v:truncate">{!! $label !!}</span>
    </a>
@else
    <form method="POST" action="{{ $href }}">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif
        <button type="submit" role="menuitem" {{ $attributes->merge(['class' => $submenuItemClass]) }}>
            @if($icon)
                <i class="{{ $submenuItemIconClass }} v:flex-none v:w-4 v:text-center v:text-gray-400"></i>
            @endif
            <span class="v:flex-1 v:truncate">{!! $label !!}</span>
        </button>
    </form>
@endif
