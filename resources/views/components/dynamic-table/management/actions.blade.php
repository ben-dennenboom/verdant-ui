@props([
    'actions' => [],
    'maxVisible' => 2,
])

@php
    $visible = array_slice($actions, 0, $maxVisible);
    $overflow = array_slice($actions, $maxVisible);
@endphp

<div x-data="{ open: false }" class="v-relative v-flex v-items-center">
    <div class="v-flex v-items-center v-gap-0 v-border v-rounded-lg v-divide-x">

        @foreach($visible as $action)
            <a href="{{ $action['route'] }}"
               class="v-px-3 v-py-2 v-text-sm v-text-gray-700 hover:v-bg-gray-50">
                {{ $action['label'] }}
            </a>
        @endforeach

        @if($overflow)
            <button @click="open = !open"
                    class="v-px-3 v-py-2 v-text-sm v-text-gray-700 hover:v-bg-gray-50">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>
        @endif
    </div>

    @if($overflow)
        <div x-show="open"
             @click.away="open = false"
             x-transition
             class="v-absolute v-right-0 v-top-full v-mt-1 v-bg-white v-rounded-lg v-border v-z-50 v-shadow-lg v-min-w-[180px] v-py-1">

            @foreach($overflow as $action)
                @php $disabled = !empty($action['disabled']); @endphp

                @if(!empty($action['form']))
                    <form action="{{ $action['route'] }}" method="POST">
                        @csrf
                        @if(isset($action['method']))
                            @method($action['method'])
                        @endif
                        <button type="submit"
                                @disabled($disabled)
                                class="v-block v-w-full v-text-left v-px-4 v-py-2 v-text-sm hover:v-bg-gray-50 {{ $disabled ? 'v-text-gray-300 v-cursor-not-allowed' : 'v-text-gray-700' }}">
                            {{ $action['label'] }}
                        </button>
                    </form>
                @elseif($disabled)
                    <span class="v-block v-px-4 v-py-2 v-text-sm v-text-gray-300 v-cursor-not-allowed">
                        {{ $action['label'] }}
                    </span>
                @else
                    <a href="{{ $action['route'] }}"
                       class="v-block v-px-4 v-py-2 v-text-sm v-text-gray-700 hover:v-bg-gray-50">
                        {{ $action['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
    @endif
</div>
