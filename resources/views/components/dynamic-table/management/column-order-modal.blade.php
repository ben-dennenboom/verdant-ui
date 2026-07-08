@props(['storeKey', 'modalId'])

<template x-teleport="body">
    <x-v-modal :id="$modalId" maxWidth="md">
        <div
            x-data="verdantColumnReorder({ storeKey: @js($storeKey) })"
            x-on:open-modal.window="if ($event.detail === '{{ $modalId }}') syncFromStore()"
        >
            <h3 class="v-text-lg v-font-medium v-leading-6 v-text-gray-900 dark:v-text-gray-100" id="{{ $modalId }}-title">
                Change column order
            </h3>

            <div class="v-text-sm v-text-gray-500 dark:v-text-gray-400 v-mt-1 v-mb-4">
                Drag and drop columns to reorder them.
            </div>

            <div class="v-space-y-2 v-max-h-96 v-overflow-auto">
                <template x-for="(col, index) in orderedColumns" :key="col.key">
                    <div
                        draggable="true"
                        @dragstart="dragStart(col.key)"
                        @dragover.prevent
                        @drop.prevent="drop(col.key)"
                        class="v-flex v-items-center v-gap-3 v-p-3 v-bg-gray-50 dark:v-bg-gray-700 v-border v-border-gray-200 dark:v-border-gray-600 v-rounded v-cursor-move"
                        :class="draggingKey === col.key ? 'v-opacity-50' : ''"
                    >
                        <i class="fas fa-grip-vertical v-text-gray-400 dark:v-text-gray-500"></i>

                        <span class="v-flex-1 v-text-sm v-text-gray-900 dark:v-text-gray-100" x-text="col.label"></span>

                        <div class="v-flex v-items-center v-gap-1">
                            <button
                                type="button"
                                class="v-text-gray-400 dark:v-text-gray-500 hover:v-text-gray-600 dark:hover:v-text-gray-300 disabled:v-opacity-30"
                                :disabled="index === 0"
                                @click="moveUp(index)"
                            >
                                <i class="fas fa-arrow-up"></i>
                            </button>

                            <button
                                type="button"
                                class="v-text-gray-400 dark:v-text-gray-500 hover:v-text-gray-600 dark:hover:v-text-gray-300 disabled:v-opacity-30"
                                :disabled="index === orderedColumns.length - 1"
                                @click="moveDown(index)"
                            >
                                <i class="fas fa-arrow-down"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="v-mt-5 sm:v-mt-4 v-flex v-justify-between">
                <x-v-button.transparent
                    type="button"
                    class="v-text-sm v-text-gray-600 dark:v-text-gray-400 hover:v-underline"
                    @click="resetOrder()"
                >
                    Reset
                </x-v-button.transparent>

                <div class="v-flex v-gap-2">
                    <x-v-button.light
                        type="button"
                        @click="$dispatch('close-modal')"
                        class="v-border-gray-500 dark:v-border-gray-600 v-text-gray-700 dark:v-text-gray-300 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 focus:v-ring-gray-500"
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
