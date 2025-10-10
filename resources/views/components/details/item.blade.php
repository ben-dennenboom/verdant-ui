@props(['label', 'value'])

<div class="odd:v-bg-gray-50 dark:odd:v-bg-gray-700">
    <div class="v-px-4 v-py-5 sm:v-px-6">
        <div class="v-flex v-flex-col sm:v-flex-row v-gap-0 sm:v-gap-5">
            <dt class="v-w-full sm:v-w-1/5 v-flex-shrink-0 v-text-gray-500 dark:v-text-gray-400 v-font-medium v-mb-1 sm:v-mb-0">
                {{ $label }}
            </dt>
            <dd class="v-w-full sm:v-w-4/4 v-text-gray-900 dark:v-text-gray-100">
                {!! $value ?? $slot !!}
            </dd>
        </div>
    </div>
</div>
