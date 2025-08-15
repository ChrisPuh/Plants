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
                        <span
                            class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ ucfirst($plant->category->label()) }}</span>
                    @endif
                    @if($plant->plant_type)
                        <span
                            class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ ucfirst($plant->plant_type->label()) }}</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Image -->
                    @if($plant->image_url)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <img src="{{ $plant->image_url }}" alt="{{ $plant->name }}"
                                 class="w-full h-64 object-cover">
                        </div>
                    @endif

                    <!-- Descriptions -->
                    @if($plant->description)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium mb-3">Beschreibung</h3>
                            <p class="text-gray-700">{{ $plant->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Botanical Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium mb-4">Botanische Information ( wird noch implementiert )</h3>
                        <div class="space-y-3">

                        </div>
                    </div>

                    <!-- Growth Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium mb-4">Wachstumsinformationen ( wird noch implementiert )</h3>
                        <div class="space-y-3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
