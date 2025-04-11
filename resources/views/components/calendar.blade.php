@props([
    'selectedDate' => null,
    'events',
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

  $selectedDay = request()->get('day', now()->format('Y-m-d'));
@endphp

<div class="v-calendar" x-data="{
    selectedDay: '{{ $selectedDay }}',
    selectedDate: '{{ $selectedDate->format('Y-m-d') }}',
    mobileEventsVisible: false,
    showEventsForDay(day) {
        this.selectedDay = day;
        this.mobileEventsVisible = true;
    },
    isToday(date) {
        return date === '{{ $today }}';
    },
    isCurrentMonth(date) {
        return date.substring(0, 7) === '{{ $selectedDate->format('Y-m') }}';
    },
    getEventsByDay(day) {
        return this.getGroupedEvents()[day] || [];
    },
    getGroupedEvents() {
        const grouped = {};
        const events = {{ json_encode($events) }};

        events.forEach(event => {
            const date = event.date;
            if (!grouped[date]) {
                grouped[date] = [];
            }
            grouped[date].push(event);
        });

        return grouped;
    }
}">
  <div class="v-lg:flex v-lg:h-full v-lg:flex-col">
    <header class="v-flex v-items-center v-justify-between v-border-b v-border-gray-200 v-px-6 v-py-4 v-lg:flex-none">
      <h1 class="v-text-base v-font-semibold v-text-gray-900">
        <time datetime="{{ $selectedDate->format('Y-m') }}">{{ $monthName }} {{ $year }}</time>
      </h1>
      <div class="v-flex v-items-center">
        <div class="v-relative v-flex v-items-center v-rounded-md v-bg-white v-shadow-xs v-md:items-stretch">
          <button type="button"
                  @click="$dispatch('calendar-previous-month')"
                  class="v-flex v-h-9 v-w-12 v-items-center v-justify-center v-rounded-l-md v-border-y v-border-l v-border-gray-300 v-pr-1 v-text-gray-400 hover:v-text-gray-500 focus:v-relative v-md:w-9 v-md:pr-0 v-md:hover:v-bg-gray-50">
            <span class="v-sr-only">Previous month</span>
            <svg class="v-size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
            </svg>
          </button>
          <button type="button"
                  @click="$dispatch('calendar-today')"
                  class="v-hidden v-border-y v-border-gray-300 v-px-3.5 v-text-sm v-font-semibold v-text-gray-900 hover:v-bg-gray-50 focus:v-relative v-md:block">
            Today
          </button>
          <span class="v-relative -v-mx-px v-h-5 v-w-px v-bg-gray-300 v-md:hidden"></span>
          <button type="button"
                  @click="$dispatch('calendar-next-month')"
                  class="v-flex v-h-9 v-w-12 v-items-center v-justify-center v-rounded-r-md v-border-y v-border-r v-border-gray-300 v-pl-1 v-text-gray-400 hover:v-text-gray-500 focus:v-relative v-md:w-9 v-md:pl-0 v-md:hover:v-bg-gray-50">
            <span class="v-sr-only">Next month</span>
            <svg class="v-size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
    </header>

    <div class="v-shadow-sm v-ring-1 v-ring-black/5 v-lg:flex v-lg:flex-auto v-lg:flex-col">
      <div class="v-grid v-grid-cols-7 v-gap-px v-border-b v-border-gray-300 v-bg-gray-200 v-text-center v-text-xs/6 v-font-semibold v-text-gray-700 v-lg:flex-none">
        <div class="v-flex v-justify-center v-bg-white v-py-2">
          <span>M</span>
          <span class="v-sr-only sm:v-not-sr-only">on</span>
        </div>
        <div class="v-flex v-justify-center v-bg-white v-py-2">
          <span>T</span>
          <span class="v-sr-only sm:v-not-sr-only">ue</span>
        </div>
        <div class="v-flex v-justify-center v-bg-white v-py-2">
          <span>W</span>
          <span class="v-sr-only sm:v-not-sr-only">ed</span>
        </div>
        <div class="v-flex v-justify-center v-bg-white v-py-2">
          <span>T</span>
          <span class="v-sr-only sm:v-not-sr-only">hu</span>
        </div>
        <div class="v-flex v-justify-center v-bg-white v-py-2">
          <span>F</span>
          <span class="v-sr-only sm:v-not-sr-only">ri</span>
        </div>
        <div class="v-flex v-justify-center v-bg-white v-py-2">
          <span>S</span>
          <span class="v-sr-only sm:v-not-sr-only">at</span>
        </div>
        <div class="v-flex v-justify-center v-bg-white v-py-2">
          <span>S</span>
          <span class="v-sr-only sm:v-not-sr-only">un</span>
        </div>
      </div>

      <div class="v-flex v-bg-gray-200 v-text-xs/6 v-text-gray-700 v-lg:flex-auto">
        <div class="v-hidden v-w-full v-lg:grid v-lg:grid-cols-7 v-lg:grid-rows-6 v-lg:gap-px">
          @php
            $currentDay = $startOfCalendar->copy();
          @endphp

          @while($currentDay <= $endOfCalendar)
            @php
              $dayDate = $currentDay->format('Y-m-d');
              $isCurrentMonth = $currentDay->month === $selectedDate->month;
              $isToday = $currentDay->isToday();
              $dayEvents = collect($events)->where('date', $dayDate)->all();
            @endphp

            <div class="v-relative v-px-3 v-py-2 {{ $isCurrentMonth ? 'v-bg-white' : 'v-bg-gray-50 v-text-gray-500' }}">
              <time datetime="{{ $dayDate }}"
                    class="{{ $isToday ? 'v-flex v-size-6 v-items-center v-justify-center v-rounded-full v-bg-primary-600 v-font-semibold v-text-white' : '' }}">
                {{ $currentDay->format('j') }}
              </time>

              @if(count($dayEvents) > 0)
                <ol class="v-mt-2">
                  @foreach($dayEvents as $event)
                    <li>
                      @if(isset($event['link']) && !empty($event['link']))
                        <a href="{{ $event['link'] }}"
                           class="v-group v-flex v-whitespace-normal">
                          <p class="v-flex-auto v-truncate v-font-medium v-text-gray-900 group-hover:v-text-primary-600"
                             style="{{ isset($event['color']) ? 'color:' . $event['color'] : '' }}">
                            {{ $event['title'] }}
                          </p>
                          @if(isset($event['time']) && !empty($event['time']))
                            <time datetime="{{ $dayDate }}T{{ $event['time'] }}"
                                  class="v-ml-3 v-hidden v-flex-none v-text-gray-500 group-hover:v-text-primary-600 xl:v-block">
                              {{ $event['time'] }}
                            </time>
                          @endif
                        </a>
                      @else
                        <div class="v-group v-flex v-whitespace-normal">
                          <p class="v-flex-auto v-truncate v-font-medium v-text-gray-900"
                             style="{{ isset($event['color']) ? 'color:' . $event['color'] : '' }}">
                            {{ $event['title'] }}
                          </p>
                          @if(isset($event['time']) && !empty($event['time']))
                            <time datetime="{{ $dayDate }}T{{ $event['time'] }}"
                                  class="v-ml-3 v-hidden v-flex-none v-text-gray-500 xl:v-block">
                              {{ $event['time'] }}
                            </time>
                          @endif
                        </div>
                      @endif
                      @if(isset($event['description']) && !empty($event['description']))
                        <p class="v-text-xs v-text-gray-500 v-truncate v-mt-1">{{ $event['description'] }}</p>
                      @endif
                    </li>
                  @endforeach
                </ol>
              @endif
            </div>

            @php
              $currentDay->addDay();
            @endphp
          @endwhile
        </div>

        <div class="v-isolate v-grid v-w-full v-grid-cols-7 v-grid-rows-6 v-gap-px v-lg:hidden">
          @php
            $currentDay = $startOfCalendar->copy();
          @endphp

          @while($currentDay <= $endOfCalendar)
            @php
              $dayDate = $currentDay->format('Y-m-d');
              $isCurrentMonth = $currentDay->month === $selectedDate->month;
              $isToday = $currentDay->isToday();
              $isSelected = $dayDate === $selectedDay;
              $dayEvents = collect($events)->where('date', $dayDate)->all();
            @endphp

            <button type="button"
                    @click="showEventsForDay('{{ $dayDate }}')"
                    class="v-flex v-h-14 v-flex-col v-px-3 v-py-2 hover:v-bg-gray-100 focus:v-z-10
                                {{ $isCurrentMonth ? 'v-bg-white' : 'v-bg-gray-50' }}
                                {{ $isToday ? 'v-font-semibold v-text-primary-600' : '' }}
                                {{ $isSelected ? 'v-font-semibold' : '' }}
                                {{ $isCurrentMonth && !$isToday ? 'v-text-gray-900' : '' }}
                                {{ !$isCurrentMonth && !$isToday ? 'v-text-gray-500' : '' }}">

              <time datetime="{{ $dayDate }}"
                    class="v-ml-auto {{ $isSelected ? 'v-flex v-size-6 v-items-center v-justify-center v-rounded-full' : '' }} {{ $isSelected && $isToday ? 'v-bg-primary-600' : '' }} {{ $isSelected && !$isToday ? 'v-bg-gray-900 v-text-white' : '' }}">
                {{ $currentDay->format('j') }}
              </time>

              @if(count($dayEvents) > 0)
                <span class="v-sr-only">{{ count($dayEvents) }} events</span>
                <span class="-v-mx-0.5 v-mt-auto v-flex v-flex-wrap-reverse">
                                    @foreach($dayEvents as $event)
                    <span class="v-mx-0.5 v-mb-1 v-size-1.5 v-rounded-full"
                          style="background-color: {{ $event['color'] ?? '#9CA3AF' }}"></span>
                  @endforeach
                                </span>
              @else
                <span class="v-sr-only">No events</span>
              @endif
            </button>

            @php
              $currentDay->addDay();
            @endphp
          @endwhile
        </div>
      </div>
    </div>

    <div class="v-px-4 v-py-6 sm:v-px-6 v-lg:hidden"
         x-show="mobileEventsVisible"
         x-cloak>
      <div class="v-text-center v-mb-4">
        <h2 class="v-text-lg v-font-semibold v-text-gray-900" x-text="new Date(selectedDay).toLocaleDateString('en-US', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})"></h2>
      </div>

      <ol class="v-divide-y v-divide-gray-100 v-overflow-hidden v-rounded-lg v-bg-white v-text-sm v-shadow-sm v-ring-1 v-ring-black/5"
          x-show="getEventsByDay(selectedDay).length > 0">
        <template x-for="(event, index) in getEventsByDay(selectedDay)" :key="index">
          <li class="v-group v-flex v-p-4 v-pr-6 focus-within:v-bg-gray-50 hover:v-bg-gray-50">
            <div class="v-flex-auto">
              <p class="v-font-semibold v-text-gray-900"
                 :style="event.color ? 'color:' + event.color : ''"
                 x-text="event.title"></p>
              <p x-show="event.description" x-text="event.description"
                 class="v-mt-1 v-text-gray-600 v-text-sm"></p>
              <time x-show="event.time" :datetime="selectedDay + 'T' + event.time"
                    class="v-mt-2 v-flex v-items-center v-text-gray-700">
                <svg class="v-mr-2 v-size-5 v-text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                </svg>
                <span x-text="event.time"></span>
              </time>
            </div>
            <a x-show="event.link" :href="event.link"
               class="v-ml-6 v-flex-none v-self-center v-rounded-md v-bg-white v-px-3 v-py-2 v-font-semibold v-text-gray-900 v-opacity-0 v-shadow-xs v-ring-1 v-ring-gray-300 v-ring-inset group-hover:v-opacity-100 hover:v-ring-gray-400 focus:v-opacity-100">
              View<span class="v-sr-only" x-text="', ' + event.title"></span>
            </a>
          </li>
        </template>
      </ol>

      <div class="v-text-center v-my-8" x-show="getEventsByDay(selectedDay).length === 0">
        <p class="v-text-gray-500">No events for this day</p>
      </div>
    </div>
  </div>
</div>
