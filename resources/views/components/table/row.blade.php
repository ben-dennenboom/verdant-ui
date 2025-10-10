@props(['hover' => true])

<tr {{ $attributes->merge(['class' => $hover ? 'hover:v-bg-gray-50 dark:hover:v-bg-secondary-900' : '']) }}>
    {{ $slot }}
</tr>
