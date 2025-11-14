@props(['icon', 'route' => null, 'routeParams' => [], 'label', 'active' => null])

@php
  $active = $active ?? ($route && request()->routeIs($route));
  $route = $route ? route($route, $routeParams) : '#';
@endphp

<li class="v-list-none">
  <a href="{{ $route }}"{{
          $attributes->merge(
            [
                'class' => 'v-rounded v-flex v-items-center v-py-3 v-px-4 ' . ($active ? 'active v-bg-primary-700 dark:v-bg-primary-100 v-text-white hover:v-bg-primary-800 dark:hover:v-bg-primary-700 hover:v-text-white dark:hover:v-text-black' : 'v-text-gray-600 dark:v-text-gray-300 hover:v-bg-gray-100 dark:hover:v-bg-gray-700 hover:v-text-gray-900 dark:hover:v-text-gray-100'),
            ]
          )
    }}>
    <i class="fas fa-{{ $icon }} v-flex-none v-w-6"></i>
    <span class="v-flex-1 v-ml-2">{{ $label }}</span>
    @if($slot->isNotEmpty())
      <i class="fas fa-chevron-down v-ml-auto"></i>
    @endif
  </a>
</li>

{{ $slot }}
