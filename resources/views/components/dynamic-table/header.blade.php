<div
    class="v-grid v-bg-gray-50"
    style="grid-template-columns: repeat({{ $vm->columnCount }}, minmax(0,1fr));"
>
    @foreach ($vm->headers as $header)
        <div class="v-px-6 v-py-3 v-text-md v-font-semibold {{ $header['class'] ?? '' }}">
            {{ $header['label'] }}
        </div>
    @endforeach
</div>
