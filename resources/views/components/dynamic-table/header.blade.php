<div x-data="dynamicTableSort({
        columns: @js($vm->sort?->columns ?? [])
    })"
    class="v-grid v-bg-gray-50 v-relative"
    style="grid-template-columns: repeat({{ $vm->columnCount }}, minmax(0,1fr));"
>
    @foreach ($vm->headers as $header)
        <div class="v-px-6 v-py-3 v-text-md v-font-semibold {{ $header['class'] ?? '' }}">
            @if (!empty($header['sortable']) && !empty($header['key']))
                <button
                    type="button"
                    title="Click to sort"
                    class="v-inline-flex v-items-center v-gap-1 hover:v-underline"
                    @click="toggle('{{ $header['key'] }}')"
                >
                    {{ $header['label'] }}

                    <template x-if="isActive('{{ $header['key'] }}')">
                        <i
                            class="v-text-xs fa"
                            :class="directionFor('{{ $header['key'] }}') === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'"
                        ></i>
                    </template>
                </button>
            @else
                {{ $header['label'] }}
            @endif
        </div>
    @endforeach

        <template x-if="hasSorting()">
            <button
                type="button"
                class="
                    v-absolute v-right-3 v-top-1/2 -v-translate-y-1/2
                    v-text-gray-400 hover:v-text-red-500
                "
                title="Reset sorting"
                @click.stop="reset"
            >
                <i class="fa fa-xmark"></i>
            </button>
        </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dynamicTableSort', (initial) => ({
            columns: initial.columns ?? [],

            isActive(key) {
                return this.columns.some(c => c.key === key);
            },

            directionFor(key) {
                return this.columns.find(c => c.key === key)?.direction ?? null;
            },

            toggle(key) {
                const existing = [...this.columns];
                const idx = existing.findIndex(c => c.key === key);

                const nextDir = idx === -1
                    ? 'asc'
                    : existing[idx].direction === 'asc' ? 'desc' : 'asc';

                if (idx !== -1) {
                    existing.splice(idx, 1);
                }

                existing.push({ key, direction: nextDir });

                this.columns = existing;
                this.navigate();
            },

            navigate() {
                const params = new URLSearchParams(window.location.search);

                if (this.columns.length) {
                    params.set('sort', this.columns.map(c => c.key).join(','));
                    params.set('direction', this.columns.map(c => c.direction).join(','));
                } else {
                    params.delete('sort');
                    params.delete('direction');
                }

                window.location.search = params.toString();
            },

            hasSorting() {
                return this.columns.length > 0
            },

            reset() {
                this.columns = [];
                this.navigate();
            },
        }))
    })
</script>
