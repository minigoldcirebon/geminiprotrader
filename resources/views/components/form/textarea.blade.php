@props([
    'label' => '',
    'name' => '',
    'required' => false,
    'help' => '',
    'rows' => 4,
    'disabled' => false
])

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <textarea 
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical ' . ($errors->has($name) ? 'border-red-500' : '')]) }}
    >{{ $slot }}</textarea>
    
    @if($help)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
