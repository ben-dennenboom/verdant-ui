@props(['hover' => true])

<tr {{ $attributes->merge(['class' => $hover ? 'v:hover:bg-gray-50 v:dark:hover:bg-[#2d3441]' : '']) }}>
    {{ $slot }}
</tr>
