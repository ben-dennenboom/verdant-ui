@if ($vm->paginator && $vm->paginator->hasPages())
    @php
        $paginator = $vm->paginator;
        $isLengthAware = $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $elements = [];

        if ($isLengthAware) {
            $window = \Illuminate\Pagination\UrlWindow::make($paginator->onEachSide(1));
            $elements = array_filter([
                $window['first'],
                is_array($window['slider']) ? '...' : null,
                $window['slider'],
                is_array($window['last']) ? '...' : null,
                $window['last'],
            ]);
        }

        $linkClass = 'v-inline-flex v-items-center v-justify-center v-min-w-[2rem] v-h-8 v-px-2 v-rounded v-text-sm';
    @endphp

    <nav class="v-flex v-flex-col sm:v-flex-row v-items-center v-justify-between v-gap-3 v-border-t v-border-gray-200 dark:v-border-gray-700 v-px-4 v-py-3 v-text-sm" aria-label="{{ __('verdant::table.pagination') }}">
        <span class="v-text-gray-500 dark:v-text-gray-400">
            @if ($isLengthAware && $paginator->total() > 0)
                {{ __('verdant::table.showing', ['from' => $paginator->firstItem(), 'to' => $paginator->lastItem(), 'total' => $paginator->total()]) }}
            @endif
        </span>

        <div class="v-flex v-items-center v-gap-1">
            @if ($paginator->onFirstPage())
                <span class="{{ $linkClass }} v-text-gray-300 dark:v-text-gray-600" aria-hidden="true"><i class="fa-solid fa-chevron-left v-text-xs"></i></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $linkClass }} v-text-gray-600 dark:v-text-gray-300 hover:v-bg-gray-100 dark:hover:v-bg-gray-700" aria-label="{{ __('verdant::table.previous') }}">
                    <i class="fa-solid fa-chevron-left v-text-xs"></i>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="{{ $linkClass }} v-hidden sm:v-inline-flex v-text-gray-400">…</span>
                @else
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="{{ $linkClass }} v-bg-primary-600 v-font-semibold v-text-white" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="{{ $linkClass }} v-hidden sm:v-inline-flex v-text-gray-600 dark:v-text-gray-300 hover:v-bg-gray-100 dark:hover:v-bg-gray-700" aria-label="{{ __('verdant::table.go_to_page', ['page' => $page]) }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $linkClass }} v-text-gray-600 dark:v-text-gray-300 hover:v-bg-gray-100 dark:hover:v-bg-gray-700" aria-label="{{ __('verdant::table.next') }}">
                    <i class="fa-solid fa-chevron-right v-text-xs"></i>
                </a>
            @else
                <span class="{{ $linkClass }} v-text-gray-300 dark:v-text-gray-600" aria-hidden="true"><i class="fa-solid fa-chevron-right v-text-xs"></i></span>
            @endif
        </div>
    </nav>
@endif
