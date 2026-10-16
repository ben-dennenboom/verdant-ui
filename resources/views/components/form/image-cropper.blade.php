@props([
    'name',
    'label' => null,
    'src' => null,
    'aspectRatio' => null,
    'minWidth' => 0,
    'minHeight' => 0,
    'maxWidth' => 5000,
    'maxHeight' => 5000,
    'required' => false,
    'uploadUrl' => null,
    'maxScale' => 512,
    'disableCrop' => false,
])

<div class="v:mb-4"
     x-data="imageCropper({
        name: '{{ $name }}',
        src: '{{ $src }}',
        aspectRatio: '{{ $aspectRatio }}',
        minWidth: {{ $minWidth }},
        minHeight: {{ $minHeight }},
        maxWidth: {{ $maxWidth }},
        maxHeight: {{ $maxHeight }},
        uploadUrl: '{{ $uploadUrl }}',
        required: {{ $required ? 'true' : 'false' }},
        csrfToken: '{{ csrf_token() }}',
        maxScale: {{ $maxScale }},
        disableCrop: {{ $disableCrop ? 'true' : 'false' }},
     })">

  @if($label)
    <label for="{{ $name }}_input" class="v:block v:font-medium v:text-gray-700 v:mb-1 v:dark:text-gray-300">
      {{ $label }}@if($required)<span class="required_asterisk">*</span>@endif
      <small class="v:ml-1 v:text-gray-500">
        @if($minWidth || $minHeight || $aspectRatio)
          (
            @if($minWidth && $minHeight)
              min {{ $minWidth }}x{{ $minHeight }}px
            @elseif($minWidth)
              min width {{ $minWidth }}px
            @elseif($minHeight)
              min height {{ $minHeight }}px
            @endif

            @if(($minWidth || $minHeight) && $aspectRatio)
              -
            @endif

            @if($aspectRatio)
              ratio {{ $aspectRatio }}
            @endif
          )
        @endif
      </small>
    </label>
  @endif

  <div x-show="error" class="v:text-red-600 v:text-sm v:mb-2" x-text="error"></div>

  <div class="v:flex v:flex-col v:mb-4 v:gap-4">
    <div x-show="preview" class="v:w-fit v:max-w-[300px] v:border v:border-secondary-300 v:rounded v:overflow-hidden">
      <img :src="preview" alt="Preview" class="v:max-w-full v:max-h-64 v:h-auto v:object-contain v:rounded">
    </div>

    <div class="v:flex v:gap-2">
      <input type="file"
             accept="image/*"
             class="v:hidden"
             x-ref="fileInput"
             @change="handleFileSelect">

      <x-v-button.secondary type="button" @click="selectFile" icon="image">
        {{ $uploadUrl ? 'Upload Image' : 'Select Image' }}
      </x-v-button.secondary>

      <x-v-button.light
              x-show="preview && preview !== initialSrc && disableCrop === false"
              type="button"
              @click="openCropModal"
              icon="crop">
        Recrop Image
      </x-v-button.light>
    </div>
  </div>

  <input type="hidden" :name="name" x-model="croppedImage" :required="required">

  <template x-teleport="body">
    <div x-show="showModal"
         class="v:fixed v:inset-0 v:z-50 v:overflow-auto v:bg-gray-500 v:opacity-75 v:flex v:items-center v:justify-center v:p-4"
         x-transition:enter="v:transition v:ease-out v:duration-300"
         x-transition:enter-start="v:opacity-0"
         x-transition:enter-end="v:opacity-100"
         x-transition:leave="v:transition v:ease-in v:duration-200"
         x-transition:leave-start="v:opacity-100"
         x-transition:leave-end="v:opacity-0">

      <div class="v:bg-floating v:rounded-lg v:shadow-xl v:w-full v:max-w-4xl v:mx-auto v:overflow-hidden"
           @click.outside="cancelCrop"
           x-transition:enter="v:transition v:ease-out v:duration-300"
           x-transition:enter-start="v:opacity-0 v:transform v:scale-95"
           x-transition:enter-end="v:opacity-100 v:transform v:scale-100"
           x-transition:leave="v:transition v:ease-in v:duration-200"
           x-transition:leave-start="v:opacity-100 v:transform v:scale-100"
           x-transition:leave-end="v:opacity-0 v:transform v:scale-95">

        <div class="v:p-4 v:border-b v:border-gray-200">
          <h3 class="v:text-lg v:font-medium v:text-gray-900">Crop Image</h3>
        </div>

        <div class="v:p-4">
          <div class="v:h-96 v:overflow-hidden">
            <img x-ref="cropperImage" src="" alt="Image to crop" class="v:block v:max-w-full">
          </div>
        </div>

        <div class="v:bg-gray-50 v:px-4 v:py-3 v:flex v:justify-end v:gap-2">
          <x-v-button.light type="button" @click="cancelCrop">
            Cancel
          </x-v-button.light>

          <x-v-button.primary type="button" @click="applyCrop">
            {{ $uploadUrl ? 'Upload' : 'Apply Crop' }}
          </x-v-button.primary>
        </div>
      </div>
    </div>
  </template>
</div>

@once
  <script src="{{ url('vendor/verdant/js/form/image-cropper.js') }}" type="module"></script>
@endonce
