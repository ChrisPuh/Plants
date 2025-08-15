<?php

use App\Models\Plant;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $plantType = '';

    public function mount(): void
    {
        $this->search = request('search', '');
        $this->category = request('category', '');
        $this->plantType = request('plant_type', '');
    }

    public function with(): array
    {
        return [
            'plants' => Plant::query()
                ->when($this->search, fn($query) => $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('latin_name', 'like', "%{$this->search}%")
                    ->orWhere('family', 'like', "%{$this->search}%")
                )
                ->when($this->category, fn($query) => $query->where('category', $this->category))
                ->when($this->plantType, fn($query) => $query->where('plant_type', $this->plantType))
                ->orderBy('name')
                ->paginate(12),
            'categories' => Plant::distinct()->pluck('category')->filter()->sort()->values(),
            'plantTypes' => Plant::distinct()->pluck('plant_type')->filter()->sort()->values(),
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedPlantType(): void
    {
        $this->resetPage();
    }

    public function delete(Plant $plant): void
    {
        $this->authorize('delete', $plant);
        $plant->delete();

        session()->flash('message', 'Pflanze erfolgreich gelöscht!');
    }
}; ?>
<div>
    <x-plants.layout
        heading="Pflanzen-Datenbank"
        subheading="Verwalten Sie alle Pflanzen in der Datenbank">

        @if (session()->has('message'))
            <x-alert type="success" class="mb-6">
                {{ session('message') }}
            </x-alert>
        @endif

        <!-- Filters -->
        <div class="card p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:field>
                    <flux:label>Suche</flux:label>
                    <flux:input wire:model.live.debounce.300ms="search"
                                placeholder="Name, lateinischer Name oder Familie..."/>
                </flux:field>

                <flux:field>
                    <flux:label>Kategorie</flux:label>
                    <flux:select wire:model.live="category">
                        <option value="">Alle Kategorien</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ ucfirst($cat->label()) }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label>Pflanzentyp</flux:label>
                    <flux:select wire:model.live="plantType">
                        <option value="">Alle Typen</option>
                        @foreach($plantTypes as $type)
                            <option value="{{ $type }}">{{ ucfirst($type->label()) }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>
            </div>
        </div>

        <!-- Plants Grid -->
        <div class="plant-grid">
            @forelse($plants as $plant)
                <div class="plant-card">
                    <!-- Plant Image with Fallback -->
                    <div class="relative">
                        <x-plants.image :plant="$plant" size="full" class="plant-card-image"/>

                        <!-- Quick Actions Overlay -->
                        <div class="absolute top-2 right-2 flex gap-1 opacity-0 hover:opacity-100 transition-opacity">
                            @can('update', $plant)
                                <flux:button tag="a" href="{{ route('plants.edit', $plant) }}" size="xs" variant="ghost"
                                             class="bg-white/80 backdrop-blur-sm">
                                    <flux:icon.pencil class="w-3 h-3"/>
                                </flux:button>
                            @endcan
                            @can('delete', $plant)
                                <flux:button
                                    wire:click="delete({{ $plant->id }})"
                                    wire:confirm="Sind Sie sicher, dass Sie diese Pflanze löschen möchten?"
                                    size="xs"
                                    variant="danger"
                                    class="bg-white/80 backdrop-blur-sm"
                                >
                                    <flux:icon.trash class="w-3 h-3"/>
                                </flux:button>
                            @endcan
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-4 space-y-3">
                        <!-- Title and Latin Name -->
                        <div>
                            <h3 class="font-semibold text-lg text-text-primary leading-tight">{{ $plant->name }}</h3>
                            @if($plant->latin_name)
                                <p class="text-sm text-text-secondary italic">{{ $plant->latin_name }}</p>
                            @endif
                        </div>

                        <!-- Badges -->
                        @if($plant->category || $plant->plant_type)
                            <div class="flex flex-wrap gap-1">
                                @if($plant->category)
                                    <flux:badge variant="solid"
                                                color="emerald">{{ $plant->category->label() }}</flux:badge>
                                @endif
                                @if($plant->plant_type)
                                    <flux:badge variant="solid"
                                                color="emerald">{{ $plant->plant_type->label() }}</flux:badge>
                                @endif
                            </div>
                        @endif

                        <!-- Description -->
                        @if($plant->description)
                            <p class="text-sm text-text-secondary line-clamp-2">{{ Str::limit($plant->description, 80) }}</p>
                        @else
                            <p class="text-sm text-text-muted italic">Noch keine Beschreibung vorhanden</p>
                        @endif

                        <!-- Actions -->
                        <div class="flex justify-between items-center pt-2">
                            <flux:button tag="a" href="{{ route('plants.show', $plant) }}" size="sm" variant="outline">
                                Details ansehen
                            </flux:button>

                            <div class="text-xs text-text-muted">
                                <!-- Future: Show contribution count or status -->
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full card p-12 text-center">
                    <div class="max-w-sm mx-auto">
                        <svg class="h-12 w-12 text-text-muted mx-auto mb-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3v6m0 0l3-3m-3 3L9 9"/>
                        </svg>
                        <p class="text-text-muted text-lg mb-4">Keine Pflanzen gefunden</p>
                        <p class="text-sm text-text-secondary mb-6">
                            @if(request('search') || request('category') || request('plant_type'))
                                Versuchen Sie es mit anderen Filtereinstellungen.
                            @else
                                Die Datenbank ist noch leer. Fügen Sie die erste Pflanze hinzu!
                            @endif
                        </p>
                        @can('create', App\Models\Plant::class)
                            <flux:button tag="a" href="{{ route('plants.create') }}" variant="primary">
                                <flux:icon.plus class="w-4 h-4 mr-2"/>
                                Erste Pflanze hinzufügen
                            </flux:button>
                        @else
                            <flux:button tag="a" href="{{ route('plants.request') }}" variant="primary">
                                <flux:icon.paper-airplane class="w-4 h-4 mr-2"/>
                                Pflanze vorschlagen
                            </flux:button>
                        @endcan
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($plants->hasPages())
            <div class="mt-8">
                {{ $plants->links() }}
            </div>
        @endif
    </x-plants.layout>
</div>

