@props([
    'name' => 'timeslot',
    'timeslots' => []
])

<div x-data="{
    selectedDay: null,
    selectedSlot: null,
    currentDate: new Date(),
    timeslots: {{ Js::from($timeslots) }},

    getTimeslots(date) {
        if (!this.timeslots || !date) return [];
        return this.timeslots.filter(slot => slot.date === date);
    },

    hasAvailableSlots(date) {
        return this.getTimeslots(date).length > 0;
    },

    formatDate(dateString) {
        if (!dateString) return 'Select a date';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            weekday: 'long',
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        });
    },

    selectDay(day) {
        if (this.hasAvailableSlots(day)) {
            this.selectedDay = day;
            this.selectedSlot = null;
        }
    },

    selectSlot(slot) {
        this.selectedSlot = slot;
        document.querySelector('input[name={{ $name }}]').value = JSON.stringify({
            date: this.selectedDay,
            time: slot.time,
            id: slot.id
        });
    },

    getSelectedSlotDisplay() {
        if (!this.selectedDay || !this.selectedSlot) return 'No timeslot selected';
        const date = new Date(this.selectedDay);
        const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        return `${dateStr} at ${this.selectedSlot.time}`;
    },

    getMonthName() {
        return this.currentDate.toLocaleDateString('en-US', { month: 'long' });
    },

    getYear() {
        return this.currentDate.getFullYear();
    },

    previousMonth() {
        this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
        this.selectedDay = null;
        this.selectedSlot = null;
    },

    nextMonth() {
        this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
        this.selectedDay = null;
        this.selectedSlot = null;
    },

    getCalendarDays() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);

        const startCalendar = new Date(firstDay);
        startCalendar.setDate(startCalendar.getDate() - (firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1));

        const endCalendar = new Date(lastDay);
        endCalendar.setDate(endCalendar.getDate() + (7 - (lastDay.getDay() === 0 ? 7 : lastDay.getDay())));

        const days = [];
        const current = new Date(startCalendar);

        while (current <= endCalendar) {
            days.push({
                date: current.getFullYear() + '-' +
                      String(current.getMonth() + 1).padStart(2, '0') + '-' +
                      String(current.getDate()).padStart(2, '0'),
                day: current.getDate(),
                isCurrentMonth: current.getMonth() === month,
                isToday: current.toDateString() === new Date().toDateString()
            });
            current.setDate(current.getDate() + 1);
        }

        return days;
    }
}">

  <input type="hidden" name="{{ $name }}"/>

  <div class="v-grid v-grid-cols-1 md:v-grid-cols-2 v-gap-6">
    <div class="v-order-2 md:v-order-1 v-shadow-sm v-border v-border-gray-200 v-bg-white v-rounded-lg v-overflow-hidden">
      <div class="v-flex v-items-center v-justify-between v-p-5 v-border-b v-border-gray-100">
        <h2 class="v-text-2xl v-font-medium v-text-gray-900">
          <span x-text="getMonthName()"></span> <span x-text="getYear()"></span>
        </h2>
        <div class="v-inline-flex v-items-center v-rounded-lg v-border v-border-gray-200 v-overflow-hidden">
          <button type="button" @click="previousMonth()" class="v-p-2 v-text-gray-500 hover:v-text-gray-700 hover:v-bg-gray-50">
            <svg class="v-w-5 v-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>
          <button type="button" @click="nextMonth()" class="v-p-2 v-text-gray-500 hover:v-text-gray-700 hover:v-bg-gray-50">
            <svg class="v-w-5 v-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </button>
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
          <template x-for="(calendarDay, index) in getCalendarDays()" :key="index">
            <div
                    class="v-min-h-[80px] v-relative v-p-2 v-transition-colors v-duration-200"
                    :class="{
                              'v-bg-white v-cursor-pointer hover:v-bg-gray-50': calendarDay.isCurrentMonth && hasAvailableSlots(calendarDay.date) && selectedDay !== calendarDay.date,
                              'v-bg-gray-50': !calendarDay.isCurrentMonth || !hasAvailableSlots(calendarDay.date),
                              'v-bg-blue-50 v-border-2 v-border-blue-200': selectedDay === calendarDay.date,
                              'v-cursor-not-allowed': !hasAvailableSlots(calendarDay.date)
                           }"
                    @click="selectDay(calendarDay.date)">

              <div class="v-z-10 v-relative">
                <div class="v-flex v-items-start v-justify-between">
                  <div class="v-relative">
                    <template x-if="calendarDay.isToday">
                      <div class="v-absolute v-top-0 v-left-0 v-w-7 v-h-7 v-rounded-full v-bg-blue-600"></div>
                    </template>
                    <span class="v-text-sm v-z-10 v-relative"
                          :class="{
                            'v-text-gray-900': calendarDay.isCurrentMonth && hasAvailableSlots(calendarDay.date),
                            'v-text-gray-400': calendarDay.isCurrentMonth && !hasAvailableSlots(calendarDay.date),
                            'v-text-gray-300': !calendarDay.isCurrentMonth,
                            'v-text-white v-font-bold v-flex v-items-center v-justify-center v-w-7 v-h-7': calendarDay.isToday
                          }"
                          x-text="calendarDay.day">
                    </span>
                  </div>

                  <template x-if="hasAvailableSlots(calendarDay.date)">
                    <div class="v-flex v-items-center v-mt-1">
                      <span class="v-text-xs v-bg-green-100 v-text-green-800 v-px-2 v-py-1 v-rounded-full v-font-medium"
                            x-text="getTimeslots(calendarDay.date).length">
                      </span>
                    </div>
                  </template>
                </div>

                <template x-if="hasAvailableSlots(calendarDay.date) && calendarDay.isCurrentMonth">
                  <div class="v-mt-2">
                    <div class="v-text-xs v-text-gray-600 v-truncate"
                         x-text="getTimeslots(calendarDay.date).slice(0, 2).map(slot => slot.time).join(', ') + (getTimeslots(calendarDay.date).length > 2 ? '...' : '')">
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>

    <div class="v-order-1 md:v-order-2 v-space-y-4">
      <div class="v-p-3 v-bg-gray-50 v-rounded-lg v-border">
        <div class="v-text-sm v-font-medium v-text-gray-700 v-mb-1">Selected Timeslot:</div>
        <div class="v-text-lg v-font-semibold v-text-gray-900" x-text="getSelectedSlotDisplay()"></div>
      </div>

      <div x-show="selectedDay"
           class="v-bg-white v-rounded-lg v-border v-border-gray-200 v-shadow-sm v-overflow-hidden">
        <div class="v-p-5 v-border-b v-border-gray-100 v-flex v-justify-between v-items-center">
          <span class="v-text-lg v-font-semibold v-text-gray-900" x-text="formatDate(selectedDay)"></span>
          <button type="button" @click="selectedDay = null; selectedSlot = null" class="v-text-gray-400 hover:v-text-gray-600">
            <svg class="v-w-5 v-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="v-p-5">
          <template x-if="getTimeslots(selectedDay).length === 0">
            <div class="v-py-4 v-text-center">
              <p class="v-text-gray-500">No available timeslots for this day.</p>
            </div>
          </template>

          <div class="v-space-y-3">
            <template x-for="(slot, index) in getTimeslots(selectedDay)" :key="index">
              <button
                      type="button"
                      @click="selectSlot(slot)"
                      class="v-w-full v-p-3 v-text-left v-rounded-lg v-border v-transition-all v-duration-200"
                      :class="{
                  'v-border-blue-500 v-bg-blue-50 v-text-blue-900': selectedSlot && selectedSlot.id === slot.id,
                  'v-border-gray-200 v-bg-white hover:v-border-gray-300 hover:v-bg-gray-50 v-text-gray-900': !selectedSlot || selectedSlot.id !== slot.id
                }">
                <div class="v-flex v-items-center v-justify-between">
                  <div class="v-flex v-items-center v-space-x-3">
                    <div class="v-flex-shrink-0">
                      <svg class="v-w-5 v-h-5 v-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                    <div>
                      <div class="v-font-medium" x-text="slot.time"></div>
                      <div class="v-text-sm v-text-gray-500" x-text="slot.duration || '30 minutes'"></div>
                    </div>
                  </div>
                  <div class="v-flex-shrink-0">
                    <template x-if="selectedSlot && selectedSlot.id === slot.id">
                      <svg class="v-w-5 v-h-5 v-text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"></path>
                      </svg>
                    </template>
                  </div>
                </div>
              </button>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
