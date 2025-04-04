@props(['hover' => true])

<tr {{ $attributes->merge(['class' => $hover ? 'hover:v-bg-gray-50' : '']) }}>
    {{ $slot }}
</tr>
