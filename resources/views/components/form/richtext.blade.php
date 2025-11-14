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
      <label :for="name" class="v-block v-font-medium v-text-gray-700 dark:v-text-gray-300">
        {{ $label }}
        @if($required)
          <span class="v-text-red-500">*</span>
        @endif
      </label>
      {{ $actions ?? '' }}
    </div>
  @endif

  <div class="v-mb-2 v-border v-border-secondary-300 dark:v-border-gray-600 v-mt-1">
    <div class="v-flex v-items-center v-p-2 v-bg-gray-50 dark:v-bg-gray-700 v-border-b v-border-secondary-300 dark:v-border-gray-600">
      <button type="button" @click="toggleFormat('bold')"
              :class="{'v-bg-gray-200 dark:v-bg-gray-600': isActive('bold') }"
              class="v-p-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-bold"></i>
      </button>
      <button type="button" @click="toggleFormat('italic')"
              :class="{'v-bg-gray-200 dark:v-bg-gray-600': isActive('italic') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-italic"></i>
      </button>
      <button type="button" @click="toggleFormat('underline')"
              :class="{'v-bg-gray-200 dark:v-bg-gray-600': isActive('underline') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-underline"></i>
      </button>

      <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300 dark:v-border-gray-600"></div>

      <button type="button" @click="alignText('left')"
              :class="{'v-bg-gray-200': isActive('justifyLeft') }"
              class="v-p-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-align-left"></i>
      </button>
      <button type="button" @click="alignText('center')"
              :class="{'v-bg-gray-200': isActive('justifyCenter') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-align-center"></i>
      </button>
      <button type="button" @click="alignText('right')"
              :class="{'v-bg-gray-200': isActive('justifyRight') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-align-right"></i>
      </button>
      <button type="button" @click="alignText('justify')"
              :class="{'v-bg-gray-200': isActive('justifyFull') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-align-justify"></i>
      </button>

      <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300 dark:v-border-gray-600"></div>

      <button type="button" @click="toggleFormat('insertUnorderedList')"
              :class="{'v-bg-gray-200': isActive('insertUnorderedList') }"
              class="v-p-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-list-ul"></i>
      </button>
      <button type="button" @click="toggleFormat('insertOrderedList')"
              :class="{'v-bg-gray-200': isActive('insertOrderedList') }"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-list-ol"></i>
      </button>

      <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300 dark:v-border-gray-600"></div>

      <button type="button" @click="insertLink()"
              :class="{'v-bg-gray-200 dark:v-bg-gray-600': isLinkActive() }"
              title="Insert link"
              class="v-p-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-link"></i>
      </button>
      <button type="button" @click="removeLink()"
              title="Remove link"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-link-slash"></i>
      </button>

      <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300 dark:v-border-gray-600"></div>

      <button type="button" @click="clearFormatting()"
              title="Clear formatting"
              class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
        <i class="fa-solid fa-eraser"></i>
      </button>
    </div>

    <div x-ref="editor"
         contenteditable="true"
         @input.debounce.150ms="updateContent"
         @keydown.tab.prevent="handleTab"
         class="richtext-editor v-px-3 v-py-2 v-min-h-[100px] focus:v-outline-none v-bg-white dark:v-bg-gray-800 v-text-gray-900 dark:v-text-gray-100"
         :required="required">
    </div>
  </div>

  <input type="hidden" :name="uniqueName" x-model="content">

  @error($name)
  <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
  @enderror
</div>

@pushonce('scripts')
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

        handleTab(e) {
          if (this.isActive('insertUnorderedList') || this.isActive('insertOrderedList')) {
            document.execCommand('indent', false, null)
          } else {
            document.execCommand('insertHTML', false, '&emsp;')
          }
          this.updateContent()
        },

        isLinkActive() {
          const selection = window.getSelection()
          if (selection.rangeCount > 0) {
            let node = selection.anchorNode
            while (node && node !== this.$refs.editor) {
              if (node.tagName === 'A') {
                return true
              }
              node = node.parentNode
            }
          }
          return false
        },

        insertLink() {
          const selection = window.getSelection()
          const selectedText = selection.toString()

          if (this.isLinkActive()) {
            let node = selection.anchorNode
            while (node && node !== this.$refs.editor) {
              if (node.tagName === 'A') {
                const currentUrl = node.getAttribute('href')
                const newUrl = prompt('Edit the URL:', currentUrl)
                if (newUrl) {
                  node.setAttribute('href', newUrl)
                  this.updateContent()
                }
                this.$refs.editor.focus()
                return
              }
              node = node.parentNode
            }
          }

          let url = prompt('Enter the URL:', 'https://')

          if (url) {
            if (selectedText) {
              document.execCommand('createLink', false, url)
              const selection = window.getSelection()
              if (selection.rangeCount > 0) {
                let node = selection.anchorNode
                while (node && node !== this.$refs.editor) {
                  if (node.tagName === 'A') {
                    node.className = 'text-primary-600 dark:text-primary-400 hover:underline text-sm'
                    node.setAttribute('target', '_blank')
                    break
                  }
                  node = node.parentNode
                }
              }
            } else {
              const linkText = prompt('Enter the link text:', url)
              if (linkText) {
                document.execCommand('insertHTML', false, `<a href="${url}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline text-sm">${linkText}</a>`)
              }
            }
            this.$refs.editor.focus()
            this.updateContent()
          }
        },

        removeLink() {
          const selection = window.getSelection()
          if (selection.rangeCount > 0) {
            let node = selection.anchorNode
            while (node && node !== this.$refs.editor) {
              if (node.tagName === 'A') {
                const text = node.textContent
                const textNode = document.createTextNode(text)
                node.parentNode.replaceChild(textNode, node)
                this.updateContent()
                this.$refs.editor.focus()
                return
              }
              node = node.parentNode
            }
          }
        },

        clearFormatting() {
          const plainText = this.$refs.editor.innerText || this.$refs.editor.textContent

          this.$refs.editor.innerHTML = ''

          if (plainText.trim()) {
            this.$refs.editor.textContent = plainText
          }

          this.$refs.editor.focus()
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
@endpushonce
