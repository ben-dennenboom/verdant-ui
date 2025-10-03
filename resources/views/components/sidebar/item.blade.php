@props(['icon', 'route' => null, 'label', 'active' => null])

@php
  $active = $active ?? ($route && request()->routeIs($route));
  $route = $route ? route($route) : '#';
@endphp

<li class="v-list-none">
  <a href="{{ $route }}"{{
          $attributes->merge(
            [
                'class' => 'v-rounded v-flex v-items-center v-py-3 v-px-4 ' . ($active ? 'active v-bg-primary-700 v-text-white hover:v-bg-primary-800 hover:v-text-white' : 'v-text-muted-foreground hover:v-bg-muted hover:v-text-foreground'),
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
