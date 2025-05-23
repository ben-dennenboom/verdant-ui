@props(['name', 'label', 'options', 'valueKey' => 'value', 'labelKey' => 'label', 'selected' => null, 'multiple' => false, 'required' => false, 'first_empty' => true])

@php
    $cleanName = str_replace(['[]', '[', ']'], ['', '.', ''], $name);
    $selected = old($cleanName, $selected);
    $id = uniqid();

    $labels = [];
    if ($labelKey instanceof \Closure) {
        foreach ($options as $option) {
            $labels[$option->{$valueKey}] = $labelKey($option);
        }
    }
@endphp

<div class="v-mb-4"
     x-data="{
        open: false,
        search: '',
        selected: @js($multiple ? (is_array($selected) ? $selected : []) : $selected),
        options: @js($options),
        labels: @js($labels),
        multiple: @js($multiple),
        required: @js($required),
        name: @js($name),
        getLabel(option) {
            @if($labelKey instanceof \Closure)
                return this.labels[option.{{ $valueKey }}];
            @else
                return option.{{ $labelKey }};
            @endif
        },
        getValue(option) {
            return option.{{ $valueKey }};
        },
        filteredOptions() {
            return this.options.filter(option =>
                String(this.getLabel(option)).toLowerCase().includes(this.search.toLowerCase())
            );
        },
        isSelected(option) {
            return this.multiple
                ? (Array.isArray(this.selected) && this.selected.includes(this.getValue(option)))
                : this.selected === this.getValue(option);
        },
        toggleOption(option) {
            if (this.multiple) {
                if (!Array.isArray(this.selected)) this.selected = [];
                const value = this.getValue(option);
                const index = this.selected.indexOf(value);
                if (index === -1) {
                    this.selected.push(value);
                } else {
                    this.selected.splice(index, 1);
                }
            } else {
                this.selected = this.getValue(option);
                this.open = false;
            }
        },
        reset() {
            this.selected = this.multiple ? [] : null;
            this.search = '';
        },
        selectedLabels() {
            if (!this.selected) return [];
            if (!this.multiple) {
                const option = this.options.find(o => this.getValue(o) === this.selected);
                return option ? [this.getLabel(option)] : [];
            }
            return this.selected.map(value => {
                const option = this.options.find(o => this.getValue(o) === value);
                return option ? this.getLabel(option) : '';
            }).filter(label => label);
        },
        displayedLabels() {
            const labels = this.selectedLabels();
            if (labels.length <= 5) return labels;
            return [...labels.slice(0, 5), `+${labels.length - 5} more`];
        }
    }"
     x-init="$watch('open', value => { if (!value) search = ''; })"
>
    <div class="v-flex v-items-center v-justify-between">
        <label for="{{ $id }}" class="v-block v-font-medium v-text-gray-700">{{ $label }}</label>
        <button type="button" @click="reset" class="v-text-red-500 v-text-sm">reset</button>
    </div>

    <div class="v-relative v-mt-1">
        <button type="button"
                @click="open = !open"
                class="v-bg-white v-relative v-w-full v-border v-border-secondary-300 v-shadow-sm v-px-4 v-py-2 v-text-left focus:v-ring-secondary-500 focus:v-border-secondary-500"
                tabindex="0">
            <div x-show="!selectedLabels().length" class="v-text-gray-500">
                Nothing selected
            </div>
            <div x-show="selectedLabels().length" class="v-flex v-flex-wrap v-gap-1">
                <template x-for="(label, index) in displayedLabels()" :key="index">
                    <span class="v-inline-flex v-items-center v-px-2 v-py-0.5 v-bg-gray-100 v-text-gray-800"
                          x-text="label"></span>
                </template>
            </div>
            <span class="v-absolute v-inset-y-0 v-right-0 v-flex v-items-center v-pr-2">
                <svg class="v-h-5 v-w-5 v-text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                          clip-rule="evenodd"/>
                </svg>
            </span>
        </button>

        <div x-show="open" @click.away="open = false"
             class="v-absolute v-z-10 v-mt-1 v-w-full v-bg-white v-border v-border-secondary-300 v-shadow-sm">
            <div class="v-p-2">
                <input type="text" x-model="search" x-ref="searchInput"
                       x-init="$watch('open', value => { if (value) $nextTick(() => $refs.searchInput.focus()); })"
                       class="v-w-full v-border v-border-secondary-300 v-shadow-sm v-px-3 v-py-2 focus:v-ring-secondary-500 focus:v-border-secondary-500"
                       placeholder="Search...">
            </div>

            <ul class="v-max-h-60 v-overflow-auto v-py-1 v-list-none">
                <template x-for="(option, index) in filteredOptions()" :key="index">
                    <li @click="toggleOption(option)"
                        :class="{'v-bg-secondary-100': isSelected(option) }"
                        class="v-px-4 v-py-2 v-cursor-pointer hover:v-bg-gray-100">
                        <span x-text="getLabel(option)"></span>
                    </li>
                </template>
            </ul>
        </div>
    </div>

    <template x-if="multiple">
        <template x-for="value in selected" :key="value">
            <input type="hidden" :name="name" :value="value">
        </template>
    </template>

    <template x-if="!multiple">
        <input type="hidden" :name="name" :value="selected" :required="required">
    </template>

    @error($cleanName)
    <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
    @enderror
</div>
