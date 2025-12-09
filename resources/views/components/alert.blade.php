@props(['type' => 'info', 'message'])

@php
    $classes = [
        'success' => 'bg-green-50 dark:bg-green-900/50 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
        'error' => 'bg-red-50 dark:bg-red-900/50 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200',
        'warning' => 'bg-yellow-50 dark:bg-yellow-900/50 border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200',
        'info' => 'bg-blue-50 dark:bg-blue-900/50 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200'
    ];

    $icons = [
        'success' => '✓',
        'error' => '✗',
        'warning' => '⚠',
        'info' => 'ℹ'
    ];

    $class = $classes[$type] ?? $classes['info'];
    $icon = $icons[$type] ?? $icons['info'];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="relative p-4 mb-4 border rounded-lg {{ $class }}">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <span class="text-lg font-semibold">{{ $icon }}</span>
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm font-medium">{{ $message }}</p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button @click="show = false" type="button" class="inline-flex rounded-md p-1.5 {{ $class }} hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 dark:focus:ring-offset-green-900 focus:ring-green-600 dark:focus:ring-green-400">
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div> 