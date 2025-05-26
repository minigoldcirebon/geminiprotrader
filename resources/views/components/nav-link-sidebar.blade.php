@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-gray-700 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md' // Style untuk link aktif
            : 'text-gray-300 hover:bg-gray-600 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md'; // Style untuk link non-aktif
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>