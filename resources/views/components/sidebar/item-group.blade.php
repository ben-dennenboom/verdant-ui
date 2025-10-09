@props(['icon', 'label'])

<div x-data="{
    open: false,
    isActive: false,
    init() {
        this.isActive = Array.from(this.$el.querySelectorAll('a')).some(el => el.classList.contains('active'));
        this.open = this.isActive;
    }}"
     x-init="init()"
     class="">
    <button @click="open = !open"
            :class="isActive ?'v-text-primary-700 v-dark:v-text-primary-400' : 'v-text-gray-600 v-dark:v-text-gray-300 hover:v-text-gray-900 v-dark:hover:v-text-gray-100'"
            class="v-rounded v-flex v-items-center v-w-full v-py-3 v-px-4 hover:v-bg-gray-100 v-dark:hover:v-bg-gray-700 v-text-left v-font-medium">
        <i class="fas fa-{{ $icon }} v-flex-none v-w-6"></i>
        <span class="v-flex-1 v-ml-2">{{ $label }}</span>
        <svg xmlns="http://www.w3.org/2000/svg"
             class="v-w-5 v-flex-none v-ml-auto v-transition-transform v-transform"
             :class="open ?'v-rotate-180' : ''"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <ul x-show="open"
        class="v-ml-6 v-mt-1 v-list-none">
        {{ $slot }}
    </ul>
</div>
