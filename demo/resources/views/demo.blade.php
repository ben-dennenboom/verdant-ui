<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verdant UI Demo</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @verdantAssets
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
<!-- Header -->
<header class="bg-white shadow-sm border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center">
      <h1 class="text-4xl font-bold text-gray-900 mb-2">Verdant UI</h1>
      <p class="text-xl text-gray-600">Component Library Demo</p>
      <div class="mt-4 flex justify-center">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            Interactive Demo
          </span>
      </div>
    </div>
  </div>
</header>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- Left Column - Navigation & Info -->
    <div class="lg:col-span-3">
      <div class="sticky top-8">
        <nav class="bg-white rounded-lg shadow-sm p-6 mb-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Components</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="#buttons" class="text-blue-600 hover:text-blue-800 transition-colors">Buttons</a></li>
            <li><a href="#forms" class="text-blue-600 hover:text-blue-800 transition-colors">Forms</a></li>
            <li><a href="#calendar" class="text-blue-600 hover:text-blue-800 transition-colors">Calendar</a></li>
            <li><a href="#timeslots" class="text-blue-600 hover:text-blue-800 transition-colors">Timeslots</a></li>
            <li><a href="#modals" class="text-blue-600 hover:text-blue-800 transition-colors">Modals</a></li>
            <li><a href="#tables" class="text-blue-600 hover:text-blue-800 transition-colors">Tables</a></li>
            <li><a href="#navigation" class="text-blue-600 hover:text-blue-800 transition-colors">Navigation</a></li>
            <li><a href="#utilities" class="text-blue-600 hover:text-blue-800 transition-colors">Utilities</a></li>
          </ul>
        </nav>

        <div class="bg-blue-50 rounded-lg p-6">
          <h4 class="font-semibold text-blue-900 mb-2">About Verdant UI</h4>
          <p class="text-sm text-blue-700">A comprehensive UI component library built with Tailwind CSS and Alpine.js
            for Laravel applications.</p>
        </div>
      </div>
    </div>

    <!-- Right Column - Components -->
    <div class="lg:col-span-9 space-y-12">

      <!-- Buttons Section -->
      <section id="buttons" class="bg-white rounded-lg shadow-sm p-8">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Buttons</h2>
          <p class="text-gray-600">Various button styles and states for different actions and contexts.</p>
        </div>

        <div class="space-y-6">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Button Variants</h3>
            <div class="flex flex-wrap gap-3">
              <x-v-button.primary>Primary Button</x-v-button.primary>
              <x-v-button.secondary>Secondary Button</x-v-button.secondary>
              <x-v-button.accent>Accent Button</x-v-button.accent>
              <x-v-button.danger>Danger Button</x-v-button.danger>
              <x-v-button.warning>Warning Button</x-v-button.warning>
              <x-v-button.light>Light Button</x-v-button.light>
              <x-v-button.transparent>Transparent Button</x-v-button.transparent>
            </div>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Button Groups</h3>
            <x-v-button.group>
              <x-v-button.primary>First</x-v-button.primary>
              <x-v-button.secondary>Second</x-v-button.secondary>
              <x-v-button.accent>Third</x-v-button.accent>
            </x-v-button.group>
          </div>
        </div>
      </section>

      <!-- Form Components Section -->
      <section id="forms" class="bg-white rounded-lg shadow-sm p-8">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Form Components</h2>
          <p class="text-gray-600">Input fields, selects, checkboxes and other form elements with consistent
            styling.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div class="space-y-6">
            <x-v-form.input
                    label="Text Input"
                    name="text_input"
                    placeholder="Enter some text..."/>

            <x-v-form.textarea
                    label="Textarea"
                    name="textarea"
                    placeholder="Enter your message..."/>

            <x-v-form.select 
              label="Select Dropdown" 
              name="select"
              required
              :options="[
                ['value' => 'option1', 'label' =>'Option 1'],
                ['value' => 'option2', 'label' =>'Option 2'],
                ['value' => 'option3', 'label' =>'Option 3'],
              ]" />
          </div>

          <div class="space-y-6">
            <x-v-form.checkbox
                    label="Checkbox Example"
                    name="checkbox"/>

            <x-v-form.file-input
                    label="File Upload"
                    name="file_upload"/>

            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Form Actions</h3>
              <x-v-form.actions>
                <x-v-button.secondary>Cancel</x-v-button.secondary>
                <x-v-button.primary>Save Changes</x-v-button.primary>
              </x-v-form.actions>
            </div>
          </div>
        </div>
      </section>

      <!-- Calendar Section -->
      <section id="calendar" class="bg-white rounded-lg shadow-sm p-8">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Calendar</h2>
          <p class="text-gray-600">Interactive calendar component for date selection and scheduling.</p>
        </div>

        <div class="max-w">
          <x-v-calendar/>
        </div>
      </section>

      <!-- Timeslot Selector Section -->
      <section id="timeslots" class="bg-white rounded-lg shadow-sm p-8">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Timeslot Selector</h2>
          <p class="text-gray-600">Calendar-based timeslot selection with available time slots per day.</p>
        </div>

        <div class="max-w">
          <x-v-timeslot-selector 
            name="appointment_slot"
            :timeslots="[
              ['id' => 1, 'date' => '2025-07-05', 'time' => '09:00', 'duration' => '30 minutes'],
              ['id' => 2, 'date' => '2025-07-05', 'time' => '09:30', 'duration' => '30 minutes'],
              ['id' => 3, 'date' => '2025-07-05', 'time' => '14:00', 'duration' => '60 minutes'],
              ['id' => 4, 'date' => '2025-07-07', 'time' => '10:00', 'duration' => '30 minutes'],
              ['id' => 5, 'date' => '2025-07-07', 'time' => '11:00', 'duration' => '30 minutes'],
              ['id' => 6, 'date' => '2025-07-08', 'time' => '15:00', 'duration' => '45 minutes'],
              ['id' => 7, 'date' => '2025-07-12', 'time' => '09:00', 'duration' => '30 minutes'],
              ['id' => 8, 'date' => '2025-07-12', 'time' => '10:30', 'duration' => '30 minutes'],
              ['id' => 9, 'date' => '2025-07-12', 'time' => '16:00', 'duration' => '60 minutes'],
              ['id' => 10, 'date' => '2025-07-15', 'time' => '13:00', 'duration' => '30 minutes']
            ]" />
        </div>
      </section>

      <!-- Modals Section -->
      <section id="modals" class="bg-white rounded-lg shadow-sm p-8">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Modals</h2>
          <p class="text-gray-600">Dialog windows for user interactions, confirmations, and content display.</p>
        </div>

        <div class="space-y-6">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Standard Modal</h3>
            <x-v-button.primary x-data @click="$dispatch('open-modal', 'demo-modal')">
              Open Modal
            </x-v-button.primary>

            <x-v-modal id="demo-modal">
                <h3 class="v-text-lg v-font-medium v-text-gray-900 v-mb-4">Demo Modal</h3>
                <p class="v-text-gray-600 v-mb-6">This is a demo modal from Verdant UI library using the component
                  syntax.</p>
                <div class="v-flex v-justify-end v-space-x-2 v-mt-4">
                  <x-v-button.secondary @click="$dispatch('close-modal', 'demo-modal')">
                    Close
                  </x-v-button.secondary>
                </div>
            </x-v-modal>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Confirmation Modal</h3>
            <x-v-button.danger x-data @click="$dispatch('open-modal', 'confirm-modal')">
              Delete Item
            </x-v-button.danger>

            <x-v-modal.confirm
                    id="confirm-modal"
                    title="Delete Confirmation"
                    message="Are you sure you want to delete this item? This action cannot be undone."/>
          </div>
        </div>
      </section>

      <!-- Tables Section -->
      <section id="tables" class="bg-white rounded-lg shadow-sm p-8">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Tables</h2>
          <p class="text-gray-600">Data tables with sorting, filtering, and responsive design.</p>
        </div>

        <x-v-table.list>
          <x-slot name="header">
            <x-v-table.row>
              <x-v-table.header title="Name"/>
              <x-v-table.header title="Email"/>
              <x-v-table.header title="Role"/>
            </x-v-table.row>
          </x-slot>

          <x-v-table.row>
            <x-v-table.cell>John Doe</x-v-table.cell>
            <x-v-table.cell>john@example.com</x-v-table.cell>
            <x-v-table.cell>Admin</x-v-table.cell>
          </x-v-table.row>

          <x-v-table.row>
            <x-v-table.cell>Jane Smith</x-v-table.cell>
            <x-v-table.cell>jane@example.com</x-v-table.cell>
            <x-v-table.cell>User</x-v-table.cell>
          </x-v-table.row>

          <x-v-table.row>
            <x-v-table.cell>Bob Johnson</x-v-table.cell>
            <x-v-table.cell>bob@example.com</x-v-table.cell>
            <x-v-table.cell>Editor</x-v-table.cell>
          </x-v-table.row>
        </x-v-table.list>
      </section>

      <!-- Navigation Section -->
      <section id="navigation" class="bg-white rounded-lg shadow-sm p-8">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Navigation</h2>
          <p class="text-gray-600">Breadcrumbs, content headers, and navigation components.</p>
        </div>

        <div class="space-y-8">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Content Header</h3>
            <x-v-content-header
                    title="Dashboard"
                    subtitle="Welcome to your dashboard overview"/>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Breadcrumbs</h3>
            <nav class="flex">
              <x-v-breadcrumb-item icon="home"/>
              <x-v-breadcrumb-item label="Users"/>
              <x-v-breadcrumb-item label="Profile" last/>
            </nav>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Details Page</h3>
            <x-v-details.page title="User Profile">
              <x-v-details.item label="Name" value="John Doe"/>
              <x-v-details.item label="Email" value="john@example.com"/>
              <x-v-details.item label="Role" value="Administrator"/>
              <x-v-details.item label="Status" value="Active"/>
            </x-v-details.page>
          </div>
        </div>
      </section>

      <!-- Utilities Section -->
      <section id="utilities" class="bg-white rounded-lg shadow-sm p-8">
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Utilities</h2>
          <p class="text-gray-600">Helper components for tooltips, identifiers, and search functionality.</p>
        </div>

        <div class="space-y-8">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Identifier</h3>
            <x-v-identifier value="VU-2024-001" />
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tooltip</h3>
            <x-v-tooltip text="This is a helpful tooltip message">
              <x-v-button.primary>Hover for tooltip</x-v-button.primary>
            </x-v-tooltip>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Search Filter</h3>
            <x-v-filters.search label="Search Components" placeholder="Search for components..." />
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Table Filter</h3>
            <x-v-table.filter route="/" title="Filter Options" id="demo-filter">
              <x-v-form.input label="Name" name="name" placeholder="Filter by name..." />
              <x-v-form.select label="Status" name="status" :options="[['value' => 'active', 'label' => 'Active'], ['value' => 'inactive', 'label' => 'Inactive']]" />
            </x-v-table.filter>
          </div>
        </div>
      </section>
    </div>
  </div>
</main>

<!-- Footer -->
<footer class="bg-white border-t border-gray-200 mt-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center">
      <p class="text-gray-500">Verdant UI Library Demo</p>
      <p class="text-sm text-gray-400 mt-2">Built with Tailwind CSS, Alpine.js, and Laravel</p>
    </div>
  </div>
</footer>
</body>
</html>
