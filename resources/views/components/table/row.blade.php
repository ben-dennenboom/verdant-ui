@props(['hover' => true])

<tr {{ $attributes->merge(['class' => $hover ? 'hover:v-bg-gray-50 v-dark:hover:v-bg-gray-800' : '']) }}>
    {{ $slot }}
</tr>
