@props([
    'route',
    'title' => 'Filter',
    'id' => 'filterModal',
    'filter' => null,
])

<x-v-button.light @click="$dispatch('open-modal', '{{ $id }}')" icon="filter" outline>
  Filter
  @if(!empty($filter))
    <span class="v-ml-2">{{ count($filter) }}</span>
  @endif
</x-v-button.light>

@if($filter)
  <x-v-button.light @click="clearAllFilters()" icon="times" outline tooltip="Clear Filters"></x-v-button.light>
@endif

<div x-data="{ open: false }">

  <x-v-modal :id="$id" maxWidth="lg">
    <form id="{{ $id }}-form" class="v-form v-auto-filter" method="get" action="{{ $route }}">
      <div class="sm:v-flex sm:v-items-start">
        <div class="v-w-full">
          <h3 class="v-text-lg v-font-medium v-leading-6 v-text-gray-900" id="modal-title">
            {{ $title }}
          </h3>
          <div class="v-mt-4 v-mb-4 v-space-y-4">
            {{ $slot }}
          </div>
        </div>
      </div>
      <div class="v-mt-5 sm:v-mt-4 v-flex v-justify-between">
        <x-v-button.light outline @click="$dispatch('close-modal', '{{ $id }}')">Cancel</x-v-button.light>

        <x-v-button.group align="right">
          <x-v-button.warning outline @click="resetFilterForm()">Reset Filter</x-v-button.warning>
          <x-v-button.primary type="submit">Apply Filter</x-v-button.primary>
        </x-v-button.group>
      </div>
    </form>
  </x-v-modal>
</div>

<script>
  function resetFilterForm() {
    const form = document.getElementById('{{ $id }}-form');

    form.querySelectorAll('input[type="text"], input[type="password"], input[type="file"], select, textarea')
        .forEach(element => element.value = '');

    form.querySelectorAll('input[type="checkbox"], input[type="radio"]')
        .forEach(element => element.checked = false);

    form.querySelectorAll('select').forEach(select => {
      select.value = '';
      if (select.classList.contains('selectpicker')) {
        if (typeof $(select).selectpicker === 'function') {
          $(select).selectpicker('refresh');
        }
      }
    });
  }

  function clearAllFilters() {
    window.location.href = window.location.pathname + '?reset_filter';
  }
</script>
