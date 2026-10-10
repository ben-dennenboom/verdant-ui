@props(['storeKey', 'modalId'])

<template x-teleport="body">
    <x-v-modal :id="$modalId" maxWidth="md">
        <div
            x-data="verdantColumnReorder({ storeKey: @js($storeKey) })"
            x-on:open-modal.window="if ($event.detail === '{{ $modalId }}') syncFromStore()"
        >
            <h3 class="v:text-lg v:font-medium v:leading-6 v:text-gray-900 v:dark:text-gray-100" id="{{ $modalId }}-title">
                Change column order
            </h3>

            <div class="v:text-sm v:text-gray-500 v:dark:text-gray-400 v:mt-1 v:mb-4">
                Drag and drop columns to reorder them.
            </div>

            <div class="v:space-y-2 v:max-h-96 v:overflow-auto">
                <template x-for="(col, index) in orderedColumns" :key="col.key">
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
                                :disabled="index === orderedColumns.length - 1"
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
                    Reset
                </x-v-button.transparent>

                <div class="v:flex v:gap-2">
                    <x-v-button.light
                        type="button"
                        @click="$dispatch('close-modal')"
                        class="v:border-gray-500 v:dark:border-gray-600 v:text-gray-700 v:dark:text-gray-300 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:focus:ring-gray-500"
                    >
                        Cancel
                    </x-v-button.light>

                    <x-v-button.primary type="button" @click="save(); $dispatch('close-modal')">
                        Save order
                    </x-v-button.primary>
                </div>
            </div>
        </div>
    </x-v-modal>
</template>
