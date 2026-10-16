@props([
    'id' => null,
    'maxWidth' => 'lg',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to perform this action?',
    'action' => '#',
    'method' => 'POST',
    'type' => 'danger',
])

@php
    $colors = [
        'primary' => ['light' => 'v:bg-primary-100 v:dark:bg-primary-800', 'text' => 'v:text-primary-700 v:dark:text-primary-300'],
        'warning' => ['light' => 'v:bg-yellow-100 v:dark:bg-yellow-800', 'text' => 'v:text-yellow-600 v:dark:text-yellow-300'],
        'danger' => ['light' => 'v:bg-red-100 v:dark:bg-red-800', 'text' => 'v:text-red-700 v:dark:text-red-300'],
    ];

    $color = $colors[$type];

    $iconClass = [
        'primary' => 'info',
        'warning' => 'exclamation',
        'danger' => 'exclamation',
    ][$type];
@endphp

<x-v-modal :id="$id" :maxWidth="$maxWidth">
    <form action="{{ $action }}" method="POST">
        @csrf
        @method($method)
        <div class="v:sm:flex v:sm:items-start">
            <div class="v:flex v:items-center v:justify-center v:shrink-0 v:w-12 v:h-12 v:mx-auto {{ $color['light'] }} v:sm:mx-0 v:sm:h-10 v:sm:w-10">
                <i class="v:w-6 v:h-6 {{ $color['text'] }} v:text-center v:contents v:align-middle fas fa-{{ $iconClass }}"></i>
            </div>

            <div class="v:mt-3 v:text-center v:sm:mt-0 v:sm:ml-4 v:sm:text-left">
                <h3 class="v:text-lg v:font-medium v:leading-6 v:text-gray-900 v:dark:text-gray-100" id="modal-title">
                    {{ $title }}
                </h3>
                <div class="v:mt-2">
                    <p class="v:text-gray-500 v:dark:text-gray-400 v:text-wrap">
                        {{ $message }}
                    </p>
                </div>
            </div>
        </div>

        <div class="v:mt-5 v:sm:mt-4">
            <x-v-button.group align="right">
                <x-v-button.light @click="$dispatch('close-modal', '{{ $id }}')">
                    Cancel
                </x-v-button.light>

                @if($type === 'danger')
                    <x-v-button.danger type="submit">Confirm</x-v-button.danger>
                @elseif($type === 'warning')
                    <x-v-button.warning type="submit">Confirm</x-v-button.warning>
                @else
                    <x-v-button.primary type="submit">Confirm</x-v-button.primary>
                @endif
            </x-v-button.group>
        </div>
    </form>
</x-v-modal>

