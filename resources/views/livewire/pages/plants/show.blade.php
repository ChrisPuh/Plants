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
    <x-plants.layout :show-navigation="false">
        @if (session()->has('message'))
            <x-alert type="success" class="mb-6">
                {{ session('message') }}
            </x-alert>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-text-primary">{{ $plant->name }}</h1>
                    @if($plant->latin_name)
                        <p class="text-xl text-text-secondary italic mt-2">{{ $plant->latin_name }}</p>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2">
                    @can('update', $plant)
                        <a href="{{ route('plants.edit', $plant) }}" class="btn-primary">
                            Bearbeiten
                        </a>
                    @endcan

                    @cannot('update', $plant)
                        <a href="{{ route('plants.request') }}" class="btn-secondary">
                            Verbesserung vorschlagen
                        </a>
                    @endcannot

                    <a href="{{ route('plants.index') }}" class="btn-secondary">
                        Zurück zur Liste
                    </a>
                </div>
            </div>

            <!-- Badges -->
            @if($plant->category || $plant->plant_type)
                <div class="flex flex-wrap gap-2 mt-6">
                    @if($plant->category)
                        <span class="badge badge-primary">
                            {{ $plant->category->label() }}
                        </span>
                    @endif
                    @if($plant->plant_type)
                        <span class="badge badge-success">
                            {{ $plant->plant_type->label() }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Image -->
                <div class="card p-6">
                    <h3 class="text-lg font-medium mb-4">Bild</h3>
                    <div class="flex justify-center">
                        <x-plants.image :plant="$plant" size="xl" show-name="false" class="max-w-full" />
                    </div>
                </div>

                <!-- Description -->
                <div class="card p-6">
                    <h3 class="text-lg font-medium mb-4">Beschreibung</h3>
                    @if($plant->description)
                        <p class="text-text-secondary leading-relaxed">{{ $plant->description }}</p>
                    @else
                        <div class="text-center py-8">
                            <p class="text-text-muted mb-4">Noch keine Beschreibung vorhanden.</p>
                            @cannot('update', $plant)
                                <p class="text-sm text-text-muted mb-4">Helfen Sie mit und schlagen Sie eine Beschreibung vor!</p>
                                <a href="{{ route('plants.request') }}" class="btn-secondary">
                                    Beschreibung vorschlagen
                                </a>
                            @endcannot
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Basic Info -->
                <div class="card p-6">
                    <h3 class="text-lg font-medium mb-4">Grundinformationen</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-text-secondary">Name:</span>
                            <p class="text-text-primary">{{ $plant->name }}</p>
                        </div>
                        @if($plant->latin_name)
                            <div>
                                <span class="text-sm font-medium text-text-secondary">Lateinischer Name:</span>
                                <p class="text-text-primary italic">{{ $plant->latin_name }}</p>
                            </div>
                        @else
                            <div>
                                <span class="text-sm font-medium text-text-muted">Lateinischer Name:</span>
                                <p class="text-text-muted text-sm">Noch nicht erfasst</p>
                                @cannot('update', $plant)
                                    <a href="{{ route('plants.request') }}" class="text-xs text-primary-600 hover:text-primary-700">
                                        → Ergänzen vorschlagen
                                    </a>
                                @endcannot
                            </div>
                        @endif

                        @if($plant->category)
                            <div>
                                <span class="text-sm font-medium text-text-secondary">Kategorie:</span>
                                <p class="text-text-primary">{{ $plant->category->label() }}</p>
                            </div>
                        @else
                            <div>
                                <span class="text-sm font-medium text-text-muted">Kategorie:</span>
                                <p class="text-text-muted text-sm">Noch nicht erfasst</p>
                                @cannot('update', $plant)
                                    <a href="{{ route('plants.request') }}" class="text-xs text-primary-600 hover:text-primary-700">
                                        → Ergänzen vorschlagen
                                    </a>
                                @endcannot
                            </div>
                        @endif

                        @if($plant->plant_type)
                            <div>
                                <span class="text-sm font-medium text-text-secondary">Pflanzentyp:</span>
                                <p class="text-text-primary">{{ $plant->plant_type->label() }}</p>
                            </div>
                        @else
                            <div>
                                <span class="text-sm font-medium text-text-muted">Pflanzentyp:</span>
                                <p class="text-text-muted text-sm">Noch nicht erfasst</p>
                                @cannot('update', $plant)
                                    <a href="{{ route('plants.request') }}" class="text-xs text-primary-600 hover:text-primary-700">
                                        → Ergänzen vorschlagen
                                    </a>
                                @endcannot
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Botanical Details (Future Feature) -->
                <div class="card p-6">
                    <h3 class="text-lg font-medium mb-4">Botanische Details</h3>
                    <div class="text-center py-8">
                        <p class="text-text-muted mb-4">Erweiterte botanische Informationen werden in einer zukünftigen Version hinzugefügt.</p>
                        @cannot('update', $plant)
                            <p class="text-sm text-text-muted">Haben Sie botanische Informationen zu dieser Pflanze?</p>
                            <a href="{{ route('plants.request') }}" class="btn-secondary mt-3">
                                Daten beitragen
                            </a>
                        @endcannot
                    </div>
                </div>
            </div>
        </div>
    </x-plants.layout>
</div>
