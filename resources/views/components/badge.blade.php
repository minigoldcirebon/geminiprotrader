@props([
    'variant' => 'primary', // primary, secondary, success, danger, warning, info, light, dark
    'size' => 'md', // sm, md, lg
    'rounded' => 'full' // full, md, lg
])

@php
$baseClasses = 'inline-flex items-center font-medium';

$variants = [
    'primary' => 'bg-blue-100 text-blue-800',
    'secondary' => 'bg-gray-100 text-gray-800',
    'success' => 'bg-green-100 text-green-800',
    'danger' => 'bg-red-100 text-red-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'info' => 'bg-cyan-100 text-cyan-800',
    'light' => 'bg-gray-50 text-gray-600',
    'dark' => 'bg-gray-800 text-gray-100',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-0.5 text-sm',
    'lg' => 'px-3 py-1 text-base',
];

$roundedClasses = [
    'full' => 'rounded-full',
    'md' => 'rounded-md',
    'lg' => 'rounded-lg',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size] . ' ' . $roundedClasses[$rounded];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
