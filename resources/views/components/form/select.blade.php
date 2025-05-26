@props([
    'label' => null,
    'name',
    'value' => null,
    'placeholder' => '-- Pilih --',
    'options' => [],
    'required' => false,
    'disabled' => false,
    'help' => null,
    'error' => null
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select 
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge([
            'class' => 'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ' . 
                      ($error ? 'border-red-300 bg-red-50' : 'border-gray-300') .
                      ($disabled ? ' bg-gray-100 cursor-not-allowed' : '')
        ]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @if(is_array($options))
            @foreach($options as $optionValue => $optionText)
                <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                    {{ $optionText }}
                </option>
            @endforeach
        @else
            {{ $options }}
        @endif
        
        {{ $slot }}
    </select>
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @elseif($errors->has($name))
        <p class="mt-1 text-sm text-red-600">{{ $errors->first($name) }}</p>
    @elseif($help)
        <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>
