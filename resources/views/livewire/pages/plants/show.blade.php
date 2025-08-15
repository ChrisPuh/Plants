<?php

use App\Models\Plant;
use Livewire\Volt\Component;

new class extends Component {
    public Plant $plant;

    public function mount(Plant $plant): void
    {
        $this->plant = $plant;
    }
}; ?>

<div>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $plant->name }}</h1>
                        @if($plant->latin_name)
                            <p class="text-xl text-gray-600 italic mt-1">{{ $plant->latin_name }}</p>
                        @endif
                    </div>
                    
                    <div class="flex gap-2">
                        @can('update', $plant)
                            <a href="{{ route('plants.edit', $plant) }}" 
                               class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Bearbeiten
                            </a>
                        @endcan
                        
                        <a href="{{ route('plants.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Zurück zur Liste
                        </a>
                    </div>
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap gap-2 mt-4">
                    @if($plant->category)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ ucfirst($plant->category) }}</span>
                    @endif
                    @if($plant->plant_type)
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ ucfirst($plant->plant_type) }}</span>
                    @endif
                    @if($plant->is_edible)
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-sm">Essbar</span>
                    @endif
                    @if($plant->is_toxic)
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Giftig</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Image -->
                    @if($plant->image_url)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <img src="{{ $plant->image_url }}" alt="{{ $plant->name }}" class="w-full h-64 object-cover">
                        </div>
                    @endif

                    <!-- Descriptions -->
                    @if($plant->description)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium mb-3">Beschreibung</h3>
                            <p class="text-gray-700">{{ $plant->description }}</p>
                        </div>
                    @endif

                    @if($plant->botanical_description)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium mb-3">Botanische Beschreibung</h3>
                            <p class="text-gray-700">{{ $plant->botanical_description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Botanical Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium mb-4">Botanische Information</h3>
                        <div class="space-y-3">
                            @if($plant->family)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Familie:</span>
                                    <span class="text-sm text-gray-900 block">{{ $plant->family }}</span>
                                </div>
                            @endif
                            @if($plant->genus)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Gattung:</span>
                                    <span class="text-sm text-gray-900 block">{{ $plant->genus }}</span>
                                </div>
                            @endif
                            @if($plant->species)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Art:</span>
                                    <span class="text-sm text-gray-900 block">{{ $plant->species }}</span>
                                </div>
                            @endif
                            @if($plant->common_names)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Alternative Namen:</span>
                                    <span class="text-sm text-gray-900 block">{{ $plant->common_names }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Growth Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium mb-4">Wachstumsinformationen</h3>
                        <div class="space-y-3">
                            @if($plant->growth_habit)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Wuchsform:</span>
                                    <span class="text-sm text-gray-900 block">{{ ucfirst($plant->growth_habit) }}</span>
                                </div>
                            @endif
                            @if($plant->native_region)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Herkunftsregion:</span>
                                    <span class="text-sm text-gray-900 block">{{ $plant->native_region }}</span>
                                </div>
                            @endif
                            @if($plant->height_min_cm || $plant->height_max_cm)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Höhe:</span>
                                    <span class="text-sm text-gray-900 block">
                                        @if($plant->height_min_cm && $plant->height_max_cm)
                                            {{ $plant->height_min_cm }} - {{ $plant->height_max_cm }} cm
                                        @elseif($plant->height_min_cm)
                                            ab {{ $plant->height_min_cm }} cm
                                        @elseif($plant->height_max_cm)
                                            bis {{ $plant->height_max_cm }} cm
                                        @endif
                                    </span>
                                </div>
                            @endif
                            @if($plant->width_min_cm || $plant->width_max_cm)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Breite:</span>
                                    <span class="text-sm text-gray-900 block">
                                        @if($plant->width_min_cm && $plant->width_max_cm)
                                            {{ $plant->width_min_cm }} - {{ $plant->width_max_cm }} cm
                                        @elseif($plant->width_min_cm)
                                            ab {{ $plant->width_min_cm }} cm
                                        @elseif($plant->width_max_cm)
                                            bis {{ $plant->width_max_cm }} cm
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Info -->
                    @if($plant->bloom_time || $plant->flower_color || $plant->foliage_type || $plant->bark_type)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium mb-4">Weitere Eigenschaften</h3>
                            <div class="space-y-3">
                                @if($plant->bloom_time)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Blütezeit:</span>
                                        <span class="text-sm text-gray-900 block">{{ $plant->bloom_time }}</span>
                                    </div>
                                @endif
                                @if($plant->flower_color)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Blütenfarbe:</span>
                                        <span class="text-sm text-gray-900 block">{{ $plant->flower_color }}</span>
                                    </div>
                                @endif
                                @if($plant->foliage_type)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Laubtyp:</span>
                                        <span class="text-sm text-gray-900 block">{{ ucfirst($plant->foliage_type) }}</span>
                                    </div>
                                @endif
                                @if($plant->bark_type)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Rindentyp:</span>
                                        <span class="text-sm text-gray-900 block">{{ $plant->bark_type }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
