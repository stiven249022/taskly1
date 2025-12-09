@props([
    'type' => 'text',
    'name',
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autocomplete' => null,
    'size' => 'md'
])

@php
    $id = $id ?? $name;
    $baseClasses = 'block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white placeholder-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-indigo-400 dark:focus:border-indigo-400';
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-3 text-base',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : '';
@endphp

<input 
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
    {{ $disabled ? 'disabled' : '' }}
    {{ $readonly ? 'readonly' : '' }}
    {{ $autocomplete ? 'autocomplete=' . $autocomplete : '' }}
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $sizeClass . ' ' . $disabledClass]) }}
> 