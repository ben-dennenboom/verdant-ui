@props(['label', 'value'])

<div class="v:odd:bg-gray-50 v:dark:odd:bg-gray-700">
    <div class="v:px-4 v:py-5 v:sm:px-6">
        <div class="v:flex v:flex-col v:sm:flex-row v:gap-0 v:sm:gap-5">
            <dt class="v:w-full v:sm:w-1/5 v:shrink-0 v:text-gray-500 v:dark:text-gray-400 v:font-medium v:mb-1 v:sm:mb-0">
                {{ $label }}
            </dt>
            <dd class="v:w-full v:sm:w-4/4 v:text-gray-900 v:dark:text-gray-100">
                {!! $value ?? $slot !!}
            </dd>
        </div>
    </div>
</div>
