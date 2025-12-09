@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200';
    
    $variants = [
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 text-white dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:focus:ring-indigo-400',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 focus:ring-gray-500 text-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white dark:focus:ring-gray-400',
        'success' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-400',
        'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-400',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 text-white dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-400',
        'info' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400',
        'outline' => 'border border-gray-300 hover:bg-gray-50 focus:ring-gray-500 text-gray-700 dark:border-gray-600 dark:hover:bg-gray-700 dark:text-gray-300 dark:focus:ring-gray-400',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : '';
@endphp

<button 
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClass . ' ' . $sizeClass . ' ' . $disabledClass]) }}
>
    {{ $slot }}
</button> 