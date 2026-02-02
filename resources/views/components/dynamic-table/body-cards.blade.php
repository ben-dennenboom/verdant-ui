@forelse ($vm->rows as $row)
    <div class="v-mb-4 v-rounded-lg v-border v-bg-white v-p-4 v-shadow-sm">

        {{-- Primary title (first two non-action columns) --}}
        <div class="v-font-semibold v-text-gray-900">
            @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']))
                @if(isset($row->cells[0]))<span x-show="isVisible('{{ $vm->columnKeyForIndex(0) }}')">{{ $row->cells[0]->value ?? '' }}</span>@endif
                @if(isset($row->cells[1]))<span x-show="isVisible('{{ $vm->columnKeyForIndex(1) }}')">{{ $row->cells[1]->value ?? '' }}</span>@endif
            @else
                {{ isset($row->cells[0]) ? ($row->cells[0]->value ?? '') : '' }}
                {{ isset($row->cells[1]) ? ($row->cells[1]->value ?? '') : '' }}
            @endif
        </div>

        {{-- Remaining fields --}}
        <div class="v-mt-2 v-space-y-1 v-text-sm">
            @foreach ($row->cells as $cell)
                @continue($cell->isActions)
                @continue($loop->index < 2)

                @php
                    $columnKey = $vm->columnKeyForIndex($loop->index);
                @endphp
                <div class="v-flex v-justify-between v-gap-4"
                    @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']))
                        x-show="isVisible('{{ $columnKey }}')"
                    @endif
                >
                    <span class="v-text-gray-500">
                        {{ $vm->headerLabel($loop->index) }}
                    </span>

                    <span class="v-text-right">
                        @if ($cell->html)
                            {!! $cell->value !!}
                        @else
                            {{ $cell->value }}
                        @endif
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Actions --}}
        @php
            $actionsCell = collect($row->cells)->first(fn ($c) => $c->isActions);
        @endphp

        @if ($actionsCell)
            <div class="v-mt-3 v-flex v-justify-end v-gap-2">
                {{-- Custom render --}}
                @if ($actionsCell->value instanceof \Illuminate\Contracts\Support\Htmlable)
                    {!! $actionsCell->value->toHtml() !!}
                @endif

                {{-- Verdant-UI buttons --}}
                @foreach ($actionsCell->actions ?? [] as $action)
                    <x-dynamic-component
                        :component="$action['component']"
                        :href="$action['href']"
                    >
                        {{ $action['label'] }}
                    </x-dynamic-component>
                @endforeach
            </div>
        @endif
    </div>
@empty
    <div class="v-px-6 v-py-4 v-text-sm v-text-gray-500 dark:v-text-gray-400">
        {{ $emptyText ?? 'No data available.' }}
    </div>
@endforelse
