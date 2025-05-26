@props([
    'label' => '',
    'name' => '',
    'value' => '',
    'checked' => false,
    'required' => false,
    'help' => '',
    'disabled' => false,
    'size' => 'md' // sm, md, lg
])

@php
$sizeClasses = [
    'sm' => 'h-3 w-3',
    'md' => 'h-4 w-4',
    'lg' => 'h-5 w-5'
];
$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="space-y-1">
    <label class="flex items-start space-x-2 cursor-pointer">
        <input 
            type="checkbox"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $value }}"
            @if($checked || old($name)) checked @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge(['class' => 'rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ' . $sizeClass]) }}
        >
        
        <div class="flex-1">
            @if($label)
                <span class="text-sm font-medium text-gray-700">
                    {{ $label }}
                    @if($required)
                        <span class="text-red-500">*</span>
                    @endif
                </span>
            @endif
            
            @if($slot->isNotEmpty())
                <div class="text-sm text-gray-700">
                    {{ $slot }}
                </div>
            @endif
            
            @if($help)
                <p class="text-sm text-gray-500 mt-1">{{ $help }}</p>
            @endif
        </div>
    </label>
    
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
