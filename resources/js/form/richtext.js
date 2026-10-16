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
