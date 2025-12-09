@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'p-6',
    'shadow' => 'shadow',
    'rounded' => 'rounded-lg'
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 ' . $shadow . ' ' . $rounded]) }}>
    @if($title || $subtitle)
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            @if($title)
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
</div> 