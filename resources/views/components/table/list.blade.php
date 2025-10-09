@props(['title', 'actions' => null, 'class' => 'v-shadow-sm'])

<div x-data="{
    hasScroll: false,
    checkScroll() {
        const tableWrapper = this.$refs.tableWrapper;
        this.hasScroll = tableWrapper.scrollWidth > tableWrapper.clientWidth;
    }
}"
     x-init="$nextTick(() => checkScroll()); window.addEventListener('resize', () => checkScroll())"
     class="v-rounded v-bg-white dark:v-bg-gray-800 v-border v-border-gray-200 dark:v-border-gray-700 {{ $class }}">

    <div x-show="hasScroll"
         x-transition:enter="v-transition v-ease-out v-duration-300"
         x-transition:enter-start="v-opacity-0"
         x-transition:enter-end="v-opacity-100"
         x-transition:leave="v-transition v-ease-in v-duration-200"
         x-transition:leave-start="v-opacity-100"
         x-transition:leave-end="v-opacity-0"
         class="v-px-4 v-py-2 v-bg-primary-100 dark:v-bg-primary-100 v-border-b v-border-primary-100 dark:v-border-primary-100 v-text-sm v-text-primary-700 dark:v-text-primary-700 v-text-right">
        <i class="fas fa-long-arrow-right v-mr-2"></i>
    </div>

    <div>
        <div x-ref="tableWrapper"
             @scroll.throttle.50ms="$el.scrollLeft > 0 ? $el.classList.add('is-scrolled') : $el.classList.remove('is-scrolled')"
             class="v-overflow-x-auto">
            <table class="v-min-w-full v-divide-y v-divide-gray-200 dark:v-divide-gray-700">
                <thead class="v-bg-gray-50 dark:v-bg-gray-700">
                {{ $header }}
                </thead>
                <tbody class="v-bg-white dark:v-bg-gray-800 v-divide-y v-divide-gray-200 dark:v-divide-gray-700">
                {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($pagination))
        <div class="v-px-6 v-py-4 v-border-t v-border-gray-200 dark:v-border-gray-700">
            {{ $pagination }}
        </div>
    @endif
</div>
