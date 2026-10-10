@props(['icon', 'route' => null, 'routeParams' => [], 'href' => null, 'label', 'active' => null, 'collapsed' => false,])

@php
    $active = $active ?? ($route && request()->routeIs($route));
    $route = $route ? route($route, $routeParams) : ($href ?? '#');
@endphp

<li class="v:list-none">
    <a href="{{ $route }}"{{
          $attributes->merge(
            [
                'class' => 'v:rounded v:flex v:items-center v:py-3 v:px-4 ' . ($active ? 'active v:bg-primary-700 v:dark:bg-primary-500 v:text-white v:hover:bg-primary-800 v:dark:hover:bg-primary-700 v:hover:text-white' : 'v:text-gray-600 v:dark:text-gray-300 v:hover:bg-gray-100 v:dark:hover:bg-gray-700 v:hover:text-gray-900 v:dark:hover:text-gray-100'),
            ]
          )
    }}>
        @php
            $sidebarItemIconClass = str_starts_with($icon, 'brands:')
                ? 'fa-brands fa-' . substr($icon, 7)
                : 'fa-solid fa-' . $icon;
        @endphp
        <i class="{{ $sidebarItemIconClass }} v:flex-none v:w-6"></i>
        <span @class(
        [
            "v-sidebar-label v:flex-1 v:ml-2",
            "hidden" => $collapsed
        ])>
            {!! $label !!}
        </span>
        @if($slot->isNotEmpty())
            <i class="v-sidebar-chevron fas fa-chevron-down v:ml-auto"></i>
        @endif
    </a>
</li>

{{ $slot }}
