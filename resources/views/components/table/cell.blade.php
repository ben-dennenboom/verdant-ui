@props(['class' => '', 'colspan' => null, 'nowrap' => false])
<td class="v-px-6 v-py-4 v-whitespace-nowrap v-font-medium v-text-foreground {{ $nowrap ? '' : 'v-text-wrap' }} {{ $class }}" {{ $colspan ? 'colspan=' . $colspan : ''}}>
    {{ $slot }}
</td>
