@props(['hover' => true])

<div {{ $attributes->merge(['class' => 'v-contents-row' . ($hover ? ' v-group' : '')]) }} 
     style="display: contents;">
    {{ $slot }}
</div>
