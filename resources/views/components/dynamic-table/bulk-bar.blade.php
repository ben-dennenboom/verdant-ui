@props(['vm', 'bulkFormId'])

@php
    $bulkFields    = $vm->bulkFields ?? [];
    $bulkActionUrl = $vm->bulkActionUrl ?? \Illuminate\Support\Str::before(request()->getRequestUri(), '?');
@endphp

<form id="{{ $bulkFormId }}" method="POST" action="{{ $bulkActionUrl }}" data-v-bulk-bar>
    @csrf

    <div class="v:flex v:items-center v:bg-floating v:rounded-full v:shadow-xl v:border v:border-gray-200 v:dark:border-gray-700 v:px-5 v:py-2.5 v:whitespace-nowrap">
        <span class="v:text-sm v:font-semibold v:text-gray-800 v:dark:text-gray-100 v:pr-4">
            <span data-v-selected-count></span> {{ mb_strtolower(__('verdant::table.selected')) }}
        </span>

        <div class="v:w-px v:self-stretch v:bg-gray-200 v:dark:bg-gray-600"></div>

        @foreach ($bulkFields as $field)
            @php
                $key         = $field['key'];
                $label       = $field['label'];
                $type        = $field['type'] ?? 'text';
                $options     = $field['options'] ?? [];
                $placeholder = $field['placeholder'] ?? ('Select ' . strtolower($label) . '…');
                $multiple    = !empty($field['multiple']);
                $noneOpt     = collect($options)->firstWhere('value', '_none');
                $mainOptions = collect($options)->filter(fn($o) => $o['value'] !== '_none')->values()->all();
                $selectClass = 'v:text-sm v:border-0 v:bg-transparent v:text-gray-700 v:dark:text-gray-200 v:focus:ring-0 v:focus:outline-hidden v:cursor-pointer v:py-0 v:pl-1 v:pr-6';
                $inputClass  = 'v:text-sm v:border v:border-gray-300 v:dark:border-gray-600 v:rounded-lg v:bg-white v:dark:bg-gray-700 v:text-gray-700 v:dark:text-gray-200 v:px-2 v:py-1 v:focus:ring-1 v:focus:ring-primary-500 v:focus:border-primary-500 v:focus:outline-hidden';
            @endphp

            <div class="v:flex v:items-center v:gap-1.5 v:pl-4 v:pr-3">
                @if ($type === 'select' && $multiple)
                    <span class="v:text-sm v:text-gray-600">{{ $label }}</span>
                    <details
                        class="v:relative"
                        data-v-multi
                        data-v-placeholder="{{ $placeholder }}"
                        data-v-count-label="{{ mb_strtolower($label) }}"
                        data-v-none-label="{{ $noneOpt ? $noneOpt['label'] : 'None' }}"
                    >
                        <summary class="v:flex v:items-center v:gap-1 v:text-sm v:text-gray-700 v:cursor-pointer v:py-0 v:pl-1 v:pr-1 v:list-none">
                            <span data-v-multi-label>{{ $placeholder }}</span>
                            <svg class="v:w-3 v:h-3 v:text-gray-500 v:shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>

                        <div class="v:absolute v:bottom-full v:left-1/2 v:-translate-x-1/2 v:mb-3 v:min-w-40 v:bg-floating v:rounded-lg v:shadow-lg v:border v:border-gray-200 v:py-1 v:z-50 v:max-h-56 v:overflow-y-auto">
                            @if ($noneOpt)
                                <label class="v:flex v:items-center v:gap-2 v:px-3 v:py-1.5 v:text-sm v:text-gray-400 v:italic v:hover:bg-gray-50 v:cursor-pointer v:select-none">
                                    <input
                                        type="checkbox"
                                        name="{{ $key }}[]"
                                        value="_none"
                                        class="v:rounded v:border-gray-300 v:text-primary-600 v:focus:ring-primary-500 v:bg-white"
                                    >
                                    {{ $noneOpt['label'] }}
                                </label>
                                <div class="v:border-t v:border-gray-100 v:mx-2 v:my-1"></div>
                            @endif
                            @foreach ($mainOptions as $opt)
                                <label class="v:flex v:items-center v:gap-2 v:px-3 v:py-1.5 v:text-sm v:text-gray-700 v:hover:bg-gray-50 v:cursor-pointer v:select-none">
                                    <input
                                        type="checkbox"
                                        name="{{ $key }}[]"
                                        value="{{ $opt['value'] }}"
                                        class="v:rounded v:border-gray-300 v:text-primary-600 v:focus:ring-primary-500 v:bg-white"
                                    >
                                    {{ $opt['label'] }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                @elseif ($type === 'select')
                    <span class="v:text-sm v:text-gray-600 v:dark:text-gray-400">{{ $label }}</span>
                    <select
                        name="{{ $key }}"
                        class="{{ $selectClass }}"
                    >
                        <option value="">{{ $placeholder }}</option>
                        @foreach ($options as $opt)
                            <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                @elseif ($type === 'checkbox')
                    <label class="v:flex v:items-center v:gap-2 v:text-sm v:text-gray-600 v:dark:text-gray-400 v:cursor-pointer v:select-none">
                        <input
                            type="checkbox"
                            name="{{ $key }}"
                            value="1"
                            class="v:rounded v:border-gray-300 v:dark:border-gray-600 v:text-primary-600 v:focus:ring-primary-500 v:bg-white v:dark:bg-gray-700"
                        >
                        {{ $label }}
                    </label>
                @else
                    <span class="v:text-sm v:text-gray-600 v:dark:text-gray-400">{{ $label }}</span>
                    <input
                        type="{{ $type === 'number' ? 'number' : ($type === 'date' ? 'date' : 'text') }}"
                        name="{{ $key }}"
                        placeholder="{{ $placeholder }}"
                        class="{{ $inputClass }} v:w-32"
                    >
                @endif
            </div>

            @if (!$loop->last)
                <div class="v:w-px v:self-stretch v:bg-gray-200 v:dark:bg-gray-600"></div>
            @endif
        @endforeach

        <div class="v:w-px v:self-stretch v:bg-gray-200 v:dark:bg-gray-600 v:ml-1"></div>

        <button
            type="submit"
            class="v:ml-3 v:text-sm v:font-medium v:text-primary-600 v:dark:text-primary-400 v:hover:text-primary-800 v:dark:hover:text-primary-200 v:transition-colors"
        >
            {{ __('verdant::table.apply') }}
        </button>

        <button
            type="reset"
            class="v:ml-3 v:text-sm v:text-gray-500 v:dark:text-gray-400 v:hover:text-gray-700 v:dark:hover:text-gray-200 v:transition-colors"
        >
            {{ __('verdant::table.clear') }}
        </button>
    </div>
</form>
