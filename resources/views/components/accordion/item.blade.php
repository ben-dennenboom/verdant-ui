@props(['title', 'index' => 0])

<div class="v-bg-white dark:v-bg-gray-800">
    <button type="button"
            @click="toggle({{ $index }})"
            class="v-flex v-w-full v-items-center v-justify-between v-px-4 v-py-4 v-text-left v-font-medium v-text-gray-900 dark:v-text-white hover:v-bg-gray-50 dark:hover:v-bg-gray-700 v-transition-colors"
            :aria-expanded="isOpen({{ $index }})"
            :aria-controls="'accordion-panel-{{ $index }}'"
            :id="'accordion-trigger-{{ $index }}'"
    >
        <span>{{ $title }}</span>
        <i class="fa-solid fa-chevron-down v-flex-shrink-0 v-transition-transform v-duration-200"
           :class="isOpen({{ $index }}) ? 'v-rotate-180' : ''"
        ></i>
    </button>
    <div x-show="isOpen({{ $index }})"
         x-transition
         x-cloak
         id="accordion-panel-{{ $index }}"
         role="region"
         :aria-labelledby="'accordion-trigger-{{ $index }}'"
         class="v-px-4 v-py-4 v-text-gray-600 dark:v-text-gray-400 v-bg-gray-50 dark:v-bg-gray-900/50"
    >
        {{ $slot }}
    </div>
</div>
