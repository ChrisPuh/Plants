@props([
    'src' => '',
    'alt' => '',
    'name' => '',
    'size' => 'md',
    'fallbackType' => 'initials' // 'initials', 'icon', 'placeholder'
])

@php
$sizeClasses = match($size) {
    'xs' => 'h-6 w-6 text-xs',
    'sm' => 'h-8 w-8 text-sm', 
    'md' => 'h-10 w-10 text-base',
    'lg' => 'h-12 w-12 text-lg',
    'xl' => 'h-16 w-16 text-xl',
    '2xl' => 'h-20 w-20 text-2xl',
    default => 'h-10 w-10 text-base'
};

$initials = '';
if ($name && $fallbackType === 'initials') {
    $words = explode(' ', trim($name));
    $initials = count($words) >= 2 
        ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
        : strtoupper(substr($name, 0, 2));
}

$bgColor = $name ? 'bg-' . ['gray', 'red', 'yellow', 'green', 'blue', 'indigo', 'purple', 'pink'][crc32($name) % 8] . '-500' : 'bg-gray-500';
@endphp

<div {{ $attributes->merge(['class' => "relative inline-block {$sizeClasses} rounded-full overflow-hidden"]) }}>
    @if($src)
        <img 
            class="h-full w-full object-cover" 
            src="{{ $src }}" 
            alt="{{ $alt ?: $name }}"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        />
        <!-- Fallback shown on image error -->
        <div class="hidden h-full w-full {{ $bgColor }} text-white font-medium justify-center items-center">
            @if($fallbackType === 'initials' && $initials)
                {{ $initials }}
            @elseif($fallbackType === 'icon')
                <svg class="h-3/4 w-3/4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            @else
                <svg class="h-3/4 w-3/4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
            @endif
        </div>
    @else
        <!-- No image provided, show fallback immediately -->
        <div class="h-full w-full {{ $bgColor }} text-white font-medium flex justify-center items-center">
            @if($fallbackType === 'initials' && $initials)
                {{ $initials }}
            @elseif($fallbackType === 'icon')
                <svg class="h-3/4 w-3/4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            @else
                <svg class="h-3/4 w-3/4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
            @endif
        </div>
    @endif
</div>