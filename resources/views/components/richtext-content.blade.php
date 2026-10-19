@props(['content'])

<div {{ $attributes->merge(['class' => 'richtext-content']) }}>
    {!! $content !!}
</div>
