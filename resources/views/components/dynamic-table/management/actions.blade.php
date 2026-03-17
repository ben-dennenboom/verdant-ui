@props([
    'actions' => [],
    'maxVisible' => 2,
])

<div x-data='verdantTableActions(@json(["actions" => $actions, "maxVisible" => $maxVisible, "csrfToken" => csrf_token()]))'
     class="v-relative v-flex v-items-center"
>
    <div class="v-flex v-items-center v-gap-0 v-border v-border-gray-200 dark:v-border-gray-600 v-rounded-lg">
        @foreach($actions as $i => $action)
            @if($i < $maxVisible)
                @php $disabled = !empty($action['disabled']); @endphp
                <div x-show="visibleCount > {{ $i }}" x-transition class="v-inline-flex v-items-center {{ $i >= 0 ? 'v-border-r' : '' }} v-border-gray-200 dark:v-border-gray-600">
                    @if(!empty($action['form']))
                        <form action="{{ $action['route'] }}" method="POST" class="v-inline-flex v-items-center">
                            @csrf
                            @if(isset($action['method']))
                                @method($action['method'])
                            @endif
                            <button type="submit"
                                    @disabled($disabled)
                                    class="v-p-1 v-px-2 v-text-sm hover:v-bg-gray-50 {{ $disabled ? 'v-text-gray-300 v-cursor-not-allowed' : 'v-text-gray-700' }}"
                            >
                                {{ $action['label'] }}
                            </button>
                        </form>
                    @elseif($disabled)
                        <span class="v-p-1 v-px-2 v-text-sm v-text-gray-300 v-cursor-not-allowed">
                            {{ $action['label'] }}
                        </span>
                    @else
                        <a href="{{ $action['route'] }}"
                           class="v-p-1 v-px-2 v-text-sm v-text-gray-700 hover:v-bg-gray-50">
                            {{ $action['label'] }}
                        </a>
                    @endif
                </div>
            @endif
        @endforeach

        <button x-show="hasOverflow"
                x-transition
                @click="open = !open"
                class="v-p-1 v-text-sm v-text-gray-700 hover:v-bg-gray-50 v-border-gray-200 dark:v-border-gray-600"
        >
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
    </div>

    @if(count($actions) > 0)
        <div x-show="open"
             @click.away="open = false"
             x-transition
             class="v-absolute v-right-0 v-top-full v-mt-1 v-bg-white v-rounded-lg v-border v-z-50 v-shadow-lg v-min-w-[180px] v-py-1"
        >
            @foreach($actions as $i => $action)
                @php $disabled = !empty($action['disabled']); @endphp

                <div x-show="visibleCount <= {{ $i }}" x-transition>
                    @if(!empty($action['form']))
                        <form action="{{ $action['route'] }}" method="POST">
                            @csrf
                            @if(isset($action['method']))
                                @method($action['method'])
                            @endif
                            <button type="submit"
                                    @disabled($disabled)
                                    class="v-block v-w-full v-text-left v-px-4 v-py-2 v-text-sm hover:v-bg-gray-50 {{ $disabled ? 'v-text-gray-300 v-cursor-not-allowed' : 'v-text-gray-700' }}"
                            >
                                {{ $action['label'] }}
                            </button>
                        </form>
                    @elseif($disabled)
                        <span class="v-block v-px-4 v-py-2 v-text-sm v-text-gray-300 v-cursor-not-allowed">
                            {{ $action['label'] }}
                        </span>
                    @else
                        <a href="{{ $action['route'] }}"
                           class="v-block v-px-4 v-py-2 v-text-sm v-text-gray-700 hover:v-bg-gray-50"
                        >
                            {{ $action['label'] }}
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
