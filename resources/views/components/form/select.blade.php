@props([
    'name' => '',
    'label' => '',
    'required' => false,
    'wireModel' => '',
    'helpText' => '',
    'placeholder' => '',
])

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1" aria-label="required">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        <select 
            id="{{ $name }}"
            name="{{ $name }}"
            @if($wireModel) wire:model="{{ $wireModel }}" @endif
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'block w-full rounded-lg border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:bg-gray-700 dark:text-white dark:ring-gray-600 dark:focus:ring-indigo-500 sm:text-sm sm:leading-6'
            ]) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            {{ $slot }}
        </select>
        
        @if($required)
            <div class="absolute inset-y-0 right-8 pr-3 flex items-center pointer-events-none">
                <span class="text-red-500 text-xs" aria-hidden="true">*</span>
            </div>
        @endif
    </div>
    
    @if($helpText)
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $helpText }}</p>
    @endif
    
    @error($name)
        <p class="text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
    @enderror
</div>