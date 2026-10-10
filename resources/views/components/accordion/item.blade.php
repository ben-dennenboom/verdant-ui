@props(['title', 'index' => 0])

<div class="v:bg-surface">
    <button type="button"
            @click="toggle({{ $index }})"
            class="v:flex v:w-full v:items-center v:justify-between v:px-4 v:py-4 v:text-left v:font-medium v:text-gray-900 v:dark:text-white v:hover:bg-gray-50 v:dark:hover:bg-gray-700 v:transition-colors"
            :aria-expanded="isOpen({{ $index }})"
            :aria-controls="'accordion-panel-{{ $index }}'"
            :id="'accordion-trigger-{{ $index }}'"
    >
        <span>{{ $title }}</span>
        <i class="fa-solid fa-chevron-down v:shrink-0 v:transition-transform v:duration-200"
           :class="isOpen({{ $index }}) ? 'v:rotate-180' : ''"
        ></i>
    </button>
    <div x-show="isOpen({{ $index }})"
         x-transition
         x-cloak
         id="accordion-panel-{{ $index }}"
         role="region"
         :aria-labelledby="'accordion-trigger-{{ $index }}'"
         class="v:px-4 v:py-4 v:text-gray-600 v:dark:text-gray-400 v:bg-gray-50 v:dark:bg-gray-900/50"
    >
        {{ $slot }}
    </div>
</div>
