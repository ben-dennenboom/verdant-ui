@props(['icon', 'label', 'count' => null])

<div x-data="{
    open: false,
    isActive: false,
    animate: false,
    init() {
        this.isActive = Array.from(this.$el.querySelectorAll('a')).some(el => el.classList.contains('active'));
        this.open = this.isActive;
        this.$nextTick(() => this.animate = true);
    }}"
     x-init="init()"
     class="">
    <button @click="if ($el.closest('.v\\:sidebar[data-collapsed=true]')) { open = true; $dispatch('v-sidebar-expand') } else { open = !open }"
            :class="isActive
            ? 'v:text-primary-700 v:dark:text-primary-400 v:group-data-[collapsed=true]/sidebar:bg-primary-700 v:dark:group-data-[collapsed=true]/sidebar:bg-primary-500 v:group-data-[collapsed=true]/sidebar:text-white v:dark:group-data-[collapsed=true]/sidebar:text-white v:group-data-[collapsed=true]/sidebar:hover:bg-primary-800 v:dark:group-data-[collapsed=true]/sidebar:hover:bg-primary-700'
            : 'v:text-gray-600 v:dark:text-gray-300 v:hover:text-gray-900 v:dark:hover:text-gray-100'"
            class="v:relative v:rounded v:flex v:items-center v:w-full v:py-3 v:px-4 v:hover:bg-gray-100 v:dark:hover:bg-gray-700 v:text-left v:font-medium">
        @php
            $iconGroupClass = str_starts_with($icon, 'brands:')
                ? 'fa-brands fa-' . substr($icon, 7)
                : 'fa-solid fa-' . $icon;
        @endphp
        <i class="{{ $iconGroupClass }} v:flex-none v:w-6 v:text-center"></i>
        <span class="v:sidebar-label v:flex-1 v:ml-2">{!! $label !!}</span>
        @if($count)
            <span class="v:sidebar-expanded-only v:mx-2 v:min-w-6 v:px-1 v:py-0.5 v:rounded-lg v:bg-primary-600 v:text-xs v:font-semibold v:border v:border-primary-700 v:text-center v:text-white">{{ $count }}</span>
            <span class="v:sidebar-collapsed-only v:absolute v:top-2 v:left-10 v:size-2 v:rounded-full v:border v:border-white"
                  :class="isActive ? 'v:bg-white' : 'v:bg-primary-600'"></span>
        @endif
        <svg xmlns="http://www.w3.org/2000/svg"
             class="v:sidebar-chevron v:w-5 v:flex-none v:ml-auto"
             :class="{ 'v:rotate-180': open, 'v:transition-transform': animate }"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <ul x-show="open"
        class="v:sidebar-children v:ml-6 v:mt-1 v:list-none v:grid v:gap-1">
        {{ $slot }}
    </ul>
</div>
