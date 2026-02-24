@props(['items', 'action'])

<div x-data="{
    draggingElement: null,
    items: @js($items)
}" class="v-max-w-4xl v-mx-auto">
    <form action="{{ $action }}" method="POST"
          @submit="$el.querySelectorAll('div[draggable]').forEach((el, i) => { el.querySelector('input[type=hidden]').name = 'items[' + i + '][id]' })"
    >
        @csrf

        <div class="v-bg-white dark:v-bg-gray-800 v-border v-border-gray-200 dark:v-border-gray-700 v-p-6">
            <div class="v-text-sm v-text-gray-500 dark:v-text-gray-400 v-mb-4">
                Drag and drop items to reorder them. Click save to apply the changes.
            </div>

            <div class="v-space-y-2">
                @foreach($items as $index => $item)
                    <div
                        draggable="true"
                        @dragstart="$el.classList.add('v-bg-gray-100'); draggingElement = $el"
                        @dragend="$el.classList.remove('v-bg-gray-100'); draggingElement = null"
                        @dragover.prevent
                        @drop.prevent="
                            if (draggingElement !== $el) {
                                if (draggingElement.parentNode === $el.parentNode) {
                                    if (draggingElement.compareDocumentPosition($el) & Node.DOCUMENT_POSITION_FOLLOWING) {
                                        $el.after(draggingElement);
                                    } else {
                                        $el.before(draggingElement);
                                    }
                                }
                            }
                        "
                        class="v-flex v-items-center v-gap-4 v-p-4 v-bg-gray-50 dark:v-bg-gray-700 v-border v-border-gray-200 dark:v-border-gray-600"
                    >
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item['id'] }}">

                        <div class="v-cursor-move v-text-gray-400 dark:v-text-gray-500 hover:v-text-gray-600 dark:hover:v-text-gray-300">
                            <i class="fas fa-grip-vertical"></i>
                        </div>

                        <div class="v-flex-1 v-text-gray-900 dark:v-text-gray-100">
                            <span>{{ $item['order'] ?? ($index+1) }}. {{ $item['title'] }}</span>
                        </div>

                        <div class="v-flex v-items-center v-gap-2">
                            @if($index > 0)
                                <button
                                    type="button"
                                    onclick="this.closest('div[draggable]').previousElementSibling?.before(this.closest('div[draggable]'))"
                                    class="v-text-gray-400 dark:v-text-gray-500 hover:v-text-gray-600 dark:hover:v-text-gray-300"
                                >
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            @endif

                            @if($index < count($items) - 1)
                                <button
                                    type="button"
                                    onclick="this.closest('div[draggable]').nextElementSibling?.after(this.closest('div[draggable]'))"
                                    class="v-text-gray-400 dark:v-text-gray-500 hover:v-text-gray-600 dark:hover:v-text-gray-300"
                                >
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="v-mt-6 v-flex v-justify-end">
                <x-v-button.primary type="submit">Save Order</x-v-button.primary>
            </div>
        </div>
    </form>
</div>
