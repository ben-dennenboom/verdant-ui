@props(['align' => 'left'])

<div {{ $attributes->merge([
    'class' => 'v-flex ' . ($align === 'right' ? 'v-justify-end' : ($align === 'center' ? 'v-justify-center' : 'v-justify-start'))
]) }}>
    <div class="v-flex v-flex-wrap v-gap-y-1 v-gap-1">
        {{ $slot }}
    </div>
</div>
