<div x-data="richtextToolbar()"
     class="v-flex v-items-center v-p-2 v-bg-gray-50 dark:v-bg-gray-700 v-border-b v-border-secondary-300 dark:v-border-gray-600">

  <button type="button" @mousedown.prevent="exec('bold')"
          :class="{'v-bg-gray-200 dark:v-bg-gray-600': isActive('bold')}"
          class="v-p-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-bold"></i>
  </button>
  <button type="button" @mousedown.prevent="exec('italic')"
          :class="{'v-bg-gray-200 dark:v-bg-gray-600': isActive('italic')}"
          class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-italic"></i>
  </button>
  <button type="button" @mousedown.prevent="exec('underline')"
          :class="{'v-bg-gray-200 dark:v-bg-gray-600': isActive('underline')}"
          class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-underline"></i>
  </button>

  <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300 dark:v-border-gray-600"></div>

  <button type="button" @mousedown.prevent="exec('justifyLeft')"
          :class="{'v-bg-gray-200': isActive('justifyLeft')}"
          class="v-p-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-align-left"></i>
  </button>
  <button type="button" @mousedown.prevent="exec('justifyCenter')"
          :class="{'v-bg-gray-200': isActive('justifyCenter')}"
          class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-align-center"></i>
  </button>
  <button type="button" @mousedown.prevent="exec('justifyRight')"
          :class="{'v-bg-gray-200': isActive('justifyRight')}"
          class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-align-right"></i>
  </button>
  <button type="button" @mousedown.prevent="exec('justifyFull')"
          :class="{'v-bg-gray-200': isActive('justifyFull')}"
          class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-align-justify"></i>
  </button>

  <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300 dark:v-border-gray-600"></div>

  <button type="button" @mousedown.prevent="exec('insertUnorderedList')"
          :class="{'v-bg-gray-200': isActive('insertUnorderedList')}"
          class="v-p-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-list-ul"></i>
  </button>
  <button type="button" @mousedown.prevent="exec('insertOrderedList')"
          :class="{'v-bg-gray-200': isActive('insertOrderedList')}"
          class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-list-ol"></i>
  </button>

  <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300 dark:v-border-gray-600"></div>

  <button type="button" @mousedown.prevent="insertLink()"
          :class="{'v-bg-gray-200 dark:v-bg-gray-600': isLinkActive()}"
          title="Insert link"
          class="v-p-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-link"></i>
  </button>
  <button type="button" @mousedown.prevent="removeLink()"
          title="Remove link"
          class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-link-slash"></i>
  </button>

  <div class="v-h-4 v-mx-2 v-border-l v-border-secondary-300 dark:v-border-gray-600"></div>

  <button type="button" @mousedown.prevent="clearFormatting()"
          title="Clear formatting"
          class="v-p-1 v-ml-1 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 v-text-gray-700 dark:v-text-gray-300">
    <i class="fa-solid fa-eraser"></i>
  </button>
</div>

@pushonce('scripts')
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('richtextToolbar', () => ({
        _t: 0,

        exec(command, value = null) {
          document.execCommand(command, false, value)
          this._t++
        },

        isActive(command) {
          this._t
          return document.queryCommandState(command)
        },

        isLinkActive() {
          this._t
          const sel = window.getSelection()
          if (!sel?.rangeCount) return false
          let node = sel.anchorNode
          while (node) {
            if (node.tagName === 'A') return true
            if (node.contentEditable === 'true') break
            node = node.parentNode
          }
          return false
        },

        insertLink() {
          const sel = window.getSelection()
          if (this.isLinkActive()) {
            let node = sel?.anchorNode
            while (node) {
              if (node.tagName === 'A') {
                const newUrl = prompt('Edit the URL:', node.getAttribute('href'))
                if (newUrl) {
                  node.setAttribute('href', newUrl)
                  this._t++
                }
                return
              }
              if (node.contentEditable === 'true') break
              node = node.parentNode
            }
          }

          const url = prompt('Enter the URL:', 'https://')
          if (!url) return

          const text = sel?.toString()
          if (text) {
            document.execCommand('createLink', false, url)
            const newSel = window.getSelection()
            let node = newSel?.anchorNode
            while (node) {
              if (node.tagName === 'A') {
                node.className = 'text-primary-600 dark:text-primary-400 hover:underline text-sm'
                node.setAttribute('target', '_blank')
                break
              }
              if (node.contentEditable === 'true') break
              node = node.parentNode
            }
          } else {
            const linkText = prompt('Enter the link text:', url)
            if (linkText) {
              document.execCommand('insertHTML', false, `<a href="${url}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline text-sm">${linkText}</a>`)
            }
          }
          this._t++
        },

        removeLink() {
          const sel = window.getSelection()
          let node = sel?.anchorNode
          while (node) {
            if (node.tagName === 'A') {
              node.parentNode.replaceChild(document.createTextNode(node.textContent), node)
              this._t++
              return
            }
            if (node.contentEditable === 'true') break
            node = node.parentNode
          }
        },

        clearFormatting() {
          document.execCommand('removeFormat')
          this._t++
        },
      }))
    })
  </script>
@endpushonce
