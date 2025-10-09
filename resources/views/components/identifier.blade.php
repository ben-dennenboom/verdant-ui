@props(['value'])

<div class="v-block md:v-flex v-items-center">
    <span class="v-font-mono v-bg-secondary-100 dark:v-bg-secondary-800 v-text-gray-800 dark:v-text-gray-200 v-px-2 v-py-1 v-rounded">{{ $value }}</span>
    <x-v-button.transparent onclick="copyToClipboard('{{ $value }}')" class="v-p-1" icon="copy"/>
</div>

<script>
  function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
      console.log('Copied to clipboard');
    }).catch(err => {
      console.error('Failed to copy: ', err);
    });
  }
</script>
