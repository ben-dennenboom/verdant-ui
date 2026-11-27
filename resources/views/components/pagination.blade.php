@props(['paginator'])

@php
    $isLengthAware = $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $itemClass = "v:border-r v:bg-transparent v:border-primary-700 v:dark:border-primary-600 v:text-primary-700 v:dark:text-primary-400 v:hover:bg-primary-700 v:dark:hover:text-black v:dark:hover:bg-primary-500 v:hover:text-white v:focus:ring-primary-500";
    $linkClass = "v:px-3 v:py-2 v:w-full v:h-full v:flex v:items-center v:justify-center";
    $previousUrl = $paginator->previousPageUrl() ?? null;
    $nextUrl = $paginator->nextPageUrl() ?? null;
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" {{ $attributes->class(['v:flex v:items-center v:justify-between v:font-medium v:text-sm']) }}>
        <div class="v:grid grid-cols-2 v:gap-6 v:w-fit v:mx-auto {{ $isLengthAware ? 'v:lg:hidden' : '' }}">
            <x-v-button.primary :href="$previousUrl" :disabled="!$previousUrl" class="v:text-sm">
                {!! __('pagination.previous') !!}
            </x-v-button.primary>
            <x-v-button.primary :href="$nextUrl" :disabled="!$nextUrl" class="v:text-sm">
                {!! __('pagination.next') !!}
            </x-v-button.primary>
        </div>

        @if($isLengthAware)
            @php
                $window = \Illuminate\Pagination\UrlWindow::make($paginator->onEachSide(1));
                $elements = array_filter([
                    $window['first'],
                    is_array($window['slider']) ? '...' : null,
                    $window['slider'],
                    is_array($window['last']) ? '...' : null,
                    $window['last'],
                ]);

                $linkClass = 'v:inline-flex v:items-center v:justify-center v:min-w-[2rem] v:h-8 v:px-2 v:rounded v:text-sm';
            @endphp

            <span class="v:text-gray-500 v:dark:text-gray-400">
                @if ($isLengthAware && $paginator->total() > 0)
                    {{ __('verdant::pagination.showing', ['from' => $paginator->firstItem(), 'to' => $paginator->lastItem(), 'total' => $paginator->total()]) }}
                @endif
            </span>

            <div class="v:flex v:items-center v:gap-1">
                @if ($paginator->onFirstPage())
                    <span class="{{ $linkClass }} v:text-gray-300 v:dark:text-gray-600" aria-hidden="true"><i class="fa-solid fa-chevron-left v:text-xs"></i></span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $linkClass }} v:text-gray-600 v:dark:text-gray-300 v:hover:bg-gray-100 v:dark:hover:bg-gray-700" aria-label="{{ __('verdant::table.previous') }}">
                        <i class="fa-solid fa-chevron-left v:text-xs"></i>
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="{{ $linkClass }} v:hidden v:sm:inline-flex v:text-gray-400">…</span>
                    @else
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="{{ $linkClass }} v:bg-primary-600 v:font-semibold v:text-white" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $linkClass }} v:hidden v:sm:inline-flex v:text-gray-600 v:dark:text-gray-300 v:hover:bg-gray-100 v:dark:hover:bg-gray-700" aria-label="{{ __('verdant::table.go_to_page', ['page' => $page]) }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $linkClass }} v:text-gray-600 v:dark:text-gray-300 v:hover:bg-gray-100 v:dark:hover:bg-gray-700" aria-label="{{ __('verdant::table.next') }}">
                        <i class="fa-solid fa-chevron-right v:text-xs"></i>
                    </a>
                @else
                    <span class="{{ $linkClass }} v:text-gray-300 v:dark:text-gray-600" aria-hidden="true"><i class="fa-solid fa-chevron-right v:text-xs"></i></span>
                @endif
            </div>
        @endif
    </nav>
@endif
