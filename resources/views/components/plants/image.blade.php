@props([
    'plant',
    'size' => 'md',
    'showName' => false
])

@php
$sizeClasses = match($size) {
    'xs' => 'h-16 w-16',
    'sm' => 'h-24 w-24',
    'md' => 'h-32 w-32',
    'lg' => 'h-48 w-48',
    'xl' => 'h-64 w-64',
    'full' => 'h-full w-full',
    default => 'h-32 w-32'
};

$initials = '';
if ($plant->name) {
    $words = explode(' ', trim($plant->name));
    $initials = count($words) >= 2
        ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
        : strtoupper(substr($plant->name, 0, 2));
}

$bgColor = $plant->name ? 'bg-' . ['emerald', 'green', 'lime', 'teal', 'cyan', 'sky', 'blue', 'indigo'][crc32($plant->name) % 8] . '-500' : 'bg-green-500';
@endphp

<div {{ $attributes->merge(['class' => "relative inline-block {$sizeClasses} rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800"]) }}>
    @if($plant->image_url)
        <img
            class="h-full w-full object-cover"
            src="{{ $plant->image_url }}"
            alt="{{ $plant->name }}"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        />
        <!-- Fallback shown on image error -->
        <div class="hidden h-full w-full {{ $bgColor }} text-white font-bold justify-center items-center flex-col">
            @if($initials)
                <div class="text-2xl">{{ $initials }}</div>
                @if($showName && $plant->name)
                    <div class="text-xs mt-1 text-center px-2 opacity-90">{{ Str::limit($plant->name, 15) }}</div>
                @endif
            @else
                <svg class="h-1/2 w-1/2 text-white opacity-75" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                @if($showName && $plant->name)
                    <div class="text-xs mt-2 text-center px-2 opacity-90">{{ Str::limit($plant->name, 15) }}</div>
                @endif
            @endif
        </div>
    @else
        <!-- No image provided, show plant-themed fallback immediately -->
        <div class="h-full w-full {{ $bgColor }} text-white font-bold flex justify-center items-center flex-col">
            @if($initials)
                <div class="text-2xl">{{ $initials }}</div>
                @if($showName && $plant->name)
                    <div class="text-xs mt-1 text-center px-2 opacity-90">{{ Str::limit($plant->name, 15) }}</div>
                @endif
            @else
                <svg class="h-1/2 w-1/2 text-white opacity-75" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/>
                </svg>
                @if($showName && $plant->name)
                    <div class="text-xs mt-2 text-center px-2 opacity-90">{{ Str::limit($plant->name, 15) }}</div>
                @endif
            @endif
        </div>
    @endif
</div>
