@props(['legend' => '', 'description' => ''])

<fieldset {{ $attributes->merge(['class' => 'border border-gray-200 dark:border-gray-700 rounded-lg p-6 bg-white dark:bg-gray-800']) }}>
    @if($legend)
        <legend class="text-lg font-semibold text-gray-900 dark:text-white px-3 -ml-3">
            {{ $legend }}
        </legend>
    @endif
    
    @if($description)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 mb-6">
            {{ $description }}
        </p>
    @endif
    
    <div class="space-y-6">
        {{ $slot }}
    </div>
</fieldset>