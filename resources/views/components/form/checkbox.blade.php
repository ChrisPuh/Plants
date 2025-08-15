@props([
    'name' => '',
    'label' => '',
    'wireModel' => '',
    'value' => '',
    'checked' => false,
    'helpText' => '',
])

<div class="space-y-1">
    <div class="relative flex items-start">
        <div class="flex h-6 items-center">
            <input 
                type="checkbox"
                id="{{ $name }}"
                name="{{ $name }}"
                @if($wireModel) wire:model="{{ $wireModel }}" @endif
                @if($value) value="{{ $value }}" @endif
                @if($checked) checked @endif
                {{ $attributes->merge([
                    'class' => 'h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-indigo-500'
                ]) }}
            />
        </div>
        
        @if($label)
            <div class="ml-3 text-sm leading-6">
                <label for="{{ $name }}" class="font-medium text-gray-900 dark:text-white cursor-pointer">
                    {{ $label }}
                </label>
            </div>
        @endif
    </div>
    
    @if($helpText)
        <p class="text-xs text-gray-500 dark:text-gray-400 ml-7">{{ $helpText }}</p>
    @endif
    
    @error($name)
        <p class="text-sm text-red-600 dark:text-red-400 ml-7" role="alert">{{ $message }}</p>
    @enderror
</div>