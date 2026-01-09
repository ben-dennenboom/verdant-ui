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

<div 
  x-data="gridComponent({ initialTileView: window.innerWidth <= 768 })"
  x-init="init()"
  class="v-rounded v-bg-white dark:v-bg-gray-800 v-border v-border-gray-200 dark:v-border-gray-700 {{ $class }}"
>
  <!-- HEADER BAR -->
  <div class="v-flex v-justify-between v-items-center v-px-4 v-py-2 v-border-b v-border-gray-200 dark:v-border-gray-700">
    
    <!-- Scroll indicator (only shows when there's scroll) -->
    <div class="v-flex-1">
      <div x-show="hasScroll"
           x-transition
           class="v-text-xs v-text-primary-700">
          <i class="fas fa-long-arrow-right v-mr-2"></i>
          Scroll right for more information
      </div>
    </div>
    
    <!-- Toggle button (always visible) -->
    <button 
      @click="toggleView()"
      class="v-view-toggle-btn"
      :title="tileView ? 'Switch to table view' : 'Switch to tile view'"
    >
      <i :class="tileView ? 'fas fa-table' : 'fas fa-grip-horizontal'"></i>
    </button>
  </div>
  
  <div x-ref="gridWrapper" 
       :class="tileView ? '' : 'v-overflow-x-auto'"
       class="v-overflow-y-visible">
      <div 
        class="v-grid v-grid-layout {{ $gridClass }}"
        :class="tileView ? 'v-tile-view' : 'v-table-view'"
           @if(!$gridClass) 
              :style="tileView ? '' : 'grid-template-columns: repeat({{ $columns }}, minmax(0, 1fr)); min-width: max-content;'"
           @else 
              :style="tileView ? '' : 'min-width: max-content;'"
           @endif>
          
          <div class="v-grid-header-wrapper" style="display: contents;">
              {{ $header }}
          </div>
          
          <div class="v-grid-body-wrapper" style="display: contents;">
              {{ $slot }}
          </div>
      </div>
  </div>
  @if(isset($pagination))
      <div class="v-px-6 v-py-4 v-border-t v-border-gray-200 dark:v-border-gray-700">
          {{ $pagination }}
      </div>
  @endif
</div>
