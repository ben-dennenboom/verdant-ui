@props(['hover' => true])
<div {{ $attributes->merge(['class' => 'v-contents-row' . ($hover ? ' v-group' : '')]) }}>
    {{ $slot }}
</div>
