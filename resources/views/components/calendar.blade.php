@props([
    'selectedDate' => null,
    'events' => []
])

@php
  $selectedDate = $selectedDate ? Carbon\Carbon::parse($selectedDate) : now();

  $startOfMonth = $selectedDate->copy()->startOfMonth();

  $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon\Carbon::MONDAY);

  $endOfMonth = $selectedDate->copy()->endOfMonth();

  $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon\Carbon::SUNDAY);

  $monthName = $selectedDate->format('F');
  $year = $selectedDate->format('Y');

  $prevMonth = $selectedDate->copy()->subMonth()->format('Y-m-d');
  $nextMonth = $selectedDate->copy()->addMonth()->format('Y-m-d');

  $today = now()->format('Y-m-d');
@endphp

<div x-data="{
    selectedDay: '{{ $today }}',
    events: {{ Js::from($events) }},

    getEvents(date) {
        if (!this.events) return [];
        return this.events.filter(event => event.date === date);
    },

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            weekday: 'long',
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        });
    },

    selectDay(day) {
        this.selectedDay = day;
    }
}"
     x-init="selectDay('{{ $today }}')">

  <div class="v-shadow-sm v-border v-border-gray-200 v-bg-white v-rounded-lg v-overflow-hidden">
    <div class="v-flex v-items-center v-justify-between v-p-5 v-border-b v-border-gray-100">
      <h2 class="v-text-2xl v-font-medium v-text-gray-900">
        {{ $monthName }} {{ $year }}
      </h2>
      <div class="v-inline-flex v-items-center v-rounded-lg v-border v-border-gray-200 v-overflow-hidden">
        <a href="?date={{ $prevMonth }}" class="v-p-2 v-text-gray-500 hover:v-text-gray-700 hover:v-bg-gray-50">
          <svg class="v-w-5 v-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </a>
        <a href="?date={{ $nextMonth }}" class="v-p-2 v-text-gray-500 hover:v-text-gray-700 hover:v-bg-gray-50">
          <svg class="v-w-5 v-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </a>
      </div>
    </div>

    <div class="v-flex v-flex-col">
      <div class="v-grid v-grid-cols-7 v-border-b v-border-gray-200">
        <div class="v-py-3 v-text-center v-text-sm v-font-medium v-text-gray-700">Mon</div>
        <div class="v-py-3 v-text-center v-text-sm v-font-medium v-text-gray-700">Tue</div>
        <div class="v-py-3 v-text-center v-text-sm v-font-medium v-text-gray-700">Wed</div>
        <div class="v-py-3 v-text-center v-text-sm v-font-medium v-text-gray-700">Thu</div>
        <div class="v-py-3 v-text-center v-text-sm v-font-medium v-text-gray-700">Fri</div>
        <div class="v-py-3 v-text-center v-text-sm v-font-medium v-text-gray-700">Sat</div>
        <div class="v-py-3 v-text-center v-text-sm v-font-medium v-text-gray-700">Sun</div>
      </div>

      <div class="v-grid v-grid-cols-7 v-divide-x v-divide-gray-200 v-divide-y v-flex-grow">
        @php
          $currentDay = $startOfCalendar->copy();
          $weeksCount = ceil($endOfCalendar->diffInDays($startOfCalendar) / 7);
        @endphp

        @while($currentDay <= $endOfCalendar)
          @php
            $dayDate = $currentDay->format('Y-m-d');
            $isCurrentMonth = $currentDay->month === $selectedDate->month;
            $isToday = $currentDay->isToday();
            $dayEvents = collect($events)->where('date', $dayDate)->all();
            $hasEvents = count($dayEvents) > 0;
          @endphp

          <div
                  class="v-min-h-[80px] v-relative v-p-2 v-cursor-pointer v-transition-colors v-duration-200"
                  :class="{
                            'v-bg-white': '{{ $isCurrentMonth }}' && selectedDay !== '{{ $dayDate }}',
                            'v-bg-gray-50': !('{{ $isCurrentMonth }}') && selectedDay !== '{{ $dayDate }}',
                            'v-bg-gray-100': selectedDay === '{{ $dayDate }}',
                            'hover:v-bg-gray-50': selectedDay !== '{{ $dayDate }}'
                         }"
                  @click="selectDay('{{ $dayDate }}')">

            <div class="v-z-10 v-relative">
              <div class="v-flex v-items-start v-justify-between">
                <div class="v-relative">
                  @if($isToday)
                    <div class="v-absolute v-top-0 v-left-0 v-w-7 v-h-7 v-rounded-full" style="background-color: #45BFBD;"></div>
                  @endif
                  <span class="v-text-sm {{ $isCurrentMonth ? '' : 'v-text-gray-400' }} {{ $isToday ? 'v-text-white v-z-10 v-relative v-font-bold v-flex v-items-center v-justify-center v-w-7 v-h-7' : '' }}">
                                        {{ $currentDay->format('j') }}
                                    </span>
                </div>

                @if($hasEvents)
                  <div class="v-flex v-space-x-1 v-mt-1 lg:v-hidden">
                    @foreach($dayEvents as $event)
                      <span class="v-w-2.5 v-h-2.5 v-rounded-full" style="background-color: {{ $event['color'] ?? '#9CA3AF' }}"></span>
                    @endforeach
                  </div>
                @endif
              </div>

              @if($hasEvents)
                <div class="v-mt-1 v-hidden lg:v-block">
                  @foreach($dayEvents as $event)
                    <div class="v-text-xs v-mb-1 v-truncate v-rounded v-px-1 v-py-0.5 {{ $isCurrentMonth ? '' : 'v-opacity-50' }}"
                         style="background-color: {{ $event['color'] ?? '#9CA3AF' }}20; color: {{ $event['color'] ?? '#9CA3AF' }}; border-left: 3px solid {{ $event['color'] ?? '#9CA3AF' }}">
                      {{ $event['title'] }}
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>

          @php
            $currentDay->addDay();
          @endphp
        @endwhile
      </div>
    </div>
  </div>

  <div class="v-mt-6 v-bg-white v-rounded-lg v-border v-border-gray-200 v-shadow-sm v-overflow-hidden">
    <div class="v-p-5 v-border-b v-border-gray-100">
      <span class="v-text-lg v-font-semibold v-text-gray-900" x-text="formatDate(selectedDay)"></span>
    </div>

    <div class="v-p-5">
      <template x-if="getEvents(selectedDay).length === 0">
        <div class="v-py-4 v-text-center">
          <p class="v-text-gray-500">No events scheduled for this day.</p>
        </div>
      </template>

      <div class="v-grid v-gap-4">
        <template x-for="(event, index) in getEvents(selectedDay)" :key="index">
          <a :href="event.link || '#'" class="v-flex v-items-start v-p-3 v-rounded-lg v-cursor-pointer v-no-underline hover:v-border hover:v-border-gray-300 hover:v-shadow-sm"
             :style="`background-color: ${event.color}15; border-left: 4px ${event.color} solid;`">
            <div class="v-flex-1">
              <span class="v-font-semibold v-text-sm" :style="`color: ${event.color}`" x-text="event.title"></h4>
              <template x-if="event.time">
                <p class="v-text-xs v-text-gray-600 v-mt-1" x-text="event.time"></p>
              </template>
              <template x-if="event.description">
                <p class="v-text-sm v-text-gray-700 v-mt-1" x-text="event.description"></p>
              </template>
            </div>
          </a>
        </template>
      </div>
    </div>
  </div>
</div>
