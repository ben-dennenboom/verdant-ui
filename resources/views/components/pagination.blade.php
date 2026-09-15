@props([
    'paginator',
    'type' => null
    ])

@php
    $itemClass = "v-border-r v-bg-transparent v-border-primary-700 dark:v-border-primary-600 v-text-primary-700 dark:v-text-primary-400 hover:v-bg-primary-700 dark:hover:v-text-black dark:hover:v-bg-primary-500 hover:v-text-white focus:v-ring-primary-500";
    $linkClass = "v-px-3 v-py-2 v-w-full v-h-full v-flex v-items-center v-justify-center";
    $previousUrl = $paginator->previousPageUrl() ?? null;
    $nextUrl = $paginator->nextPageUrl() ?? null;
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="v-flex v-items-center v-justify-between v-font-medium v-text-sm">
        <div class="v-grid grid-cols-2 v-gap-6 v-w-fit v-mx-auto {{ $type == 'default' ? 'lg:v-hidden' : '' }}">
            <x-v-button.primary :href="$previousUrl" :disabled="!$previousUrl" class="v-text-sm">
                {!! __('pagination.previous') !!}
            </x-v-button.primary>
            <x-v-button.primary :href="$nextUrl" :disabled="!$nextUrl" class="v-text-sm">
                {!! __('pagination.next') !!}
            </x-v-button.primary>
        </div>

        @if($type == 'extended' && method_exists($paginator, 'linkCollection'))
            @php
                $links = $paginator->linkCollection();
            @endphp
            <div class="v-hidden lg:v-block">
                <p class="v-py-1 v-text-gray-700 dark:v-text-gray-300">
                    {{ __('pagination.showing', [
                        'first' => $paginator->firstItem(),
                        'last' => $paginator->lastItem(),
                        'total' => $paginator->total()
                        ])
                    }}
                </p>
            </div>

            <div class="v-hidden lg:v-block">
                <ul class="v-flex v-items-center v-rounded v-border v-border-primary-700 dark:v-border-primary-600">
                    @foreach ($links as $link)
                        @if ($link === $links->first())
                            <li class="{{ $itemClass }} v-rounded-l">
                                @if($link['url'] == null)
                                    <span class="{{ $linkClass }} v-cursor-not-allowed">&laquo;</span>
                                @else
                                    <a class="{{ $linkClass }}" :href="{{ $link['url'] }}">&laquo;</a>
                                @endif
                            </li>
                        @elseif($link === $links->last())
                            <li class="{{ $itemClass }} v-rounded-r v-border-transparent">
                                @if($link['url'] == null)
                                    <span class="{{ $linkClass }} v-cursor-not-allowed">&raquo;</span>
                                @else
                                    <a class="{{ $linkClass }}" href="{{ $link['url'] }}">&raquo;</a>
                                @endif
                            </li>
                        @elseif ($link['url'] == null)
                            <li class="{{ $itemClass }}">
                                <span class="{{ $linkClass }}">{{ $link['label'] }}</span>
                            </li>
                        @elseif ($link['active'])
                            <li class="{{ $itemClass }}">
                                <span class="{{ $linkClass }} v-bg-primary-600 v-text-white">{{ $link['label'] }}</span>
                            </li>
                        @else
                            <li class="{{ $itemClass }}">
                                <a class="{{ $linkClass }}" href="{{ $link['url'] }}">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
    </nav>
@endif
