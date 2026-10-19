@extends('layouts.app')

@section('title', 'Verdant UI Demo - Dennenboom')

@section('content')
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

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-3">
            <div class="sticky top-8">
                <nav class="bg-white rounded-lg shadow-sm p-6 mb-6 max-h-[70vh] overflow-y-auto"
                     x-data="{
                         activeSection: 'installation',
                         sections: ['installation', 'buttons', 'forms', 'translations', 'tables', 'dynamic-tables', 'grids', 'grid-toolbars', 'navigation', 'overlays', 'content', 'utilities'],
                         init() {
                             const observer = new IntersectionObserver((entries) => {
                                 entries.forEach(entry => {
                                     if (entry.isIntersecting) {
                                         this.activeSection = entry.target.id;
                                     }
                                 });
                             }, { rootMargin: '-20% 0px -70% 0px' });

                             this.sections.forEach(id => {
                                 const el = document.getElementById(id);
                                 if (el) observer.observe(el);
                             });
                         }
                     }">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contents</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#installation" :class="activeSection === 'installation' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Installation</a></li>
                    </ul>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-4">Components</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#buttons" :class="activeSection === 'buttons' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Buttons</a></li>
                        <li><a href="#forms" :class="activeSection === 'forms' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Form fields</a></li>
                        <li><a href="#translations" :class="activeSection === 'translations' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Translation fields</a></li>
                        <li><a href="#tables" :class="activeSection === 'tables' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Tables</a></li>
                        <li><a href="#dynamic-tables" :class="activeSection === 'dynamic-tables' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Dynamic tables</a></li>
                        <li><a href="#grids" :class="activeSection === 'grids' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Grids</a></li>
                        <li><a href="#grid-toolbars" :class="activeSection === 'grid-toolbars' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Grid toolbar</a></li>
                        <li><a href="#navigation" :class="activeSection === 'navigation' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Navigation</a></li>
                        <li><a href="#overlays" :class="activeSection === 'overlays' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Overlays</a></li>
                        <li><a href="#content" :class="activeSection === 'content' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Content</a></li>
                        <li><a href="#utilities" :class="activeSection === 'utilities' ? 'font-bold text-blue-800' : 'text-blue-600 hover:text-blue-800'" class="transition-colors">Utilities</a></li>
                    </ul>
                </nav>

                <div class="bg-blue-50 rounded-lg p-6">
                    <h4 class="font-semibold text-blue-900 mb-2">About Verdant UI</h4>
                    <p class="text-sm text-blue-700">A comprehensive UI component library built with Tailwind CSS and
                        Alpine.js
                        for Laravel applications.</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-9 space-y-12">

            {{-- ============================================================ --}}
            {{-- INSTALLATION                                                 --}}
            {{-- ============================================================ --}}
            <section id="installation" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Installation</h2>
                    <p class="text-gray-600">How to add Verdant UI to a Laravel application.</p>
                </div>

                @php $code = <<<'BLADE'
composer require dennenboom/verdant-ui
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="text-sm font-semibold text-gray-900">1. Install the package</h4>
                        <p class="mt-1 text-sm text-gray-600">Pull <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">dennenboom/verdant-ui</code> in with Composer.</p>
                    </div>
                    <div class="relative bg-gray-900">
                        <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span data-copy-label>Copy</span>
                        </button>
                        <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<head>
    ...
    @verdantAssets
</head>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="text-sm font-semibold text-gray-900">2. Publish the assets</h4>
                        <p class="mt-1 text-sm text-gray-600">After installation, add the Verdant assets to your layout's head section.</p>
                    </div>
                    <div class="relative bg-gray-900">
                        <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span data-copy-label>Copy</span>
                        </button>
                        <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                    </div>
                </article>

                @php $code = <<<'BLADE'
"post-autoload-dump": [
    "@php artisan vendor:publish --tag=verdant-assets --force",
    "php artisan cache:clear"
]
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="text-sm font-semibold text-gray-900">3. Configure Composer</h4>
                        <p class="mt-1 text-sm text-gray-600">Add this to your <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">composer.json</code>'s
                            post-autoload-dump section so the assets are republished on every install.</p>
                    </div>
                    <div class="relative bg-gray-900">
                        <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span data-copy-label>Copy</span>
                        </button>
                        <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                    </div>
                </article>
            </section>

            {{-- ============================================================ --}}
            {{-- COMPONENTS                                                   --}}
            {{-- ============================================================ --}}
            <section class="bg-white rounded-lg shadow-sm p-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Components</h2>
                    <p class="text-gray-600">Every component shipped by <code class="font-mono text-sm bg-gray-100 px-1.5 py-0.5 rounded">dennenboom/verdant-ui</code>,
                        with a preview on the left and the Blade markup that produced it on the right.</p>
                    <p class="text-gray-600 mt-3">All components are registered globally with a <code class="font-mono text-sm bg-gray-100 px-1.5 py-0.5 rounded">v-</code> prefix,
                        so a component living at <code class="font-mono text-sm bg-gray-100 px-1.5 py-0.5 rounded">resources/views/components/form/input.blade.php</code>
                        is used as <code class="font-mono text-sm bg-gray-100 px-1.5 py-0.5 rounded">&lt;x-v-form.input /&gt;</code>.</p>
                </div>
            </section>

            {{-- ============================================================ --}}
            {{-- BUTTONS                                                      --}}
            {{-- ============================================================ --}}
            <section id="buttons" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Buttons</h2>
                    <p class="text-gray-600">Seven colour variants sharing one base component, plus a group wrapper and an
                        icon-only toolbar button. Every variant accepts the same props:
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">href</code>,
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">disabled</code>,
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">icon</code>,
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">iconRight</code>,
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">outline</code>,
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">tooltip</code>,
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">tooltipPosition</code> and
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">newWindow</code>.</p>
                </div>

                @php $code = <<<'BLADE'
<x-v-button.base>Base button</x-v-button.base>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-button.base&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">The unstyled base every other variant extends. Use it directly
                            when you want to supply your own colours through <code class="font-mono text-xs">class</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.base>Base button</x-v-button.base>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-button.primary>Save changes</x-v-button.primary>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-button.primary&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">The main call to action. Use at most one per screen area.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.primary>Save changes</x-v-button.primary>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-button.secondary>Cancel</x-v-button.secondary>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-button.secondary&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Supporting action, typically paired next to a primary button.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.secondary>Cancel</x-v-button.secondary>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-button.accent>Publish</x-v-button.accent>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-button.accent&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Accent colour from the theme, for actions you want to stand apart
                            from the primary flow.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.accent>Publish</x-v-button.accent>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-button.danger icon="trash">Delete</x-v-button.danger>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-button.danger&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Destructive actions. The <code class="font-mono text-xs">icon</code>
                            prop takes a Font Awesome name without the <code class="font-mono text-xs">fa-</code> prefix.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.danger icon="trash">Delete</x-v-button.danger>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-button.warning icon="triangle-exclamation">Revoke access</x-v-button.warning>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-button.warning&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">For actions that need a second thought but are not destructive.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.warning icon="triangle-exclamation">Revoke access</x-v-button.warning>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-button.light icon="filter" outline>Filter</x-v-button.light>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-button.light&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Neutral, low-emphasis button. Used internally by the table and grid
                            filter components.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.light icon="filter" outline>Filter</x-v-button.light>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-button.transparent icon="pen">Edit</x-v-button.transparent>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-button.transparent&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Borderless button for inline row actions and icon-only controls.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.transparent icon="pen">Edit</x-v-button.transparent>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
{{-- Link button: rendered as an <a> when href is given --}}
<x-v-button.primary href="https://github.com/ben-dennenboom/verdant-ui"
                    icon="arrow-up-right-from-square"
                    newWindow>
    Open repository
</x-v-button.primary>

{{-- Disabled state --}}
<x-v-button.primary disabled>Unavailable</x-v-button.primary>

{{-- Icon on the right --}}
<x-v-button.secondary iconRight="chevron-right">Next step</x-v-button.secondary>

{{-- Built-in tooltip --}}
<x-v-button.accent tooltip="Exports the current selection" tooltipPosition="bottom">
    Export
</x-v-button.accent>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">Shared button props</h4>
                        <p class="mt-1 text-sm text-gray-600">Links, disabled state, right-hand icons and tooltips work on every
                            variant because they all extend <code class="font-mono text-xs">button.base</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <div class="flex flex-wrap items-start gap-3">
                                <x-v-button.primary href="https://github.com/ben-dennenboom/verdant-ui" icon="arrow-up-right-from-square" newWindow>Open repository</x-v-button.primary>
                                <x-v-button.primary disabled>Unavailable</x-v-button.primary>
                                <x-v-button.secondary iconRight="chevron-right">Next step</x-v-button.secondary>
                                <x-v-button.accent tooltip="Exports the current selection" tooltipPosition="bottom">Export</x-v-button.accent>
                            </div>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-button.group align="right">
    <x-v-button.secondary>Back</x-v-button.secondary>
    <x-v-button.light>Save draft</x-v-button.light>
    <x-v-button.primary>Publish</x-v-button.primary>
</x-v-button.group>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-button.group&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Wraps buttons in a wrapping flex row.
                            <code class="font-mono text-xs">align</code> accepts <code class="font-mono text-xs">left</code>
                            (default), <code class="font-mono text-xs">center</code> or <code class="font-mono text-xs">right</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.group align="right">
                                <x-v-button.secondary>Back</x-v-button.secondary>
                                <x-v-button.light>Save draft</x-v-button.light>
                                <x-v-button.primary>Publish</x-v-button.primary>
                            </x-v-button.group>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-button.toolbar tooltip="Manage columns" tooltip-position="top">
    <i class="fas fa-columns v-text-2xl v-text-gray-600"></i>
</x-v-button.toolbar>
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-button.toolbar&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Large square icon button used by the grid toolbar. Takes only
                            <code class="font-mono text-xs">tooltip</code> and <code class="font-mono text-xs">tooltipPosition</code>;
                            the icon goes in the slot.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.toolbar tooltip="Manage columns" tooltip-position="top">
                                <i class="fas fa-columns v-text-2xl v-text-gray-600"></i>
                            </x-v-button.toolbar>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>
            </section>

            {{-- ============================================================ --}}
            {{-- FORM FIELDS                                                  --}}
            {{-- ============================================================ --}}
            <section id="forms" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Form fields</h2>
                    <p class="text-gray-600">Every field reads back <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">old()</code>
                        input and renders its own <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">@@error</code> message,
                        so they drop straight into a validated Laravel form.</p>
                </div>

                @php $code = <<<'BLADE'
<x-v-form.input
    name="company_name"
    label="Company name"
    placeholder="Dennenboom BV"
    required/>

{{-- Any input type works, and `hidden` renders a bare hidden input --}}
<x-v-form.input name="starts_at" label="Starts at" type="date" value="2026-01-01"/>
<x-v-form.input name="tenant_id" value="42" hidden/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.input&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Labelled text input. Props:
                            <code class="font-mono text-xs">name</code>, <code class="font-mono text-xs">label</code>,
                            <code class="font-mono text-xs">type</code>, <code class="font-mono text-xs">value</code>,
                            <code class="font-mono text-xs">required</code>, <code class="font-mono text-xs">hidden</code>,
                            <code class="font-mono text-xs">id</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.input name="company_name" label="Company name" placeholder="Dennenboom BV" required/>
                            <x-v-form.input name="starts_at" label="Starts at" type="date" value="2026-01-01"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.textarea
    name="description"
    label="Description"
    placeholder="Tell us about the project..."
    required/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.textarea&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Multi-line text field. Props:
                            <code class="font-mono text-xs">name</code>, <code class="font-mono text-xs">label</code>,
                            <code class="font-mono text-xs">value</code>, <code class="font-mono text-xs">required</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.textarea name="description" label="Description" placeholder="Tell us about the project..." required/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.select
    name="status"
    label="Status"
    selected="published"
    required
    :options="[
        ['value' => 'draft',     'label' => 'Draft'],
        ['value' => 'published', 'label' => 'Published'],
        ['value' => 'archived',  'label' => 'Archived'],
    ]"/>

{{-- Multi-select: use name="tags[]" and an array of selected values --}}
<x-v-form.select
    name="tags[]"
    label="Tags"
    multiple
    :selected="['laravel', 'alpine']"
    :options="[
        ['value' => 'laravel',  'label' => 'Laravel'],
        ['value' => 'alpine',   'label' => 'Alpine.js'],
        ['value' => 'tailwind', 'label' => 'Tailwind CSS'],
    ]"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.select&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Searchable Alpine dropdown with single and multiple selection.
                            <code class="font-mono text-xs">valueKey</code> / <code class="font-mono text-xs">labelKey</code>
                            let you feed it models instead of arrays, and <code class="font-mono text-xs">labelKey</code> also
                            accepts a closure.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.select
                                    name="status"
                                    label="Status"
                                    selected="published"
                                    required
                                    :options="[
                                    ['value' => 'draft',     'label' => 'Draft'],
                                    ['value' => 'published', 'label' => 'Published'],
                                    ['value' => 'archived',  'label' => 'Archived'],
                                ]"/>

                            <x-v-form.select
                                    name="tags[]"
                                    label="Tags"
                                    multiple
                                    :selected="['laravel', 'alpine']"
                                    :options="[
                                    ['value' => 'laravel',  'label' => 'Laravel'],
                                    ['value' => 'alpine',   'label' => 'Alpine.js'],
                                    ['value' => 'tailwind', 'label' => 'Tailwind CSS'],
                                ]"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.checkbox
    name="accepts_terms"
    label="I accept the terms and conditions"
    value="1"
    checked/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.checkbox&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Single checkbox with an inline label. Props:
                            <code class="font-mono text-xs">name</code>, <code class="font-mono text-xs">label</code>,
                            <code class="font-mono text-xs">value</code>, <code class="font-mono text-xs">checked</code>,
                            <code class="font-mono text-xs">required</code>, <code class="font-mono text-xs">id</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.checkbox name="accepts_terms" label="I accept the terms and conditions" value="1" checked/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.toggle
    name="is_active"
    label="Active"
    value="1"
    checked/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.toggle&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Switch-style boolean. It always submits a hidden input, so an
                            "off" toggle posts <code class="font-mono text-xs">0</code> instead of nothing. Bind it to an outer
                            Alpine scope with <code class="font-mono text-xs">x-model</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.toggle name="is_active" label="Active" value="1" checked/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.file-input
    name="attachment"
    label="Attachment"
    accept="application/pdf,image/*"
    required/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.file-input&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">File picker. <code class="font-mono text-xs">accept</code> is passed
                            straight through to the native input and defaults to <code class="font-mono text-xs">*</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.file-input name="attachment" label="Attachment" accept="application/pdf,image/*" required/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.actions>
    <x-v-button.secondary href="/back">Cancel</x-v-button.secondary>
    <x-v-button.primary type="submit">Save changes</x-v-button.primary>
</x-v-form.actions>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.actions&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Footer row for a form: a top border and an evenly spaced flex row.
                            Slot-only, no props.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.actions>
                                <x-v-button.secondary href="/back">Cancel</x-v-button.secondary>
                                <x-v-button.primary type="submit">Save changes</x-v-button.primary>
                            </x-v-form.actions>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-filters.search
    name="search"
    label="Search users"
    placeholder="Name or e-mail..."
    :filter="request()->get('filter', [])"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-filters.search&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Plain search field that submits as
                            <code class="font-mono text-xs">filter[name]</code>. Pass the current
                            <code class="font-mono text-xs">filter</code> array so the value survives a reload. Designed to sit
                            inside <code class="font-mono text-xs">&lt;x-v-table.filter&gt;</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-filters.search name="search" label="Search users" placeholder="Name or e-mail..." :filter="request()->get('filter', [])"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.vat-number-checker
    name="vat_number"
    label="VAT number"
    nameId="company_name"
    addressId="company_address"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.vat-number-checker&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Text input that validates the VAT number against the package's own
                            <code class="font-mono text-xs">verdant.vat-number-check</code> endpoint on blur and shows a status
                            icon. <code class="font-mono text-xs">nameId</code> and <code class="font-mono text-xs">addressId</code>
                            are element ids that get auto-filled from the lookup result.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.vat-number-checker name="vat_number" class="h-8 px-2" label="VAT number" nameId="company_name" addressId="company_address"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.richtext
    name="body"
    label="Body"
    :value="$page->body ?? null"
    required/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.richtext&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">HTML editor with a bold/italic/underline/alignment toolbar. It also
                            exposes an <code class="font-mono text-xs">actions</code> slot next to the label.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.richtext name="body" label="Body" required/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.image-cropper
    name="cover_image"
    label="Cover image"
    :src="$page->cover_url ?? null"
    aspectRatio="16/9"
    :minWidth="1200"
    :minHeight="675"
    :uploadUrl="route('media.store')"/>
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.image-cropper&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Upload plus Cropper.js crop step, with dimension guards
                            (<code class="font-mono text-xs">minWidth</code>, <code class="font-mono text-xs">minHeight</code>,
                            <code class="font-mono text-xs">maxWidth</code>, <code class="font-mono text-xs">maxHeight</code>,
                            <code class="font-mono text-xs">maxScale</code>). Set
                            <code class="font-mono text-xs">disableCrop</code> for a plain upload.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.image-cropper name="cover_image" label="Cover image" aspectRatio="16/9" :minWidth="1200" :minHeight="675"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>
            </section>

            {{-- ============================================================ --}}
            {{-- TRANSLATION FIELDS                                           --}}
            {{-- ============================================================ --}}
            <section id="translations" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Translation fields</h2>
                    <p class="text-gray-600">Four field types that render language tabs above a single logical field. Each takes a
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">languages</code> array of
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">['name' =&gt; ..., 'label' =&gt; ..., 'value' =&gt; ...]</code>.
                        Tabs that already hold a value are highlighted; empty ones are greyed out.</p>
                </div>

                @php
                    $demoLanguages = [
                        ['name' => 'title_en', 'label' => '🇬🇧 English', 'value' => 'Annual report 2026'],
                        ['name' => 'title_nl', 'label' => '🇳🇱 Dutch',   'value' => null],
                        ['name' => 'title_fr', 'label' => '🇫🇷 French',  'value' => 'Rapport annuel 2026'],
                    ];
                @endphp

                @php $code = <<<'BLADE'
<x-v-form.translation-input
    label="Report title"
    :languages="[
        ['name' => 'title_en', 'label' => '🇬🇧 English', 'value' => $report->title_en],
        ['name' => 'title_nl', 'label' => '🇳🇱 Dutch',   'value' => $report->title_nl],
        ['name' => 'title_fr', 'label' => '🇫🇷 French',  'value' => $report->title_fr],
    ]"
    required/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.translation-input&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Text input per language. <code class="font-mono text-xs">type</code>
                            is forwarded to every underlying input, so <code class="font-mono text-xs">type="url"</code> works too.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.translation-input label="Report title" :languages="$demoLanguages" required/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.translation-textarea
    label="Report summary"
    :languages="[
        ['name' => 'summary_en', 'label' => '🇬🇧 English', 'value' => $report->summary_en],
        ['name' => 'summary_nl', 'label' => '🇳🇱 Dutch',   'value' => $report->summary_nl],
    ]"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.translation-textarea&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Same idea with a textarea per language.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.translation-textarea label="Report summary" :languages="[
                                ['name' => 'summary_en', 'label' => '🇬🇧 English', 'value' => 'A strong year.'],
                                ['name' => 'summary_nl', 'label' => '🇳🇱 Dutch',   'value' => null],
                            ]"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.translation-tabs :languages="$languages"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.translation-tabs&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">The tab strip on its own. The other translation components render it
                            for you; use it directly only inside an Alpine scope that already defines
                            <code class="font-mono text-xs">activeTab</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6" x-data="{ activeTab: 0 }">
                            <x-v-form.translation-tabs :languages="$demoLanguages"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-5 py-4">
                    <p class="text-sm font-semibold text-amber-900">Same asset requirement as richtext and image cropper</p>
                    <p class="mt-1 text-sm text-amber-800">The two components below wrap
                        <code class="font-mono text-xs">form.richtext</code> and <code class="font-mono text-xs">form.image-cropper</code>,
                        so they need the same published source assets to become interactive.</p>
                </div>

                @php $code = <<<'BLADE'
<x-v-form.translation-richtext
    label="Introduction"
    :languages="[
        ['name' => 'intro_en', 'label' => '🇬🇧 English', 'value' => $report->intro_en],
        ['name' => 'intro_nl', 'label' => '🇳🇱 Dutch',   'value' => $report->intro_nl],
    ]"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.translation-richtext&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">A full HTML editor per language.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.translation-richtext label="Introduction" :languages="[
                                ['name' => 'intro_en', 'label' => '🇬🇧 English', 'value' => null],
                                ['name' => 'intro_nl', 'label' => '🇳🇱 Dutch',   'value' => null],
                            ]"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-form.translation-image
    label="Hero image"
    aspectRatio="16/9"
    :uploadUrl="route('media.store')"
    :languages="[
        ['name' => 'hero_en', 'label' => '🇬🇧 English', 'value' => $report->hero_en_url],
        ['name' => 'hero_nl', 'label' => '🇳🇱 Dutch',   'value' => $report->hero_nl_url],
    ]"/>
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-form.translation-image&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">One croppable image per language. Accepts the same dimension props as
                            <code class="font-mono text-xs">form.image-cropper</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-form.translation-image label="Hero image" aspectRatio="16/9" :languages="[
                                ['name' => 'hero_en', 'label' => '🇬🇧 English', 'value' => null],
                                ['name' => 'hero_nl', 'label' => '🇳🇱 Dutch',   'value' => null],
                            ]"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>
            </section>

            {{-- ============================================================ --}}
            {{-- TABLES                                                       --}}
            {{-- ============================================================ --}}
            <section id="tables" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tables</h2>
                    <p class="text-gray-600">The classic <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">&lt;table&gt;</code>
                        building blocks. Compose them yourself when you control the query and markup; reach for
                        <a href="#dynamic-tables" class="text-blue-600 hover:text-blue-800">dynamic tables</a> when you want
                        sorting, filtering and column visibility handed to you.</p>
                </div>

                @php $code = <<<'BLADE'
<x-v-table.list title="Team members">
    <x-slot name="actions">
        <x-v-button.primary icon="plus">Add member</x-v-button.primary>
    </x-slot>

    <x-slot name="header">
        <x-v-table.row :hover="false">
            <x-v-table.header title="Name"/>
            <x-v-table.header title="E-mail"/>
            <x-v-table.header title="Role"/>
        </x-v-table.row>
    </x-slot>

    @foreach ($users as $user)
        <x-v-table.row>
            <x-v-table.cell nowrap>{{ $user->name }}</x-v-table.cell>
            <x-v-table.cell>{{ $user->email }}</x-v-table.cell>
            <x-v-table.cell>{{ $user->role }}</x-v-table.cell>
        </x-v-table.row>
    @endforeach
</x-v-table.list>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-table.list&gt; · &lt;x-v-table.row&gt; · &lt;x-v-table.header&gt; · &lt;x-v-table.cell&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">
                            <code class="font-mono text-xs">table.list</code> is the shell (it shows a scroll hint when the table
                            overflows) and takes <code class="font-mono text-xs">title</code> plus optional
                            <code class="font-mono text-xs">actions</code> and <code class="font-mono text-xs">header</code> slots.
                            <code class="font-mono text-xs">table.row</code> takes <code class="font-mono text-xs">hover</code>,
                            <code class="font-mono text-xs">table.cell</code> takes <code class="font-mono text-xs">colspan</code>
                            and <code class="font-mono text-xs">nowrap</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-table.list title="Team members">
                                <x-slot name="actions">
                                    <x-v-button.primary icon="plus">Add member</x-v-button.primary>
                                </x-slot>

                                <x-slot name="header">
                                    <x-v-table.row :hover="false">
                                        <x-v-table.header title="Name"/>
                                        <x-v-table.header title="E-mail"/>
                                        <x-v-table.header title="Role"/>
                                    </x-v-table.row>
                                </x-slot>

                                <x-v-table.row>
                                    <x-v-table.cell nowrap>John Doe</x-v-table.cell>
                                    <x-v-table.cell>john@example.com</x-v-table.cell>
                                    <x-v-table.cell>Admin</x-v-table.cell>
                                </x-v-table.row>
                                <x-v-table.row>
                                    <x-v-table.cell nowrap>Jane Smith</x-v-table.cell>
                                    <x-v-table.cell>jane@example.com</x-v-table.cell>
                                    <x-v-table.cell>User</x-v-table.cell>
                                </x-v-table.row>
                                <x-v-table.row>
                                    <x-v-table.cell nowrap>Bob Johnson</x-v-table.cell>
                                    <x-v-table.cell>bob@example.com</x-v-table.cell>
                                    <x-v-table.cell>Editor</x-v-table.cell>
                                </x-v-table.row>
                            </x-v-table.list>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
{{-- Sortable header: links to the current route with filter[order] / filter[order_direction] --}}
<x-v-table.header title="Name" field="name" sortable/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-table.header sortable&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">With <code class="font-mono text-xs">sortable</code> and a
                            <code class="font-mono text-xs">field</code>, the header becomes a link that flips
                            <code class="font-mono text-xs">filter[order_direction]</code> between
                            <code class="font-mono text-xs">asc</code> and <code class="font-mono text-xs">desc</code> on the
                            current route. Read the state back from <code class="font-mono text-xs">request()->get('filter')</code>
                            in your controller.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <table class="w-full">
                                <thead class="v-bg-surface-muted">
                                <x-v-table.row :hover="false">
                                    <x-v-table.header title="Name" field="name" sortable/>
                                    <x-v-table.header title="Created" field="created_at" sortable/>
                                </x-v-table.row>
                                </thead>
                            </table>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-table.filter
    :route="route('users.index')"
    title="Filter users"
    id="users-filter"
    :filter="request()->get('filter')">

    <x-v-form.input name="filter[name]" label="Name" placeholder="Filter by name..."/>
    <x-v-form.select
        name="filter[status]"
        label="Status"
        :selected="request()->input('filter.status')"
        :options="[
            ['value' => 'active',   'label' => 'Active'],
            ['value' => 'inactive', 'label' => 'Inactive'],
        ]"/>
</x-v-table.filter>
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-table.filter&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">A "Filter" button that opens a modal with your fields in it and
                            submits as a GET form to <code class="font-mono text-xs">route</code>. Passing the current
                            <code class="font-mono text-xs">filter</code> array adds an active-filter count badge and a clear
                            button.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-table.filter route="/" title="Filter users" id="users-filter">
                                <x-v-form.input name="filter[name]" label="Name" placeholder="Filter by name..."/>
                                <x-v-form.select name="filter[status]" label="Status" :options="[
                                    ['value' => 'active',   'label' => 'Active'],
                                    ['value' => 'inactive', 'label' => 'Inactive'],
                                ]"/>
                            </x-v-table.filter>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>
            </section>

            {{-- ============================================================ --}}
            {{-- DYNAMIC TABLES                                               --}}
            {{-- ============================================================ --}}
            <section id="dynamic-tables" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Dynamic tables</h2>
                    <p class="text-gray-600">A single container component driven by a
                        <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">DynamicTableData</code> object built in
                        PHP. It renders its own search bar, filter modal, column picker, bulk bar, table/card bodies and
                        pagination, so those sub-components are never placed by hand.</p>
                </div>

                @php
                    $dtActions = [
                        ['label' => 'Edit',   'route' => '#', 'icon' => 'pen'],
                        ['label' => 'Delete', 'route' => '#', 'icon' => 'trash'],
                    ];

                    $dtRows = [
                        ['name' => 'John Doe',    'email' => 'john@example.com', 'role' => 'Admin',  'status' => 'Active',   'actions' => $dtActions],
                        ['name' => 'Jane Smith',  'email' => 'jane@example.com', 'role' => 'User',   'status' => 'Active',   'actions' => $dtActions],
                        ['name' => 'Bob Johnson', 'email' => 'bob@example.com',  'role' => 'Editor', 'status' => 'Inactive', 'actions' => $dtActions],
                    ];

                    $dtBasic = \Dennenboom\VerdantUI\Tables\DynamicTableData::from(
                        headers: [
                            ['key' => 'name',   'label' => 'Name',   'sortable' => true],
                            ['key' => 'email',  'label' => 'E-mail', 'sortable' => true],
                            ['key' => 'role',   'label' => 'Role'],
                            ['key' => 'status', 'label' => 'Status'],
                        ],
                        rows: $dtRows
                    );

                    $dtFull = \Dennenboom\VerdantUI\Tables\DynamicTableData::from(
                        headers: [
                            ['key' => 'name',   'label' => 'Name',   'sortable' => true],
                            ['key' => 'email',  'label' => 'E-mail', 'sortable' => true],
                            ['key' => 'role',   'label' => 'Role'],
                            ['key' => 'status', 'label' => 'Status'],
                        ],
                        rows: $dtRows
                    )
                        ->withSearchableColumns(['name', 'email'])
                        ->withFilters([
                            \Dennenboom\VerdantUI\Tables\Filter::make('role', 'Role', 'select')
                                ->options(['Admin' => 'Admin', 'User' => 'User', 'Editor' => 'Editor']),
                            \Dennenboom\VerdantUI\Tables\Filter::make('status', 'Status', 'select')
                                ->options(['Active' => 'Active', 'Inactive' => 'Inactive']),
                        ])
                        ->withColumnVisibility('verdant-demo-full');
                @endphp

                @php $code = <<<'BLADE'
use Dennenboom\VerdantUI\Tables\DynamicTableData;

$table = DynamicTableData::from(
    headers: [
        ['key' => 'name',   'label' => 'Name',   'sortable' => true],
        ['key' => 'email',  'label' => 'E-mail', 'sortable' => true],
        ['key' => 'role',   'label' => 'Role'],
        ['key' => 'status', 'label' => 'Status'],
    ],
    rows: [
        ['name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Admin', 'status' => 'Active',
         'actions' => [
             ['label' => 'Edit',   'route' => route('users.edit', $user), 'icon' => 'pen'],
             ['label' => 'Delete', 'route' => route('users.destroy', $user), 'icon' => 'trash',
              'form' => true, 'method' => 'DELETE'],
         ]],
    ]
);
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-dynamic-table.container&gt; — plain</h4>
                        <p class="mt-1 text-sm text-gray-600">Build the data object in PHP, then render
                            <code class="font-mono text-xs">&lt;x-v-dynamic-table.container :data="$table"/&gt;</code>. Each row may
                            carry an <code class="font-mono text-xs">actions</code> array; entries with
                            <code class="font-mono text-xs">'form' =&gt; true</code> post instead of linking.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-dynamic-table.container :data="$dtBasic"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
use Dennenboom\VerdantUI\Tables\DynamicTableData;
use Dennenboom\VerdantUI\Tables\Filter;

$table = DynamicTableData::from(headers: $headers, rows: $rows)
    ->withSearchableColumns(['name', 'email'])
    ->withFilters([
        Filter::make('role', 'Role', 'select')
            ->options(['Admin' => 'Admin', 'User' => 'User', 'Editor' => 'Editor']),
        Filter::make('status', 'Status', 'select')
            ->options(['Active' => 'Active', 'Inactive' => 'Inactive']),
    ])
    ->withColumnVisibility('users-table');
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-dynamic-table.container&gt; — search, filters, column picker</h4>
                        <p class="mt-1 text-sm text-gray-600">The toolbar appears as soon as one of
                            <code class="font-mono text-xs">withSearchableColumns()</code>,
                            <code class="font-mono text-xs">withFilters()</code> or
                            <code class="font-mono text-xs">withColumnVisibility()</code> is set. The visibility key is the
                            localStorage bucket the chosen columns are remembered in.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-dynamic-table.container :data="$dtFull"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
use Dennenboom\VerdantUI\Tables\BulkField;
use Dennenboom\VerdantUI\Tables\Column;
use Dennenboom\VerdantUI\Tables\DynamicTableData;

$columns = [
    Column::make('last_name', 'Last name')->default()->sortable(),
    Column::make('full_name', 'Full name')
        ->value(fn (User $user) => $user->first_name . ' ' . $user->last_name)
        ->sortable('last_name'),
    Column::make('is_active', 'Active')
        ->format(fn (bool $value) => $value ? 'Yes' : 'No'),
    Column::make('roles', 'Roles')->pinned(),
];

$table = DynamicTableData::fromCollection($users, $columns, $actions)
    ->withColumnVisibility('users-table', null)   // defaults come from ->default()
    ->withSearchApiUrl(route('users.search'))     // type-ahead results in the search bar
    ->withRowOpenUrl(fn (User $user) => route('users.show', $user))
    ->withRowStyle(fn (User $user) => $user->trashed() ? RowStyle::Muted : null)
    ->withActionsMaxVisible(2)
    ->withBulkEdit([
        BulkField::make('role', 'Role', 'select')->options(['admin' => 'Admin', 'user' => 'User']),
    ], route('users.bulk-update'));
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">DynamicTableData — the full builder</h4>
                        <p class="mt-1 text-sm text-gray-600">Reference for the remaining builder methods. See
                            <code class="font-mono text-xs">docs/DynamicTable.md</code> in the package for the complete
                            <code class="font-mono text-xs">Column</code> API, server-side sorting with
                            <code class="font-mono text-xs">Column::applySort()</code> and export helpers.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <dl class="space-y-3 text-sm">
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">::from(headers:, rows:)</dt><dd class="text-gray-600">Build from plain arrays.</dd></div>
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">::fromCollection($items, $columns, $actions, $context)</dt><dd class="text-gray-600">Build from models with <code class="font-mono text-xs">Column</code> objects.</dd></div>
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">->withSearchableColumns([...])</dt><dd class="text-gray-600">Shows the search bar.</dd></div>
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">->withSearchApiUrl($url)</dt><dd class="text-gray-600">Adds a type-ahead result list.</dd></div>
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">->withFilters([Filter::make(...)])</dt><dd class="text-gray-600">Shows the filter modal.</dd></div>
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">->withColumnVisibility($key, $default)</dt><dd class="text-gray-600">Shows the column picker, remembered per key.</dd></div>
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">->withSorting(DynamicTableSort::fromRequest($keys))</dt><dd class="text-gray-600">Marks the active sort column.</dd></div>
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">->withBulkEdit($fields, $url)</dt><dd class="text-gray-600">Adds row checkboxes and the bulk bar.</dd></div>
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">->withRowOpenUrl($callback)</dt><dd class="text-gray-600">Makes whole rows clickable.</dd></div>
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">->withRowStyle($callback)</dt><dd class="text-gray-600">Per-row <code class="font-mono text-xs">RowStyle</code>.</dd></div>
                                <div><dt class="font-mono text-xs font-semibold text-gray-900">->withActionsMaxVisible($n)</dt><dd class="text-gray-600">How many row actions before the overflow menu.</dd></div>
                            </dl>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-dynamic-table.management.search-bar
    :search-term="request('search', '')"
    param-name="search"
    placeholder="Search users…"
    :search-api-url="route('users.search')"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-dynamic-table.management.search-bar&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Rendered automatically by the container, but usable on its own above
                            any listing. It keeps the rest of the query string as hidden inputs, so search composes with your
                            existing filters. With <code class="font-mono text-xs">searchApiUrl</code> it also shows a type-ahead
                            dropdown of <code class="font-mono text-xs">{ label, url }</code> results.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-dynamic-table.management.search-bar placeholder="Search users…"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-dynamic-table.management.actions
    :max-visible="2"
    :actions="[
        ['label' => 'Edit',      'route' => route('users.edit', $user), 'icon' => 'pen'],
        ['label' => 'Duplicate', 'route' => route('users.copy', $user), 'icon' => 'copy'],
        ['label' => 'Delete',    'route' => route('users.destroy', $user), 'icon' => 'trash',
         'form' => true, 'method' => 'DELETE'],
    ]"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-dynamic-table.management.actions&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Row action bar. The first
                            <code class="font-mono text-xs">maxVisible</code> actions are shown inline, the rest move into an
                            overflow menu. <code class="font-mono text-xs">route</code> is a URL, not a route name. Use
                            <code class="font-mono text-xs">'form' =&gt; true</code> with a
                            <code class="font-mono text-xs">method</code> for non-GET actions, and
                            <code class="font-mono text-xs">'disabled' =&gt; true</code> to grey one out.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-dynamic-table.management.actions :max-visible="2" :actions="[
                                ['label' => 'Edit',      'route' => '#', 'icon' => 'pen'],
                                ['label' => 'Duplicate', 'route' => '#', 'icon' => 'copy'],
                                ['label' => 'Delete',    'route' => '#', 'icon' => 'trash'],
                            ]"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                <div class="rounded-lg border border-gray-200 bg-gray-50 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900">Internal sub-components</p>
                    <p class="mt-1 text-sm text-gray-600">
                        <code class="font-mono text-xs">dynamic-table.header</code>,
                        <code class="font-mono text-xs">dynamic-table.body-table</code>,
                        <code class="font-mono text-xs">dynamic-table.body-cards</code>,
                        <code class="font-mono text-xs">dynamic-table.pagination</code>,
                        <code class="font-mono text-xs">dynamic-table.bulk-bar</code>,
                        <code class="font-mono text-xs">dynamic-table.management.column-picker</code> and
                        <code class="font-mono text-xs">dynamic-table.management.filter-modal</code>
                        are rendered by <code class="font-mono text-xs">dynamic-table.container</code> and expect a prepared
                        <code class="font-mono text-xs">$vm</code> view model, so they are not meant to be placed by hand.</p>
                </div>
            </section>

            {{-- ============================================================ --}}
            {{-- GRIDS                                                        --}}
            {{-- ============================================================ --}}
            <section id="grids" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Grids</h2>
                    <p class="text-gray-600">A CSS-grid alternative to <code class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">table.*</code>
                        that can flip between a table layout and a tile layout, hide columns and scroll horizontally.</p>
                </div>

                @php $code = <<<'BLADE'
{{-- The wrapper supplies the Alpine scope the grid components rely on --}}
<div x-data="gridComponent({ title: 'users', totalColumns: 4 })">

    <x-v-grid-toolbar.toolbar grid-id="users" :columns="[
        ['id' => 'name',   'label' => 'Name',   'hideable' => false],
        ['id' => 'email',  'label' => 'E-mail', 'hideable' => true],
        ['id' => 'role',   'label' => 'Role',   'hideable' => true],
        ['id' => 'status', 'label' => 'Status', 'hideable' => true],
    ]"/>

    <x-v-grid.gridContainer id="users" :columns="4">
        <x-slot name="header">
            <x-v-grid.header title="Name"   field="name" sortable/>
            <x-v-grid.header title="E-mail" id="email"  hideable/>
            <x-v-grid.header title="Role"   id="role"   hideable/>
            <x-v-grid.header title="Status" id="status" hideable/>
        </x-slot>

        @foreach ($users as $user)
            <x-v-grid.row>
                <x-v-grid.cell label="Name" nowrap>{{ $user->name }}</x-v-grid.cell>
                <x-v-grid.cell label="E-mail" id="email"  hideable>{{ $user->email }}</x-v-grid.cell>
                <x-v-grid.cell label="Role"   id="role"   hideable>{{ $user->role }}</x-v-grid.cell>
                <x-v-grid.cell label="Status" id="status" hideable>{{ $user->status }}</x-v-grid.cell>
            </x-v-grid.row>
        @endforeach

        <x-slot name="pagination">
            <x-v-pagination :paginator="$users" type="extended"/>
        </x-slot>
    </x-v-grid.gridContainer>
</div>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-grid.gridContainer&gt; · &lt;x-v-grid.header&gt; · &lt;x-v-grid.row&gt; · &lt;x-v-grid.cell&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Note the camelCase component name:
                            <code class="font-mono text-xs">gridContainer</code>, not <code class="font-mono text-xs">grid-container</code>.
                            Cells and headers pair up through a shared <code class="font-mono text-xs">id</code>; the
                            <code class="font-mono text-xs">label</code> on a cell is what shows as its caption in tile view.
                            <code class="font-mono text-xs">span</code> (1–8 or <code class="font-mono text-xs">full</code>) widens a
                            column, and <code class="font-mono text-xs">hideable</code> opts it into the column manager.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <div x-data="{}" class="text-sm">
                                <x-v-grid.gridContainer id="users-demo" :columns="3">
                                    <x-slot name="header">
                                        <x-v-grid.header title="Name" id="name"/>
                                        <x-v-grid.header title="E-mail" id="email"/>
                                        <x-v-grid.header title="Role" id="role"/>
                                    </x-slot>

                                    <x-v-grid.row>
                                        <x-v-grid.cell label="Name" id="name" nowrap>John Doe</x-v-grid.cell>
                                        <x-v-grid.cell label="E-mail" id="email">john@example.com</x-v-grid.cell>
                                        <x-v-grid.cell label="Role" id="role">Admin</x-v-grid.cell>
                                    </x-v-grid.row>
                                    <x-v-grid.row>
                                        <x-v-grid.cell label="Name" id="name" nowrap>Jane Smith</x-v-grid.cell>
                                        <x-v-grid.cell label="E-mail" id="email">jane@example.com</x-v-grid.cell>
                                        <x-v-grid.cell label="Role" id="role">User</x-v-grid.cell>
                                    </x-v-grid.row>
                                </x-v-grid.gridContainer>
                            </div>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-grid.filter
    :route="route('users.index')"
    title="Filter users"
    id="users-grid-filter"
    :filter="request()->get('filter')">

    <x-v-form.input name="filter[name]" label="Name"/>
</x-v-grid.filter>
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-grid.filter&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">The grid counterpart of <code class="font-mono text-xs">table.filter</code>:
                            same filter modal, but styled to sit inside the grid toolbar's slot.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-grid.filter route="/" title="Filter users" id="users-grid-filter">
                                <x-v-form.input name="filter[name]" label="Name"/>
                            </x-v-grid.filter>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>
            </section>

            {{-- ============================================================ --}}
            {{-- GRID TOOLBAR                                                 --}}
            {{-- ============================================================ --}}
            <section id="grid-toolbars" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Grid toolbar</h2>
                    <p class="text-gray-600">The floating control bar that sits above a grid. Like the grid itself it needs the
                        published source assets described above.</p>
                </div>

                @php $code = <<<'BLADE'
<x-v-grid-toolbar.toolbar grid-id="users" :columns="[
    ['id' => 'name',   'label' => 'Name',   'hideable' => false],
    ['id' => 'email',  'label' => 'E-mail', 'hideable' => true],
    ['id' => 'status', 'label' => 'Status', 'hideable' => true],
]">
    {{-- Anything in the slot gets its own divided section on the right --}}
    <x-v-grid.filter :route="route('users.index')" id="users-grid-filter">
        <x-v-form.input name="filter[name]" label="Name"/>
    </x-v-grid.filter>
</x-v-grid-toolbar.toolbar>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-grid-toolbar.toolbar&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Bundles the view toggle, the column manager and the scroll buttons.
                            <code class="font-mono text-xs">gridId</code> must match the id you gave
                            <code class="font-mono text-xs">gridComponent()</code> so both read the same store entry. The column
                            manager only appears when <code class="font-mono text-xs">columns</code> is non-empty.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-grid-toolbar.toolbar grid-id="users-demo" :columns="[
                                ['id' => 'name',   'label' => 'Name',   'hideable' => false],
                                ['id' => 'email',  'label' => 'E-mail', 'hideable' => true],
                                ['id' => 'role',   'label' => 'Role',   'hideable' => true],
                            ]"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-grid-toolbar.column-manager :columns="[
    ['id' => 'email',  'label' => 'E-mail', 'hideable' => true],
    ['id' => 'role',   'label' => 'Role',   'hideable' => true],
    ['id' => 'status', 'label' => 'Status', 'hideable' => true],
]"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-grid-toolbar.column-manager&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Dropdown of checkboxes, one per column with
                            <code class="font-mono text-xs">'hideable' =&gt; true</code>, plus a Reset link. Choices are persisted in
                            localStorage under the grid's id.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-grid-toolbar.column-manager :columns="[
                                ['id' => 'email',  'label' => 'E-mail', 'hideable' => true],
                                ['id' => 'role',   'label' => 'Role',   'hideable' => true],
                                ['id' => 'status', 'label' => 'Status', 'hideable' => true],
                            ]"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-grid-toolbar.view-toggle/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-grid-toolbar.view-toggle&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Swaps the grid between table and tile view by calling
                            <code class="font-mono text-xs">toggleView()</code> on the surrounding grid scope. No props — the icon
                            follows the current <code class="font-mono text-xs">tileView</code> state.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-grid-toolbar.view-toggle/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-grid-toolbar.scroll-to direction="start" tooltip="Scroll to start"/>
<x-v-grid-toolbar.scroll-to direction="end"   tooltip="Scroll to end"/>
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-grid-toolbar.scroll-to&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Jumps a horizontally scrolling grid to either end.
                            <code class="font-mono text-xs">direction</code> is <code class="font-mono text-xs">start</code> or
                            <code class="font-mono text-xs">end</code>; the toolbar hides each button once that end is reached.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <div class="flex gap-2">
                                <x-v-grid-toolbar.scroll-to direction="start" tooltip="Scroll to start"/>
                                <x-v-grid-toolbar.scroll-to direction="end" tooltip="Scroll to end"/>
                            </div>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>
            </section>

            {{-- ============================================================ --}}
            {{-- NAVIGATION                                                   --}}
            {{-- ============================================================ --}}
            <section id="navigation" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Navigation</h2>
                    <p class="text-gray-600">Page headers, breadcrumbs, sidebar entries and pagination.</p>
                </div>

                @php $code = <<<'BLADE'
{{-- With a subtitle: renders as a section header --}}
<x-v-content-header title="Dashboard" subtitle="Everything that needs your attention">
    <x-slot name="actions">
        <x-v-button.primary icon="plus">New project</x-v-button.primary>
    </x-slot>
</x-v-content-header>

{{-- Without a subtitle: renders as a page title --}}
<x-v-content-header title="Users"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-content-header&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Page or section header with an optional
                            <code class="font-mono text-xs">actions</code> slot that scrolls horizontally on small screens.
                            Supplying <code class="font-mono text-xs">subtitle</code> switches it from an
                            <code class="font-mono text-xs">h1</code> page title to a smaller <code class="font-mono text-xs">h3</code>
                            section header.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6 space-y-4">
                            <x-v-content-header title="Dashboard" subtitle="Everything that needs your attention">
                                <x-slot name="actions">
                                    <x-v-button.primary icon="plus">New project</x-v-button.primary>
                                </x-slot>
                            </x-v-content-header>
                            <x-v-content-header title="Users"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<nav class="v-flex">
    <x-v-breadcrumb-item icon="home" :route="route('dashboard')" label="Home"/>
    <x-v-breadcrumb-item :route="route('users.index')" label="Users"/>
    <x-v-breadcrumb-item label="John Doe" last/>
</nav>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-breadcrumb-item&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">One crumb per component. <code class="font-mono text-xs">route</code>
                            takes a URL (omit it for the current page), <code class="font-mono text-xs">icon</code> prefixes a Font
                            Awesome icon, and <code class="font-mono text-xs">last</code> drops the trailing chevron. Wrap them in
                            your own <code class="font-mono text-xs">&lt;nav&gt;</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <nav class="v-flex">
                                <x-v-breadcrumb-item icon="home" route="/" label="Home"/>
                                <x-v-breadcrumb-item route="/" label="Users"/>
                                <x-v-breadcrumb-item label="John Doe" last/>
                            </nav>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<ul>
    <x-v-sidebar.item icon="gauge" route="dashboard" label="Dashboard"/>

    <x-v-sidebar.item-group icon="users" label="People">
        <x-v-sidebar.item icon="user" route="users.index" label="Users"/>
        <x-v-sidebar.item icon="user-shield" route="roles.index" label="Roles"/>
    </x-v-sidebar.item-group>

    {{-- Prefix with brands: for Font Awesome brand icons --}}
    <x-v-sidebar.item icon="brands:github" label="Repository" :active="false"/>
</ul>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-sidebar.item&gt; · &lt;x-v-sidebar.item-group&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Sidebar entries. <code class="font-mono text-xs">route</code> is a
                            <em>route name</em> here (with optional <code class="font-mono text-xs">routeParams</code>) and the item
                            highlights itself via <code class="font-mono text-xs">request()->routeIs()</code> unless you override
                            <code class="font-mono text-xs">active</code>. A group opens automatically when one of its children is
                            active. <code class="font-mono text-xs">collapsed</code> hides the label for a rail-style sidebar.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <ul class="w-full max-w-xs">
                                <x-v-sidebar.item icon="gauge" label="Dashboard" :active="true"/>
                                <x-v-sidebar.item-group icon="users" label="People">
                                    <x-v-sidebar.item icon="user" label="Users"/>
                                    <x-v-sidebar.item icon="user-shield" label="Roles"/>
                                </x-v-sidebar.item-group>
                                <x-v-sidebar.item icon="brands:github" label="Repository"/>
                            </ul>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php
                    $demoPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                        items: range(21, 30),
                        total: 87,
                        perPage: 10,
                        currentPage: 3,
                        options: ['path' => url()->current(), 'pageName' => 'demo_page']
                    );
                @endphp

                @php $code = <<<'BLADE'
{{-- Previous / next only --}}
<x-v-pagination :paginator="$users"/>

{{-- Adds the "showing x to y of z" line and numbered pages on large screens --}}
<x-v-pagination :paginator="$users" type="extended"/>
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-pagination&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Takes any Laravel paginator. Labels come from your
                            <code class="font-mono text-xs">pagination</code> language file.
                            <code class="font-mono text-xs">type="extended"</code> needs a
                            <code class="font-mono text-xs">LengthAwarePaginator</code>;
                            <code class="font-mono text-xs">type="default"</code> hides the buttons on large screens when you render
                            your own page list beside them.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6 space-y-6">
                            <x-v-pagination :paginator="$demoPaginator" type="extended"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>
            </section>

            {{-- ============================================================ --}}
            {{-- OVERLAYS                                                     --}}
            {{-- ============================================================ --}}
            <section id="overlays" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Overlays</h2>
                    <p class="text-gray-600">Modals are opened and closed by dispatching browser events, so any element on the page
                        can trigger one without knowing about it.</p>
                </div>

                @php $code = <<<'BLADE'
<x-v-button.primary x-data @click="$dispatch('open-modal', 'edit-user')">
    Edit user
</x-v-button.primary>

<x-v-modal id="edit-user" maxWidth="2xl">
    <h3 class="v-text-lg v-font-medium v-mb-4">Edit user</h3>

    <x-v-form.input name="name" label="Name" value="John Doe"/>

    <x-v-form.actions>
        <x-v-button.secondary @click="$dispatch('close-modal', 'edit-user')">Close</x-v-button.secondary>
        <x-v-button.primary type="submit">Save</x-v-button.primary>
    </x-v-form.actions>
</x-v-modal>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-modal&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Open with <code class="font-mono text-xs">$dispatch('open-modal', 'id')</code>,
                            close with <code class="font-mono text-xs">close-modal</code> or the escape key.
                            <code class="font-mono text-xs">maxWidth</code> accepts
                            <code class="font-mono text-xs">sm</code>, <code class="font-mono text-xs">md</code>,
                            <code class="font-mono text-xs">lg</code>, <code class="font-mono text-xs">xl</code> or
                            <code class="font-mono text-xs">2xl</code> (default).</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.primary x-data @click="$dispatch('open-modal', 'demo-edit-user')">Edit user</x-v-button.primary>

                            <x-v-modal id="demo-edit-user" maxWidth="2xl">
                                <h3 class="v-text-lg v-font-medium v-mb-4">Edit user</h3>
                                <x-v-form.input name="demo_modal_name" label="Name" value="John Doe"/>
                                <x-v-form.actions>
                                    <x-v-button.secondary @click="$dispatch('close-modal', 'demo-edit-user')">Close</x-v-button.secondary>
                                    <x-v-button.primary>Save</x-v-button.primary>
                                </x-v-form.actions>
                            </x-v-modal>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-button.danger x-data @click="$dispatch('open-modal', 'delete-user')">
    Delete user
</x-v-button.danger>

<x-v-modal.confirm
    id="delete-user"
    type="danger"
    title="Delete user"
    message="Are you sure you want to delete this user? This action cannot be undone."
    :action="route('users.destroy', $user)"
    method="DELETE"/>
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-modal.confirm&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">A ready-made confirmation dialog wrapping a CSRF-protected form.
                            <code class="font-mono text-xs">type</code> is
                            <code class="font-mono text-xs">danger</code>, <code class="font-mono text-xs">warning</code> or
                            <code class="font-mono text-xs">primary</code> and drives the icon and colour;
                            <code class="font-mono text-xs">method</code> is spoofed via
                            <code class="font-mono text-xs">@@method</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-button.danger x-data @click="$dispatch('open-modal', 'demo-delete-user')">Delete user</x-v-button.danger>

                            <x-v-modal.confirm
                                    id="demo-delete-user"
                                    type="danger"
                                    title="Delete user"
                                    message="Are you sure you want to delete this user? This action cannot be undone."
                                    action="#"
                                    method="DELETE"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>
            </section>

            {{-- ============================================================ --}}
            {{-- CONTENT                                                      --}}
            {{-- ============================================================ --}}
            <section id="content" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Content</h2>
                    <p class="text-gray-600">Read-only presentation of a record, collapsible panels and rendered rich text.</p>
                </div>

                @php $code = <<<'BLADE'
<x-v-details.page>
    <x-v-details.item label="Name"   value="John Doe"/>
    <x-v-details.item label="E-mail" value="john@example.com"/>
    <x-v-details.item label="Role"   value="Administrator"/>

    {{-- Omit value and use the slot for markup --}}
    <x-v-details.item label="Status">
        <span class="v-text-green-700 v-font-medium">Active</span>
    </x-v-details.item>
</x-v-details.page>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-details.page&gt; · &lt;x-v-details.item&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">A definition list with zebra striping.
                            <code class="font-mono text-xs">value</code> is rendered as raw HTML, so escape it yourself if it comes
                            from user input — or leave it off and use the slot.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-details.page>
                                <x-v-details.item label="Name" value="John Doe"/>
                                <x-v-details.item label="E-mail" value="john@example.com"/>
                                <x-v-details.item label="Role" value="Administrator"/>
                                <x-v-details.item label="Status">
                                    <span class="v-text-green-700 v-font-medium">Active</span>
                                </x-v-details.item>
                            </x-v-details.page>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-accordion :allow-multiple="false">
    <x-v-accordion.item title="What is Verdant UI?" :index="0">
        A self-contained Blade component library built on Tailwind CSS and Alpine.js.
    </x-v-accordion.item>

    <x-v-accordion.item title="Does it need a build step?" :index="1">
        No. The package ships a pre-built CSS and JS bundle that @verdantAssets pulls in.
    </x-v-accordion.item>

    <x-v-accordion.item title="Can I publish the views?" :index="2">
        Yes: php artisan vendor:publish --tag=verdant-views
    </x-v-accordion.item>
</x-v-accordion>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-accordion&gt; · &lt;x-v-accordion.item&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Each item needs a unique
                            <code class="font-mono text-xs">index</code> — that is the key the parent tracks open state with. Set
                            <code class="font-mono text-xs">allowMultiple</code> on the parent to let several panels stay open at
                            once.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-accordion :allow-multiple="false">
                                <x-v-accordion.item title="What is Verdant UI?" :index="0">
                                    A self-contained Blade component library built on Tailwind CSS and Alpine.js.
                                </x-v-accordion.item>
                                <x-v-accordion.item title="Does it need a build step?" :index="1">
                                    No. The package ships a pre-built CSS and JS bundle that the assets directive pulls in.
                                </x-v-accordion.item>
                                <x-v-accordion.item title="Can I publish the views?" :index="2">
                                    Yes: php artisan vendor:publish --tag=verdant-views
                                </x-v-accordion.item>
                            </x-v-accordion>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-richtext-content :content="$page->body"/>
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-richtext-content&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Renders trusted HTML — typically what
                            <code class="font-mono text-xs">form.richtext</code> saved — inside the library's typography styles.
                            The content is <strong>not</strong> escaped, so only pass markup you control.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-richtext-content content="<h3>Release notes</h3><p>This paragraph was stored as <strong>HTML</strong> and is rendered with the <em>richtext</em> stylesheet.</p><ul><li>First item</li><li>Second item</li></ul>"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>
            </section>

            {{-- ============================================================ --}}
            {{-- UTILITIES                                                    --}}
            {{-- ============================================================ --}}
            <section id="utilities" class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Utilities</h2>
                    <p class="text-gray-600">Date pickers, tooltips, copyable identifiers and drag-to-reorder lists.</p>
                </div>

                @php $code = <<<'BLADE'
<x-v-calendar
    :selected-date="now()->startOfMonth()"
    :events="[
        ['date' => '2026-09-08', 'title' => 'Kick-off',   'time' => '10:00'],
        ['date' => '2026-09-08', 'title' => 'Retro',      'time' => '16:00'],
        ['date' => '2026-09-21', 'title' => 'Deployment', 'time' => '09:30'],
    ]"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-calendar&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Month view built around Carbon.
                            <code class="font-mono text-xs">selectedDate</code> is any value
                            <code class="font-mono text-xs">Carbon::parse()</code> understands and decides which month is shown;
                            <code class="font-mono text-xs">events</code> are matched to days by their
                            <code class="font-mono text-xs">date</code> key.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-calendar :events="[
                                ['date' => '2026-09-08', 'title' => 'Kick-off',   'time' => '10:00'],
                                ['date' => '2026-09-08', 'title' => 'Retro',      'time' => '16:00'],
                                ['date' => '2026-09-21', 'title' => 'Deployment', 'time' => '09:30'],
                            ]"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-timeslot-selector
    name="appointment_slot"
    :timeslots="[
        ['id' => 1, 'date' => '2026-09-21', 'time' => '09:00', 'duration' => '30 minutes'],
        ['id' => 2, 'date' => '2026-09-21', 'time' => '09:30', 'duration' => '30 minutes'],
        ['id' => 3, 'date' => '2026-09-23', 'time' => '14:00', 'duration' => '60 minutes'],
    ]"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-timeslot-selector&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Calendar plus a list of the slots available on the chosen day. Days
                            without slots are not selectable. The picked slot's <code class="font-mono text-xs">id</code> is posted
                            under <code class="font-mono text-xs">name</code>.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-timeslot-selector name="appointment_slot" :timeslots="[
                                ['id' => 1, 'date' => '2026-09-21', 'time' => '09:00', 'duration' => '30 minutes'],
                                ['id' => 2, 'date' => '2026-09-21', 'time' => '09:30', 'duration' => '30 minutes'],
                                ['id' => 3, 'date' => '2026-09-23', 'time' => '14:00', 'duration' => '60 minutes'],
                                ['id' => 4, 'date' => '2026-09-28', 'time' => '11:00', 'duration' => '45 minutes'],
                            ]"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-tooltip text="Only cool developers can see this!" position="right">
    <x-v-button.primary>Hover me</x-v-button.primary>
</x-v-tooltip>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-tooltip&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Wraps any element. <code class="font-mono text-xs">position</code> is
                            <code class="font-mono text-xs">top</code> (default),
                            <code class="font-mono text-xs">bottom</code>, <code class="font-mono text-xs">left</code> or
                            <code class="font-mono text-xs">right</code>. Buttons have this built in via their own
                            <code class="font-mono text-xs">tooltip</code> prop.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-tooltip text="Only cool developers can see this!" position="right">
                                <x-v-button.primary>Hover me</x-v-button.primary>
                            </x-v-tooltip>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-identifier :value="$invoice->reference"/>
BLADE; @endphp
                <article data-example class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-identifier&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Monospace badge for a reference or id, with a copy-to-clipboard
                            button next to it.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-identifier value="VU-2026-001"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>

                @php $code = <<<'BLADE'
<x-v-reorderable-list
    :action="route('chapters.reorder')"
    :items="$chapters->map(fn ($chapter) => [
        'id'    => $chapter->id,
        'title' => $chapter->title,
        'order' => $chapter->position,
    ])->all()"/>
BLADE; @endphp
                <article data-example class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <h4 class="font-mono text-sm font-semibold text-gray-900">&lt;x-v-reorderable-list&gt;</h4>
                        <p class="mt-1 text-sm text-gray-600">Drag-and-drop (or arrow-button) ordering inside a CSRF-protected POST
                            form. Each item needs <code class="font-mono text-xs">id</code> and
                            <code class="font-mono text-xs">title</code>; <code class="font-mono text-xs">order</code> is optional
                            and only used for the number shown. On submit the controller receives
                            <code class="font-mono text-xs">items[0][id]</code>, <code class="font-mono text-xs">items[1][id]</code>
                            … in the new order.</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="bg-white p-6">
                            <x-v-reorderable-list action="#" :items="[
                                ['id' => 1, 'title' => 'Introduction', 'order' => 1],
                                ['id' => 2, 'title' => 'Getting started', 'order' => 2],
                                ['id' => 3, 'title' => 'Components', 'order' => 3],
                                ['id' => 4, 'title' => 'Theming', 'order' => 4],
                            ]"/>
                        </div>
                        <div class="relative bg-gray-900">
                            <button type="button" onclick="verdantCopyExample(this)" class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span data-copy-label>Copy</span>
                            </button>
                            <pre class="overflow-x-auto px-5 pb-5 pt-14 text-xs leading-relaxed"><code class="text-gray-100">{{ $code }}</code></pre>
                        </div>
                    </div>
                </article>
            </section>
        </div>
    </div>
</main>

<footer class="bg-white border-t border-gray-200 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center">
            <p class="text-gray-500">Verdant UI Library Demo</p>
            <p class="text-sm text-gray-400 mt-2">Built with Tailwind CSS, Alpine.js, and Laravel</p>
        </div>
    </div>
</footer>
@endsection

@push('scripts')
<script>
    function verdantCopyExample(button) {
        const example = button.closest('[data-example]');
        const code = example ? example.querySelector('pre code') : null;

        if (!code) {
            return;
        }

        const label = button.querySelector('[data-copy-label]');
        const reset = (text) => {
            label.textContent = text;
            setTimeout(() => {
                label.textContent = 'Copy';
                button.classList.remove('bg-green-600', 'border-green-500', 'text-white');
            }, 1500);
        };

        navigator.clipboard.writeText(code.textContent)
            .then(() => {
                button.classList.add('bg-green-600', 'border-green-500', 'text-white');
                reset('Copied');
            })
            .catch(() => reset('Failed'));
    }
</script>
@endpush
