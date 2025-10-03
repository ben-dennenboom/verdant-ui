@props(['title', 'subtitle' => null])

<div class="v-rounded-t v-bg-surface v-shadow-sm">
    <div class="v-py-6 v-px-4 sm:v-px-6 lg:v-px-8">
        <div class="v-flex v-flex-col lg:v-flex-row lg:v-items-center lg:v-justify-between v-gap-4">
            <div class="v-min-w-0">
                @if ($subtitle)
                    <h3 class="v-text-lg v-leading-6 v-font-medium v-text-foreground">{{ $title }}</h3>
                    <p class="v-mt-1 v-text-sm v-text-muted-foreground">{{ $subtitle }}</p>
                @else
                    <h1 class="v-text-2xl v-font-semibold v-text-foreground">{{ $title }}</h1>
                @endif
            </div>
            @if(isset($actions) && $actions)
                <div class="v-w-full lg:v-w-auto v-overflow-x-auto">
                    <div class="v-inline-flex v-min-w-full lg:v-min-w-0 v-gap-1">
                        {{ $actions }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
