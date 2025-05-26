@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'p-6',
    'shadow' => 'shadow',
    'rounded' => 'rounded-lg',
    'background' => 'bg-white'
])

<div {{ $attributes->merge(['class' => "$background $shadow $rounded $padding"]) }}>
    @if($title || $subtitle)
        <div class="mb-4">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-sm text-gray-600">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    {{ $slot }}
</div>
