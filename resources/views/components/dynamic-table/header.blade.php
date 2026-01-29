<div
    class="v-grid v-bg-gray-50"
    style="grid-template-columns: repeat({{ $vm->columnCount }}, minmax(0,1fr));"
>
    @foreach ($vm->headers as $header)
        <div class="v-px-6 v-py-3 v-text-md v-font-semibold {{ $header['class'] ?? '' }}">
            @if (!empty($header['sortable']) && !empty($header['key']))
                @php
                    $isActive = $vm->sort?->key === $header['key'];
                    $direction = $isActive ? $vm->sort->toggleDirection() : 'asc';

                    $href = request()->fullUrlWithQuery([
                        'sort' => $header['key'],
                        'direction' => $direction,
                    ]);
                @endphp

                <a
                    href="{{ $href }}"
                    class="v-inline-flex v-items-center v-gap-1 hover:v-underline"
                >
                    {{ $header['label'] }}

                    @if ($isActive)
                        <i class="v-text-xs fa
                            fa-arrow-{{ $vm->sort->direction === 'asc' ? 'up' : 'down' }}
                        "></i>
                    @endif
                </a>
            @else
                {{ $header['label'] }}
            @endif
        </div>
    @endforeach
</div>
