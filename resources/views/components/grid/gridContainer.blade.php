@props(['title', 'actions' => null, 'class' => 'v-shadow-sm', 'columns' => 4])
@php
    $gridClasses = [
        1 => 'v-grid-cols-1',
        2 => 'v-grid-cols-2',
        3 => 'v-grid-cols-3',
        4 => 'v-grid-cols-4',
        5 => 'v-grid-cols-5',
        6 => 'v-grid-cols-6',
        7 => 'v-grid-cols-7',
        8 => 'v-grid-cols-8',
    ];
    
    $gridClass = $gridClasses[$columns] ?? null;
@endphp
<div x-data="{
    hasScroll: false,
    checkScroll() {
        const gridWrapper = this.$refs.gridWrapper;
        this.hasScroll = gridWrapper.scrollWidth > gridWrapper.clientWidth;
    }
}"
     x-init="$nextTick(() => checkScroll()); window.addEventListener('resize', () => checkScroll())"
     class="v-rounded v-bg-white dark:v-bg-gray-800 v-border v-border-gray-200 dark:v-border-gray-700 {{ $class }}">
    <!-- Scroll indicator -->
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
        <div x-ref="gridWrapper"
             @scroll.throttle.50ms="$el.scrollLeft > 0 ? $el.classList.add('is-scrolled') : $el.classList.remove('is-scrolled')"
             class="v-overflow-x-auto">
            
            <!-- Single Parent Grid -->
            <div class="v-grid v-grid-layout {{ $gridClass }}"
                 @if(!$gridClass) style="grid-template-columns: repeat({{ $columns }}, minmax(0, 1fr)); min-width: max-content;" @else style="min-width: max-content;" @endif>
                
                <!-- Header -->
                <div class="v-grid-header-wrapper" style="display: contents;">
                    {{ $header }}
                </div>
                
                <!-- Body rows -->
                <div class="v-grid-body-wrapper" style="display: contents;">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    @if(isset($pagination))
        <div class="v-px-6 v-py-4 v-border-t v-border-gray-200 dark:v-border-gray-700">
            {{ $pagination }}
        </div>
    @endif
</div>
