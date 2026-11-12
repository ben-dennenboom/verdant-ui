@props(['vm', 'hasFilter' => true])

@php
    $columnsPanelId = 'columns-panel-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', $vm->columnVisibilityKey);
    $popupPosition = $hasFilter ? 'v:left-1/2 v:-translate-x-1/2' : '';
    $reorderModalId = 'column-order-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', $vm->columnVisibilityKey);
@endphp

<div class="v:shrink-0 v:flex v:items-center v:justify-end">
    <div
        class="v:relative"
        x-data="verdantColumnPicker({
            saveUrl: @js($vm->preferencesSaveUrl),
            csrfToken: @js(csrf_token()),
            orderEnabled: @js($vm->columnOrderEnabled),
            reorderModalId: @js($reorderModalId),
        })"
        @click.outside="open = false"
    >
        <x-v-button.light
            type="button"
            icon-right="angle-down"
            aria-label="{{ __('verdant::table.choose_visible_columns') }}"
            aria-haspopup="true"
            x-bind:aria-expanded="open"
            aria-controls="{{ $columnsPanelId }}"
            @click="open = !open"
            class="v:text-sm v:border-gray-500 v:dark:border-gray-600 v:text-gray-700 v:dark:text-gray-300 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:focus:ring-gray-500 v:whitespace-nowrap"
        >
            <i class="fas fa-gear v:mr-1"></i>
        </x-v-button.light>

        <template x-if="open">
            <div
                id="{{ $columnsPanelId }}"
                role="region"
                aria-label="{{ __('verdant::table.visible_columns') }}"
                class="v:absolute {{ $popupPosition }} v:md:left-auto v:md:right-0 v:md:translate-x-0 v:z-20 v:mt-2 {{ config('verdant.ui.wide_column_picker') ? 'v:w-72 v:max-w-[calc(100vw-2rem)]' : 'v:w-60' }} v:rounded-md v:border v:border-gray-200 v:dark:border-gray-600 v:bg-floating v:p-3 v:shadow-lg"
            >
                <div class="v:flex v:items-center v:justify-between {{ config('verdant.ui.wide_column_picker') ? 'v:gap-2 ' : '' }}v:pb-2">
                    <div class="{{ config('verdant.ui.wide_column_picker') ? 'v:whitespace-nowrap ' : '' }}v:text-sm v:font-semibold v:text-gray-900 v:dark:text-gray-100">{{ __('verdant::table.visible_columns') }}</div>
                    <x-v-button.transparent
                        type="button"
                        class="v:text-xs v:text-gray-600 v:dark:text-gray-400 v:hover:underline"
                        @click="reset(); open = false"
                    >
                        {{ __('verdant::table.reset') }}
                    </x-v-button.transparent>
                </div>

                <div class="v:max-h-64 v:space-y-2 v:overflow-auto v:pl-1 v:pt-1 v:scrollbar-thin">
                    <template x-for="col in columns" :key="col.key">
                        <label class="v:flex v:items-center v:gap-2 v:text-sm v:cursor-pointer">
                            <input
                                type="checkbox"
                                class="v:rounded v:border-secondary-300 v:dark:border-gray-600 v:text-primary-600 v:focus:ring-primary-500 v:focus:border-primary-500 v:shadow-sm v:sm:text-sm v:not-checked:bg-white v:dark:not-checked:bg-gray-800"
                                :checked="col.visible"
                                @change="setVisible(col.key, $event.target.checked)"
                                :disabled="col.pinned"
                            />
                            <span :class="col.pinned ? 'v:text-gray-400 v:dark:text-gray-500' : 'v:text-gray-700 v:dark:text-gray-300'" x-text="col.label"></span>
                        </label>
                    </template>
                </div>

                <div class="v:mt-3 v:flex v:gap-2">
                    <x-v-button.light
                        type="button"
                        outline
                        class="v:flex-1 {{ config('verdant.ui.wide_column_picker') ? 'v:min-w-0 v:px-2! ' : '' }}v:text-sm v:border-gray-500 v:dark:border-gray-600 v:text-gray-700 v:dark:text-gray-300 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:focus:ring-gray-500 v:whitespace-nowrap"
                        @click="showAll()"
                    >
                        {{ __('verdant::table.show_all') }}
                    </x-v-button.light>

                    <x-v-button.light
                        type="button"
                        outline
                        class="v:flex-1 {{ config('verdant.ui.wide_column_picker') ? 'v:min-w-0 v:px-2! ' : '' }}v:text-sm v:border-gray-500 v:dark:border-gray-600 v:text-gray-700 v:dark:text-gray-300 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:focus:ring-gray-500 v:whitespace-nowrap"
                        @click="hideAll()"
                    >
                        {{ __('verdant::table.hide_all') }}
                    </x-v-button.light>
                </div>

                @if($vm->columnOrderEnabled)
                    <x-v-button.light
                        type="button"
                        outline
                        class="v:mt-2 v:w-full v:text-sm v:border-gray-500 v:dark:border-gray-600 v:text-gray-700 v:dark:text-gray-300 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:focus:ring-gray-500 v:whitespace-nowrap"
                        aria-haspopup="dialog"
                        aria-controls="{{ $reorderModalId }}"
                        @click="openReorder()"
                    >
                        <i class="fas fa-arrows-up-down-left-right v:mr-1"></i>
                        {{ __('verdant::table.change_order') }}
                    </x-v-button.light>
                @endif
            </div>
        </template>

        @if($vm->columnOrderEnabled)
            <template x-teleport="body">
                <x-v-modal :id="$reorderModalId" maxWidth="md">
                    <h3 class="v:text-lg v:font-medium v:leading-6 v:text-gray-900 v:dark:text-gray-100">
                        {{ __('verdant::table.change_column_order') }}
                    </h3>

                    <div class="v:text-sm v:text-gray-500 v:dark:text-gray-400 v:mt-1 v:mb-4">
                        {{ __('verdant::table.drag_to_reorder') }}
                    </div>

                    <div class="v:space-y-2 v:max-h-96 v:overflow-auto v:scrollbar-thin">
                        <template x-for="(col, index) in draft" :key="col.key">
                            <div
                                draggable="true"
                                @dragstart="dragStart(col.key)"
                                @dragover.prevent
                                @drop.prevent="drop(col.key)"
                                class="v:flex v:items-center v:gap-3 v:p-3 v:bg-gray-50 v:dark:bg-gray-700 v:border v:border-gray-200 v:dark:border-gray-600 v:rounded v:cursor-move"
                                :class="draggingKey === col.key ? 'v:opacity-50' : ''"
                            >
                                <i class="fas fa-grip-vertical v:text-gray-400 v:dark:text-gray-500"></i>

                                <span class="v:flex-1 v:text-sm v:text-gray-900 v:dark:text-gray-100" x-text="col.label"></span>

                                <div class="v:flex v:items-center v:gap-1">
                                    <button
                                        type="button"
                                        class="v:text-gray-400 v:dark:text-gray-500 v:hover:text-gray-600 v:dark:hover:text-gray-300 v:disabled:opacity-30"
                                        :disabled="index === 0"
                                        @click="moveUp(index)"
                                    >
                                        <i class="fas fa-arrow-up"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="v:text-gray-400 v:dark:text-gray-500 v:hover:text-gray-600 v:dark:hover:text-gray-300 v:disabled:opacity-30"
                                        :disabled="index === draft.length - 1"
                                        @click="moveDown(index)"
                                    >
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="v:mt-5 v:sm:mt-4 v:flex v:justify-between">
                        <x-v-button.transparent
                            type="button"
                            class="v:text-sm v:text-gray-600 v:dark:text-gray-400 v:hover:underline"
                            @click="resetOrder()"
                        >
                            {{ __('verdant::table.reset') }}
                        </x-v-button.transparent>

                        <div class="v:flex v:gap-2">
                            <x-v-button.light
                                type="button"
                                @click="$dispatch('close-modal')"
                                class="v:border-gray-500 v:dark:border-gray-600 v:text-gray-700 v:dark:text-gray-300 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:focus:ring-gray-500"
                            >
                                {{ __('verdant::table.cancel') }}
                            </x-v-button.light>

                            <x-v-button.primary type="button" @click="saveOrder(); $dispatch('close-modal')">
                                {{ __('verdant::table.save_order') }}
                            </x-v-button.primary>
                        </div>
                    </div>
                </x-v-modal>
            </template>
        @endif
    </div>
</div>