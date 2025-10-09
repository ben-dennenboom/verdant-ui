@props(['last' => false, 'route' => null, 'label' => '', 'icon' => null])

<li class="v-flex v-items-center v-m-0 v-text-gray-700 dark:v-text-gray-300">
    @if($icon)
        <i class="fas fa-{{ $icon }} v-mr-2"></i>
    @endif

    @if($route)
        <a href="{{ $route }}" class="v-inline-flex v-text-sm v-font-medium v-text-gray-700 dark:v-text-gray-300 hover:v-text-gray-900 dark:v-hover:v-text-gray-100">
            {!! $label !!}
        </a>
    @else
        <span class="v-text-sm v-font-medium v-text-gray-500 dark:v-text-gray-400">{!! $label !!}</span>
    @endif

    @if($last === false)
        <svg class="rtl:rotate-180 v-w-3 v-h-3 v-mx-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
    @endif
</li>
