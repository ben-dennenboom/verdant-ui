@if ($vm->paginator && $vm->paginator->hasPages())
    <div class="v-flex v-items-center v-justify-between v-border-t v-px-4 v-py-3 v-text-sm">
        {{-- Prev --}}
        @if ($vm->paginator->onFirstPage())
            <span class="v-text-gray-400">Previous</span>
        @else
            <a
                href="{{ $vm->paginator->previousPageUrl() }}"
                class="v-text-primary hover:v-underline"
            >
                Previous
            </a>
        @endif

        {{-- Page info --}}
        <span class="v-text-gray-500">
            Page {{ $vm->paginator->currentPage() }}
            of {{ $vm->paginator->lastPage() }}
        </span>

        {{-- Next --}}
        @if ($vm->paginator->hasMorePages())
            <a
                href="{{ $vm->paginator->nextPageUrl() }}"
                class="v-text-primary hover:v-underline"
            >
                Next
            </a>
        @else
            <span class="v-text-gray-400">Next</span>
        @endif
    </div>
@endif
