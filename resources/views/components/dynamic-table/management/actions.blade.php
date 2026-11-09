@props([
    'actions' => [],
    'maxVisible' => 2,
])

@php
    $actions = array_values($actions);
    $maxVisible = min((int) ($maxVisible ?? 2), count($actions));
    $hasOverflow = count($actions) > $maxVisible;
    $defaultDisplay = config('verdant.ui.table_actions_icon_only') ? 'icon' : 'all';
    $showsLabel = fn (array $a): bool => empty($a['icon']) || ($a['display'] ?? $defaultDisplay) === 'all';
    $iconOnly = collect(array_slice($actions, 0, $maxVisible))->every(fn ($a) => ! $showsLabel($a));
    [$foldSecondHidden, $foldSecondBlock, $foldFirstHidden, $foldFirstBlock] = $iconOnly
        ? ['v:@max-[100px]:hidden', 'v:@max-[100px]:block', 'v:@max-[70px]:hidden', 'v:@max-[70px]:block']
        : ['v:@max-[170px]:hidden', 'v:@max-[170px]:block', 'v:@max-[100px]:hidden', 'v:@max-[100px]:block'];
    $menuClass = match (true) {
        $hasOverflow => '',
        count($actions) > min(1, $maxVisible) => 'v:hidden ' . $foldSecondBlock,
        default => 'v:hidden ' . $foldFirstBlock,
    };
@endphp

<div class="v:relative v:flex v:items-center">
    <div class="v:flex v:items-center v:gap-0 v:border v:border-gray-200 v:dark:border-gray-600 v:bg-surface v:rounded-lg v:overflow-hidden">
        @foreach($actions as $i => $action)
            @php
                $disabled = !empty($action['disabled']);
                $icon = $action['icon'] ?? null;
                $showLabel = $showsLabel($action);
                $hasBorder = $i < $maxVisible - 1 || ($i < $maxVisible && $hasOverflow);
                $inlineClass = match (true) {
                    $i >= $maxVisible => 'v:hidden',
                    $i >= 1 => 'v:inline-flex ' . $foldSecondHidden,
                    default => 'v:inline-flex ' . $foldFirstHidden,
                };
            @endphp
            <div class="{{ $inlineClass }} v:items-center v:border-gray-200 v:dark:border-gray-600 {{ $hasBorder ? 'v:border-r' : '' }}">
                @if(!empty($action['form']))
                    <form action="{{ $action['route'] }}" method="POST" class="v:inline-flex v:items-center">
                        @csrf
                        @if(isset($action['method']))
                            @method($action['method'])
                        @endif
                        <button type="submit"
                                @disabled($disabled)
                                class="v:p-1 v:px-2 v:text-sm v:hover:bg-gray-50 v:overflow-hidden {{ $disabled ? 'v:text-gray-300 v:cursor-not-allowed' : 'v:text-gray-700 v:cursor-pointer' }}"
                                @if(!$showLabel) title="{{ $action['label'] }}"@endif
                        >
                            @if($icon)<i class="fas fa-{{ $icon }} {{ $showLabel ? 'v:mr-2' : 'v:mx-1' }}"></i>@endif
                            @if($showLabel){{ $action['label'] }}@endif
                        </button>
                    </form>
                @elseif($disabled)
                    <span
                        class="v:p-1 v:px-2 v:text-sm v:text-gray-300 v:cursor-not-allowed"
                        @if(!$showLabel) title="{{ $action['label'] }}"@endif
                    >
                        @if($icon)<i class="fas fa-{{ $icon }} {{ $showLabel ? 'v:mr-2' : 'v:mx-1' }}"></i>@endif
                        @if($showLabel){{ $action['label'] }}@endif
                    </span>
                @else
                    <a href="{{ $action['route'] }}"
                       class="v:p-1 v:px-2 v:text-sm v:text-gray-700 v:hover:bg-gray-50"
                       @if(!$showLabel) title="{{ $action['label'] }}"@endif
                    >
                        @if($icon)<i class="fas fa-{{ $icon }} {{ $showLabel ? 'v:mr-2' : 'v:mx-1' }}"></i>@endif
                        @if($showLabel){{ $action['label'] }}@endif
                    </a>
                @endif
            </div>
        @endforeach

        @if(count($actions) > 0)
            <details data-v-action-menu class="{{ $menuClass }}">
                <summary class="v:list-none v:py-1 v:text-sm v:text-gray-700 v:hover:bg-gray-50 v:cursor-pointer">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </summary>

                <div class="v:absolute v:right-0 v:top-full v:mt-1 v:bg-floating v:rounded-lg v:border v:border-gray-200 v:dark:border-gray-600 v:z-50 v:shadow-lg v:min-w-[180px] v:py-1 v:text-left">
                    @foreach($actions as $i => $action)
                        @php
                            $disabled = !empty($action['disabled']);
                            $icon = $action['icon'] ?? null;
                            $overflowClass = match (true) {
                                $i >= $maxVisible => '',
                                $i >= 1 => 'v:hidden ' . $foldSecondBlock,
                                default => 'v:hidden ' . $foldFirstBlock,
                            };
                        @endphp
                        <div class="{{ $overflowClass }}">
                            @if(!empty($action['form']))
                                <form action="{{ $action['route'] }}" method="POST">
                                    @csrf
                                    @if(isset($action['method']))
                                        @method($action['method'])
                                    @endif
                                    <button type="submit"
                                            @disabled($disabled)
                                            class="v:block v:w-full v:text-left v:px-4 v:py-2 v:text-sm v:hover:bg-gray-50 {{ $disabled ? 'v:text-gray-300 v:cursor-not-allowed' : 'v:text-gray-700 v:cursor-pointer' }}"
                                    >
                                        @if($icon)<i class="fas fa-{{ $icon }} v:mr-2"></i>@endif
                                        {{ $action['label'] }}
                                    </button>
                                </form>
                            @elseif($disabled)
                                <span class="v:block v:px-4 v:py-2 v:text-sm v:text-gray-300 v:cursor-not-allowed">
                                    @if($icon)<i class="fas fa-{{ $icon }} v:mr-2"></i>@endif
                                    {{ $action['label'] }}
                                </span>
                            @else
                                <a href="{{ $action['route'] }}"
                                   class="v:block v:px-4 v:py-2 v:text-sm v:text-gray-700 v:hover:bg-gray-50"
                                >
                                    @if($icon)<i class="fas fa-{{ $icon }} v:mr-2"></i>@endif
                                    {{ $action['label'] }}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </details>
        @endif
    </div>
</div>
@once
    <script src="{{ asset('vendor/verdant/js/dynamic-table.js') }}" type="module"></script>
@endonce
