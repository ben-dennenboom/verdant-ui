@props([
    'name',
    'label' => null,
    'src' => null,
    'aspectRatio' => null,
    'minWidth' => 0,
    'minHeight' => 0,
    'maxWidth' => 2000,
    'maxHeight' => 2000,
    'required' => false,
    'uploadUrl' => null,
])

<div class="v-mb-4"
     x-data="imageCropper({
        name: '{{ $name }}',
        src: '{{ $src ?? asset('vendor/verdant/images/placeholder.jpg') }}',
        aspectRatio: '{{ $aspectRatio }}',
        minWidth: {{ $minWidth }},
        minHeight: {{ $minHeight }},
        maxWidth: {{ $maxWidth }},
        maxHeight: {{ $maxHeight }},
        uploadUrl: '{{ $uploadUrl }}',
        required: {{ $required ? 'true' : 'false' }},
        csrfToken: '{{ csrf_token() }}'
     })">

  @if($label)
    <label for="{{ $name }}_input" class="v-block v-font-medium v-text-gray-700 v-mb-1">
      {{ $label }}
      @if($required)
        <span class="v-text-red-500">*</span>
      @endif
      <small class="v-ml-1 v-text-gray-500">
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

  <div x-show="error" class="v-text-red-600 v-text-sm v-mb-2" x-text="error"></div>

  <div class="v-flex v-flex-col v-mb-4 v-gap-4">
    <div x-show="preview" class="v-max-w-md v-border v-border-secondary-300 v-rounded">
      <img :src="preview" alt="Preview" class="v-w-full v-h-auto v-max-h-64 v-object-contain v-rounded">
    </div>

    <div class="v-flex v-gap-2">
      <input type="file"
             accept="image/*"
             class="v-hidden"
             :id="name + '_input'"
             @change="handleFileSelect">

      <x-v-button.secondary type="button" @click="$el.previousElementSibling.click()" icon="image">
        {{ $uploadUrl ? 'Upload Image' : 'Select Image' }}
      </x-v-button.secondary>

      <x-v-button.light
              x-show="preview && preview !== initialSrc"
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
         class="v-fixed v-inset-0 v-z-50 v-overflow-auto v-bg-gray-500 v-bg-opacity-75 v-flex v-items-center v-justify-center v-p-4"
         x-transition:enter="v-transition v-ease-out v-duration-300"
         x-transition:enter-start="v-opacity-0"
         x-transition:enter-end="v-opacity-100"
         x-transition:leave="v-transition v-ease-in v-duration-200"
         x-transition:leave-start="v-opacity-100"
         x-transition:leave-end="v-opacity-0">

      <div class="v-bg-white v-rounded-lg v-shadow-xl v-w-full v-max-w-4xl v-mx-auto v-overflow-hidden"
           @click.outside="cancelCrop"
           x-transition:enter="v-transition v-ease-out v-duration-300"
           x-transition:enter-start="v-opacity-0 v-transform v-scale-95"
           x-transition:enter-end="v-opacity-100 v-transform v-scale-100"
           x-transition:leave="v-transition v-ease-in v-duration-200"
           x-transition:leave-start="v-opacity-100 v-transform v-scale-100"
           x-transition:leave-end="v-opacity-0 v-transform v-scale-95">

        <div class="v-p-4 v-border-b v-border-gray-200">
          <h3 class="v-text-lg v-font-medium v-text-gray-900">Crop Image</h3>
        </div>

        <div class="v-p-4">
          <div class="v-h-96 v-overflow-hidden">
            <img x-ref="cropperImage" src="" alt="Image to crop" class="v-block v-max-w-full">
          </div>
        </div>

        <div class="v-bg-gray-50 v-px-4 v-py-3 v-flex v-justify-end v-gap-2">
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

@pushonce('scripts')
  <script src="{{ asset('vendor/verdant/js/cropper.min.js') }}"></script>
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('imageCropper', ({
                                     name,
                                     src,
                                     aspectRatio,
                                     minWidth,
                                     minHeight,
                                     maxWidth,
                                     maxHeight,
                                     uploadUrl,
                                     required,
                                     csrfToken
                                   }) => ({
        name: name,
        showModal: false,
        initialSrc: src,
        preview: src,
        croppedImage: '',
        error: '',
        cropper: null,
        aspectRatioValue: null,

        init() {
          if (aspectRatio && aspectRatio.includes(':')) {
            const [width, height] = aspectRatio.split(':').map(Number);
            this.aspectRatioValue = width / height;
          } else if (aspectRatio && !isNaN(aspectRatio)) {
            this.aspectRatioValue = Number(aspectRatio);
          }
        },

        handleFileSelect(event) {
          const file = event.target.files[0];
          if (!file) return;

          this.error = '';

          if (file.size > 5 * 1024 * 1024) {
            this.error = 'File size exceeds 5MB limit';
            event.target.value = '';
            return;
          }

          const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
          if (!validTypes.includes(file.type)) {
            this.error = 'Invalid file type. Please use JPG, PNG, GIF, or WebP images.';
            event.target.value = '';
            return;
          }

          const reader = new FileReader();
          reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
              if (img.width < minWidth || img.height < minHeight) {
                this.error = `Image dimensions (${img.width}x${img.height}px) too small. Minimum size is ${minWidth}x${minHeight}px`;
                event.target.value = '';
                return;
              }

              if (img.width > maxWidth || img.height > maxHeight) {
                this.error = `Image dimensions (${img.width}x${img.height}px) too large. Maximum size is ${maxWidth}x${maxHeight}px`;
                event.target.value = '';
                return;
              }

              this.$refs.cropperImage.src = e.target.result;
              this.openCropModal();
            };
            img.src = e.target.result;
          };
          reader.readAsDataURL(file);
        },

        openCropModal() {
          this.showModal = true;
          this.$nextTick(() => {
            this.initCropper();
          });
        },

        initCropper() {
          if (this.cropper) {
            this.cropper.destroy();
          }

          this.cropper = new Cropper(this.$refs.cropperImage, {
            viewMode: 1,
            aspectRatio: this.aspectRatioValue,
            autoCropArea: 0.8,
            responsive: true,
            restore: false,
            center: true,
            highlight: false,
            guides: true,
            background: false,
            crop: (event) => {
              let width = event.detail.width;
              let height = event.detail.height;

              if (width < minWidth || height < minHeight) {
                this.cropper.setData({
                  width: Math.max(minWidth, width),
                  height: Math.max(minHeight, height),
                });
              }
            },
          });
        },

        async applyCrop() {
          if (!this.cropper) return;

          const canvas = this.cropper.getCroppedCanvas({
            maxWidth: maxWidth,
            maxHeight: maxHeight,
            fillColor: '#fff',
            imageSmoothingQuality: 'high',
          });

          if (!canvas) {
            this.error = 'Failed to crop image';
            return;
          }

          const croppedData = canvas.toDataURL('image/jpeg', 0.8);

          if (uploadUrl) {
            await this.uploadImage(croppedData);
          } else {
            this.preview = croppedData;
            this.croppedImage = croppedData;
          }

          this.showModal = false;
          this.destroyCropper();
        },

        async uploadImage(imageData) {
          try {
            const response = await fetch(uploadUrl, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({image: imageData})
            });

            if (!response.ok) {
              throw new Error('Upload failed');
            }

            const data = await response.json();
            this.preview = data.url;
            this.croppedImage = data.path || data.url;

            this.$dispatch('image-cropped', {
              name: this.name,
              url: data.url,
              path: data.path || data.url
            });

          } catch (error) {
            console.error('Upload failed:', error);
            this.error = 'Failed to upload image. Please try again.';
          }
        },

        cancelCrop() {
          this.showModal = false;
          this.destroyCropper();
        },

        destroyCropper() {
          if (this.cropper) {
            this.cropper.destroy();
            this.cropper = null;
          }
        }
      }));
    });
  </script>
@endpushonce

@pushonce('styles')
  <link rel="stylesheet" href="{{ asset('vendor/verdant/css/cropper.css') }}">
@endpushonce
