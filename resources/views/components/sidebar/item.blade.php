@props(['icon', 'route' => null, 'routeParams' => [], 'href' => null, 'label', 'active' => null, 'collapsed' => false, 'count' => null])

@php
    $active = $active ?? ($route && request()->routeIs($route));
    $route = $route ? route($route, $routeParams) : ($href ?? '#');
@endphp

<li class="v:relative v:list-none">
    <a href="{{ $route }}"{{
          $attributes->merge(
            [
                'class' => 'v:group v:rounded v:flex v:items-center v:py-3 v:px-4 ' . ($active ? 'active v:bg-primary-700 v:dark:bg-primary-500 v:text-white v:hover:bg-primary-800 v:dark:hover:bg-primary-700 v:hover:text-white' : 'v:text-gray-600 v:dark:text-gray-300 v:hover:bg-gray-100 v:dark:hover:bg-gray-700 v:hover:text-gray-900 v:dark:hover:text-gray-100'),
            ]
          )
    }}>
        @php
            $sidebarItemIconClass = str_starts_with($icon, 'brands:')
                ? 'fa-brands fa-' . substr($icon, 7)
                : 'fa-solid fa-' . $icon;
        @endphp
        <i class="{{ $sidebarItemIconClass }} v:flex-none v:w-6 v:text-center v:mx-auto"></i>
        <span @class(
        [
            "v:sidebar-label v:flex-1 v:ml-2 v:whitespace-nowrap v:overflow-hidden",
            "hidden" => $collapsed
        ])>
            {!! $label !!}
        </span>
        @isset($count)
            <span @class([
                'v:sidebar-expanded-only v:mx-2 v:min-w-6 v:px-1 v:py-0.5 v:rounded-lg v:bg-white/80 v:text-xs v:font-bold v:text-center',
                $active ? 'v:text-primary-700' : 'v:text-primary-500',
            ])>{{ $count }}</span>
            <span @class([
                'v:sidebar-collapsed-only v:absolute v:top-2 v:left-10 v:size-2 v:rounded-full v:border v:border-white',
                $active ? 'v:bg-white' : 'v:bg-primary-500',
            ])></span>
        @endisset
    @if($slot->isNotEmpty())
            <i class="v:sidebar-chevron fas fa-chevron-down v:ml-auto"></i>
        @endif
    </a>
</li>

{{ $slot }}
