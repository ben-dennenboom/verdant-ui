@props(['sortable' => false, 'field' => '', 'title' => '', 'filter' => request()->get('filter', [])])

@php
    $sortField = $filter['order'] ?? null;
    $sortDirection = $filter['order_direction'] ?? null;
    $nextDirection = (isset($filter['order']) && $filter['order'] === $field && $sortDirection === 'asc') ? 'desc' : 'asc';
@endphp

<th {{ $attributes->merge(['class' => 'v-px-6 v-py-3 v-text-left v-font-medium v-text-sm v-text-muted-foreground v-uppercase v-tracking-wider']) }}>
    @if($sortable)
        <a href="{{ route(Route::currentRouteName(), array_merge(Route::current()->parameters(), request()->all(), ['filter[order]' => $field, 'filter[order_direction]' => $nextDirection])) }}"
           class="v-group v-inline-flex v-items-center v-space-x-1">
            <span>{{ $title }}</span>
            <span class="v-relative v-flex v-items-center">
                @if($sortField === $field)
                    @if($sortDirection === 'asc')
                        <svg class="v-w-3 v-h-3 v-text-foreground" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd">
                            </path>
                        </svg>
                    @else
                        <svg class="v-w-3 v-h-3 v-text-foreground" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z"
                                  clip-rule="evenodd">
                            </path>
                        </svg>
                    @endif
                @else
                    <svg class="v-w-3 v-h-3 v-text-muted-foreground v-opacity-0 group-hover:v-opacity-100" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                              clip-rule="evenodd">
                        </path>
                    </svg>
                @endif
            </span>
        </a>
    @else
        <span>{{ $title }}</span>
    @endif
</th>
