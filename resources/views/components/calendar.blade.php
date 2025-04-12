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

  $selectedDay = $selectedDate->format('Y-m-d');
@endphp

<div x-data="{
    selectedDay: null,
    mobileEventsVisible: false,
    showEvents(day) {
        this.selectedDay = day;
        this.mobileEventsVisible = true;
    },
    getEvents(date) {
        return {{ Js::from($events) }}.filter(event => event.date === date);
    }
}">
  <div class="v-shadow-sm v-border v-border-gray-200 v-bg-white v-rounded-lg v-overflow-hidden">
    <div class="v-flex v-items-center v-justify-between v-p-4 v-border-b v-border-gray-200">
      <h2 class="v-text-xl v-font-semibold v-text-gray-900">
        {{ $monthName }} {{ $year }}
      </h2>
      <div class="v-flex v-items-center v-space-x-1">
        <a href="?date={{ $prevMonth }}" class="v-p-2 v-text-gray-500 hover:v-text-gray-700 v-rounded-md hover:v-bg-gray-100">
          <svg class="v-w-5 v-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </a>
        <a href="?date={{ $nextMonth }}" class="v-p-2 v-text-gray-500 hover:v-text-gray-700 v-rounded-md hover:v-bg-gray-100">
          <svg class="v-w-5 v-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </a>
      </div>
    </div>

    <div class="v-flex v-flex-col">
      <div class="v-grid v-grid-cols-7 v-border-b v-border-gray-200">
        <div class="v-py-2 v-text-center v-text-sm v-font-medium v-text-gray-700">Mon</div>
        <div class="v-py-2 v-text-center v-text-sm v-font-medium v-text-gray-700">Tue</div>
        <div class="v-py-2 v-text-center v-text-sm v-font-medium v-text-gray-700">Wed</div>
        <div class="v-py-2 v-text-center v-text-sm v-font-medium v-text-gray-700">Thu</div>
        <div class="v-py-2 v-text-center v-text-sm v-font-medium v-text-gray-700">Fri</div>
        <div class="v-py-2 v-text-center v-text-sm v-font-medium v-text-gray-700">Sat</div>
        <div class="v-py-2 v-text-center v-text-sm v-font-medium v-text-gray-700">Sun</div>
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

          <div class="v-min-h-[80px] v-relative v-p-1 {{ $isCurrentMonth ? 'v-bg-white' : 'v-bg-gray-50' }} {{ $isToday ? 'v-bg-blue-50' : '' }} v-overflow-hidden"
               @click="showEvents('{{ $dayDate }}')">

            <div class="v-z-10 v-relative">
              <div class="v-flex v-items-start v-justify-between">
                <div class="v-relative">
                  @if($isToday)
                    <div class="v-absolute v-top-0 v-left-0 v-w-7 v-h-7 v-rounded-full v-bg-primary-500"></div>
                  @endif
                  <span class="v-text-sm {{ $isCurrentMonth ? '' : 'v-text-gray-400' }} {{ $isToday ? 'v-text-white v-z-10 v-relative v-font-bold v-flex v-items-center v-justify-center v-w-7 v-h-7' : '' }}">
                                        {{ $currentDay->format('j') }}
                                    </span>
                </div>

                @if($hasEvents && count($dayEvents) <= 3)
                  <div class="v-flex v-space-x-1 v-mt-1 lg:v-hidden">
                    @foreach($dayEvents as $event)
                      <span class="v-w-2 v-h-2 v-rounded-full" style="background-color: {{ $event['color'] ?? '#9CA3AF' }}"></span>
                    @endforeach
                  </div>
                @endif
              </div>

              @if($hasEvents)
                <div class="v-mt-1 v-hidden lg:v-block">
                  @foreach($dayEvents as $event)
                    <div class="v-text-xs v-mb-1 v-truncate v-rounded v-px-1 v-py-0.5 {{ $isCurrentMonth ? '' : 'v-opacity-50' }}"
                         style="background-color: {{ $event['color'] ?? '#9CA3AF' }}; color: white;">
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

  <div x-show="selectedDay"
       x-cloak
       class="v-fixed v-inset-0 v-z-50 v-overflow-y-auto"
       @click.away="selectedDay = null">
    <div class="v-flex v-min-h-screen v-items-end v-justify-center v-p-4 v-text-center sm:v-items-center sm:v-p-0">
      <div class="v-relative v-transform v-transition-all v-bg-white v-rounded-lg v-overflow-hidden v-shadow-xl v-w-full v-max-w-lg v-p-6">
        <div class="v-absolute v-top-0 v-right-0 v-p-4">
          <button @click="selectedDay = null" class="v-text-gray-400 hover:v-text-gray-500">
            <svg class="v-h-6 v-w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <h3 class="v-text-lg v-font-semibold v-mb-4" x-text="selectedDay ? new Date(selectedDay).toLocaleDateString('en-US', {weekday: 'long', month: 'long', day: 'numeric', year: 'numeric'}) : ''"></h3>

        <template x-if="selectedDay && getEvents(selectedDay).length === 0">
          <p class="v-text-gray-500 v-py-4">No events scheduled for this day.</p>
        </template>

        <div class="v-space-y-3">
          <template x-for="event in getEvents(selectedDay)" :key="event.id">
            <div class="v-flex v-items-start v-p-3 v-rounded-lg" :style="`background-color: ${event.color}25`">
              <div class="v-flex-1">
                <h4 class="v-font-semibold v-text-sm" :style="`color: ${event.color}`" x-text="event.title"></h4>
                <template x-if="event.time">
                  <p class="v-text-xs v-text-gray-600 v-mt-1" x-text="event.time"></p>
                </template>
                <template x-if="event.description">
                  <p class="v-text-sm v-text-gray-700 v-mt-1" x-text="event.description"></p>
                </template>
              </div>
              <template x-if="event.link">
                <a :href="event.link" class="v-text-xs v-py-1 v-px-2 v-bg-white v-rounded v-shadow-sm v-ml-2"
                   :style="`color: ${event.color}; border: 1px solid ${event.color}`">
                  View
                </a>
              </template>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</div>
