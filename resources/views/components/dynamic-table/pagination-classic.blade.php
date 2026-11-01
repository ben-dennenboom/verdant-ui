@if ($vm->paginator && $vm->paginator->hasPages())
    <div class="v:flex v:items-center v:justify-between v:border-t v:border-gray-200 v:dark:border-gray-700 v:px-4 v:py-3 v:text-sm">
        {{-- Prev --}}
        @if ($vm->paginator->onFirstPage())
            <span class="v:text-gray-400 v:dark:text-gray-500">{{ __('verdant::table.previous') }}</span>
        @else
            <a
                href="{{ $vm->paginator->previousPageUrl() }}"
                class="v:text-primary v:hover:underline"
            >
                {{ __('verdant::table.previous') }}
            </a>
        @endif

        {{-- Page info --}}
        <span class="v:text-gray-500 v:dark:text-gray-400">
            {{ __('verdant::table.page_of', ['page' => $vm->paginator->currentPage(), 'last' => $vm->paginator->lastPage()]) }}
        </span>

        {{-- Next --}}
        @if ($vm->paginator->hasMorePages())
            <a
                href="{{ $vm->paginator->nextPageUrl() }}"
                class="v:text-primary v:hover:underline"
            >
                {{ __('verdant::table.next') }}
            </a>
        @else
            <span class="v:text-gray-400 v:dark:text-gray-500">{{ __('verdant::table.next') }}</span>
        @endif
    </div>
@endif
