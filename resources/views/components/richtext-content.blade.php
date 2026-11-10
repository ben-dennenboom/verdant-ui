@props(['content'])

<div {{ $attributes->merge(['richtext-content']) }}>
    {!! $content !!}
</div>
