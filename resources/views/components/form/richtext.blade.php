@props(['name', 'label' => null, 'value' => null, 'required' => false])

@php
  $aligned = (bool) config('verdant.ui.aligned_form_controls');
@endphp

<div x-data="richText('{{ $name }}')"
     class="v:mb-4 richtext-container"
     data-name="{{ $name }}"
     data-label="{{ $label }}"
     data-value="{{ old($name, $value) }}"
     {{ $required ? 'data-required' : '' }}
     @error($name) data-error="{{ $message }}" @enderror>

  @if($label)
    <div class="v:flex v:items-center v:justify-between">
      <label :for="name" class="v:block v:font-medium v:text-gray-700 v:dark:text-gray-300">
        {{ $label }}@if($required)<span class="required_asterisk">*</span>@endif
      </label>
      {{ $actions ?? '' }}
    </div>
  @endif

  <div class="v:mb-2 v:border v:border-secondary-300 v:dark:border-gray-600 v:mt-1 {{$aligned ? 'rounded overflow-hidden' : ''}}">
    <div class="v:flex v:items-center v:p-2 v:bg-gray-50 v:dark:bg-gray-700 v:border-b v:border-secondary-300 v:dark:border-gray-600">
      <button type="button" @click="toggleFormat('bold')"
              :class="{'v:bg-gray-200 v:dark:bg-gray-600': isActive('bold') }"
              class="v:p-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-bold"></i>
      </button>
      <button type="button" @click="toggleFormat('italic')"
              :class="{'v:bg-gray-200 v:dark:bg-gray-600': isActive('italic') }"
              class="v:p-1 v:ml-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-italic"></i>
      </button>
      <button type="button" @click="toggleFormat('underline')"
              :class="{'v:bg-gray-200 v:dark:bg-gray-600': isActive('underline') }"
              class="v:p-1 v:ml-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-underline"></i>
      </button>

      <div class="v:h-4 v:mx-2 v:border-l v:border-secondary-300 v:dark:border-gray-600"></div>

      <button type="button" @click="alignText('left')"
              :class="{'v:bg-gray-200': isActive('justifyLeft') }"
              class="v:p-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-align-left"></i>
      </button>
      <button type="button" @click="alignText('center')"
              :class="{'v:bg-gray-200': isActive('justifyCenter') }"
              class="v:p-1 v:ml-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-align-center"></i>
      </button>
      <button type="button" @click="alignText('right')"
              :class="{'v:bg-gray-200': isActive('justifyRight') }"
              class="v:p-1 v:ml-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-align-right"></i>
      </button>
      <button type="button" @click="alignText('justify')"
              :class="{'v:bg-gray-200': isActive('justifyFull') }"
              class="v:p-1 v:ml-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-align-justify"></i>
      </button>

      <div class="v:h-4 v:mx-2 v:border-l v:border-secondary-300 v:dark:border-gray-600"></div>

      <button type="button" @click="toggleFormat('insertUnorderedList')"
              :class="{'v:bg-gray-200': isActive('insertUnorderedList') }"
              class="v:p-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-list-ul"></i>
      </button>
      <button type="button" @click="toggleFormat('insertOrderedList')"
              :class="{'v:bg-gray-200': isActive('insertOrderedList') }"
              class="v:p-1 v:ml-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-list-ol"></i>
      </button>

      <div class="v:h-4 v:mx-2 v:border-l v:border-secondary-300 v:dark:border-gray-600"></div>

      <button type="button" @click="insertLink()"
              :class="{'v:bg-gray-200 v:dark:bg-gray-600': isLinkActive() }"
              title="Insert link"
              class="v:p-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-link"></i>
      </button>
      <button type="button" @click="removeLink()"
              title="Remove link"
              class="v:p-1 v:ml-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-link-slash"></i>
      </button>

      <div class="v:h-4 v:mx-2 v:border-l v:border-secondary-300 v:dark:border-gray-600"></div>

      <button type="button" @click="clearFormatting()"
              title="Clear formatting"
              class="v:p-1 v:ml-1 v:hover:bg-gray-200 v:dark:hover:bg-gray-600 v:text-gray-700 v:dark:text-gray-300 rounded">
        <i class="fa-solid fa-eraser"></i>
      </button>
    </div>

    <div x-ref="editor"
         contenteditable="true"
         @input.debounce.150ms="updateContent"
         @keydown.tab.prevent="handleTab"
         class="richtext-editor v:px-3 v:py-2 v:min-h-25 v:focus:outline-hidden v:bg-white v:dark:bg-gray-800 v:text-gray-900 v:dark:text-gray-100"
         :required="required">
    </div>
  </div>

  <input type="hidden" :name="uniqueName" x-model="content">

  @error($name)
  <p class="v:mt-2 v:text-red-600">{{ $message }}</p>
  @enderror
</div>

@once
  <script src="{{ url('vendor/verdant/js/form/richtext.js') }}" type="module"></script>
@endonce
