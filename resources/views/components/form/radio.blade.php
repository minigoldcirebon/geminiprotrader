@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'required' => false,
    'help' => '',
    'layout' => 'vertical', // vertical, horizontal, grid
    'columns' => 2,
    'disabled' => false
])

@php
$layoutClasses = [
    'vertical' => 'space-y-2',
    'horizontal' => 'flex flex-wrap gap-6',
    'grid' => 'grid grid-cols-' . $columns . ' gap-4'
];
$layoutClass = $layoutClasses[$layout] ?? $layoutClasses['vertical'];
@endphp

<div class="space-y-2">
    @if($label)
        <fieldset>
            <legend class="text-sm font-medium text-gray-700">
                {{ $label }}
                @if($required)
                    <span class="text-red-500">*</span>
                @endif
            </legend>
        </fieldset>
    @endif
    
    <div class="{{ $layoutClass }}">
        @foreach($options as $value => $text)
            <label class="flex items-center space-x-2 cursor-pointer">
                <input 
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $value }}"
                    @if(old($name) === $value) checked @endif
                    @if($required) required @endif
                    @if($disabled) disabled @endif
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                >
                <span class="text-sm text-gray-700">{{ $text }}</span>
            </label>
        @endforeach
    </div>
    
    @if($help)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
