@props(['allowMultiple' => false])

<div x-data="{
    openIndex: null,
    openIndices: {},
    allowMultiple: @js($allowMultiple),
    toggle(index) {
        if (this.allowMultiple) {
            this.openIndices[index] = !this.openIndices[index];
        } else {
            this.openIndex = this.openIndex === index ? null : index;
        }
    },
    isOpen(index) {
        if (this.allowMultiple) {
            return this.openIndices[index] || false;
        }
        return this.openIndex === index;
    }
}"
     class="v-divide-y v-divide-gray-200 dark:v-divide-gray-600 v-border v-border-gray-200 dark:v-border-gray-600 v-rounded-lg v-overflow-hidden"
>
    {{ $slot }}
</div>
