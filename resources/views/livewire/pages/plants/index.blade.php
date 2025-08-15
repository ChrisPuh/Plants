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
        <div class="bg-white rounded-lg shadow p-6 mb-6">
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
                            <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label>Pflanzentyp</flux:label>
                    <flux:select wire:model.live="plantType">
                        <option value="">Alle Typen</option>
                        @foreach($plantTypes as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>
            </div>
        </div>

        <!-- Plants Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($plants as $plant)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">


                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">{{ $plant->name }}</h3>
                        @if($plant->latin_name)
                            <p class="text-sm text-zinc-600 italic mb-2">{{ $plant->latin_name }}</p>
                        @endif

                        <div class="flex flex-wrap gap-1 mb-3">
                            @if($plant->category)
                                <flux:badge size="sm"
                                            variant="outline">{{ ucfirst($plant->category) }}</flux:badge>
                            @endif
                            @if($plant->plant_type)
                                <flux:badge size="sm"
                                            variant="outline">{{ ucfirst($plant->plant_type) }}</flux:badge>
                            @endif
                        </div>

                        @if($plant->description)
                            <p class="text-sm text-zinc-600 mb-4 line-clamp-2">{{ Str::limit($plant->description, 100) }}</p>
                        @endif

                        <div class="flex justify-between items-center">
                            <flux:button tag="a" href="{{ route('plants.show', $plant) }}" size="sm"
                                         variant="outline">
                                Details
                            </flux:button>

                            <div class="flex gap-2">
                                @can('update', $plant)
                                    <flux:button tag="a" href="{{ route('plants.edit', $plant) }}" size="sm"
                                                 variant="ghost">
                                        Bearbeiten
                                    </flux:button>
                                @endcan

                                @can('delete', $plant)
                                    <flux:button
                                        wire:click="delete({{ $plant->id }})"
                                        wire:confirm="Sind Sie sicher, dass Sie diese Pflanze löschen möchten?"
                                        size="sm"
                                        variant="danger"
                                    >
                                        Löschen
                                    </flux:button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-zinc-500 text-lg">Keine Pflanzen gefunden.</p>
                    @can('create', App\Models\Plant::class)
                        <flux:button tag="a" href="{{ route('plants.create') }}" variant="primary" class="mt-4">
                            Erste Pflanze hinzufügen
                        </flux:button>
                    @endcan
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

