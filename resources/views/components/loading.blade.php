@props([
    'size' => 'md', // sm, md, lg, xl
    'color' => 'blue', // blue, white, gray, green, red
    'type' => 'spinner' // spinner, dots, bars
])

@php
$sizes = [
    'sm' => 'h-4 w-4',
    'md' => 'h-6 w-6', 
    'lg' => 'h-8 w-8',
    'xl' => 'h-12 w-12'
];

$colors = [
    'blue' => 'text-blue-600',
    'white' => 'text-white',
    'gray' => 'text-gray-600',
    'green' => 'text-green-600',
    'red' => 'text-red-600'
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
$colorClass = $colors[$color] ?? $colors['blue'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
    @if($type === 'spinner')
        <svg class="animate-spin {{ $sizeClass }} {{ $colorClass }}" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($type === 'dots')
        <div class="flex space-x-1">
            <div class="w-2 h-2 {{ $colorClass }} bg-current rounded-full animate-bounce" style="animation-delay: 0ms"></div>
            <div class="w-2 h-2 {{ $colorClass }} bg-current rounded-full animate-bounce" style="animation-delay: 150ms"></div>
            <div class="w-2 h-2 {{ $colorClass }} bg-current rounded-full animate-bounce" style="animation-delay: 300ms"></div>
        </div>
    @elseif($type === 'bars')
        <div class="flex space-x-1">
            <div class="w-1 h-4 {{ $colorClass }} bg-current animate-pulse" style="animation-delay: 0ms"></div>
            <div class="w-1 h-4 {{ $colorClass }} bg-current animate-pulse" style="animation-delay: 150ms"></div>
            <div class="w-1 h-4 {{ $colorClass }} bg-current animate-pulse" style="animation-delay: 300ms"></div>
            <div class="w-1 h-4 {{ $colorClass }} bg-current animate-pulse" style="animation-delay: 450ms"></div>
        </div>
    @endif
    
    @if($slot->isNotEmpty())
        <span class="ml-2 text-sm {{ $colorClass }}">{{ $slot }}</span>
    @endif
</div>
