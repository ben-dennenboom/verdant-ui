@props(['name', 'label' => null, 'value' => null, 'required' => false])

<div x-data="richText('{{ $name }}')"
     class="v-mb-4 richtext-container"
     data-name="{{ $name }}"
     data-label="{{ $label }}"
     data-value="{{ old($name, $value) }}"
     {{ $required ? 'data-required' : '' }}
     @error($name) data-error="{{ $message }}" @enderror>

  @if($label)
    <div class="v-flex v-items-center v-justify-between">
      <label :for="name" class="v-block v-font-medium v-text-gray-700">
        {{ $label }}
        @if($required)
          <span class="v-text-red-500">*</span>
        @endif
      </label>
      {{ $actions ?? '' }}
    </div>
  @endif

  <div class="v-mb-2 v-border v-border-secondary-300 v-mt-1">
    <div class="v-flex v-items-center v-p-2 v-bg-gray-50 v-border-b v-border-secondary-300">
      <button type="button" @click="toggleFormat('bold')"
              :class="{'v-bg-gray-200': isActive('bold') }"
              class="v-p-1 hover:v-bg-gray-200">
        <i class="fa-solid fa-bold"></i>
      </button>
      <button type="button" @click="toggleFormat('italic')"
              :class="{'v-bg-gray-200': isActive('italic') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200">
        <i class="fa-solid fa-italic"></i>
      </button>
      <button type="button" @click="toggleFormat('underline')"
              :class="{'v-bg-gray-200': isActive('underline') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200">
        <i class="fa-solid fa-underline"></i>
      </button>

      <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300"></div>

      <button type="button" @click="alignText('left')"
              :class="{'v-bg-gray-200': isActive('justifyLeft') }"
              class="v-p-1 hover:v-bg-gray-200">
        <i class="fa-solid fa-align-left"></i>
      </button>
      <button type="button" @click="alignText('center')"
              :class="{'v-bg-gray-200': isActive('justifyCenter') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200">
        <i class="fa-solid fa-align-center"></i>
      </button>
      <button type="button" @click="alignText('right')"
              :class="{'v-bg-gray-200': isActive('justifyRight') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200">
        <i class="fa-solid fa-align-right"></i>
      </button>
      <button type="button" @click="alignText('justify')"
              :class="{'v-bg-gray-200': isActive('justifyFull') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200">
        <i class="fa-solid fa-align-justify"></i>
      </button>

      <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300"></div>

      <button type="button" @click="toggleFormat('insertUnorderedList')"
              :class="{'v-bg-gray-200': isActive('insertUnorderedList') }"
              class="v-p-1 hover:v-bg-gray-200">
        <i class="fa-solid fa-list-ul"></i>
      </button>
      <button type="button" @click="toggleFormat('insertOrderedList')"
              :class="{'v-bg-gray-200': isActive('insertOrderedList') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200">
        <i class="fa-solid fa-list-ol"></i>
      </button>
      <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300"></div>
      <button type="button" @click="pasteWithoutFormat()"
              :class="{'v-bg-gray-200': isActive('pasteWithoutFormat') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200">
        <i class="fa-solid fa-paste"></i>
      </button>
    </div>

    <div x-ref="editor"
         contenteditable="true"
         @input.debounce.150ms="updateContent"
         @keydown.tab.prevent="handleTab"
         class="richtext-editor v-px-3 v-py-2 v-min-h-[100px] focus:v-outline-none"
         :required="required">
    </div>
  </div>

  <input type="hidden" :name="uniqueName" x-model="content">

  @error($name)
  <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
  @enderror
</div>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('richText', (uniqueName) => ({
      content: '',
      uniqueName: uniqueName,

      init() {
        this.name = this.$el.getAttribute('data-name')
        this.label = this.$el.getAttribute('data-label')
        this.required = this.$el.hasAttribute('data-required')
        this.error = this.$el.getAttribute('data-error')

        const value = this.$el.getAttribute('data-value')
        if (value) {
          this.$refs.editor.innerHTML = value
          this.content = value
        }

        if (this.error) {
          this.$refs.editor.focus()
          this.$refs.editor.scrollIntoView({behavior: 'smooth', block: 'center'})
        }
      },

      toggleFormat(command) {
        document.execCommand(command, false, null)
        this.$refs.editor.focus()
        this.updateContent()
      },

      alignText(alignment) {
        document.execCommand('justify' + alignment.charAt(0).toUpperCase() + alignment.slice(1))
        this.$refs.editor.focus()
        this.updateContent()
      },

      isActive(command) {
        return document.queryCommandState(command)
      },

      pasteWithoutFormat() {
console.log('paste')
        const clipboardData = event.clipboardData || window.clipboardData;

        console.log(clipboardData)
        if (!clipboardData) {
            return;
        }

        const plainText = clipboardData.getData('text/plain');

        console.log(plainText)
        if(!plainText) {
          return;
        }

        document.execCommand('insertText', false, plainText);
        console.log("ok")
        this.$refs.editor.focus();
        this.updateContent();
        console.log("refresh?")
      },

      handleTab(e) {
        if (this.isActive('insertUnorderedList') || this.isActive('insertOrderedList')) {
          document.execCommand('indent', false, null)
        } else {
          document.execCommand('insertHTML', false, '&emsp;')
        }
        this.updateContent()
      },

      updateContent() {
        let newContent = this.$refs.editor.innerHTML.trim()
        if (newContent.replace(/<[^>]*>|&nbsp;?/gm, '').trim() === '') {
          newContent = ''
        }

        this.content = newContent
        this.$dispatch('editor-updated', {content: newContent})
      }
    }))
  })
</script>
