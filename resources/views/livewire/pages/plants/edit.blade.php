<?php

use App\Models\Plant;
use Livewire\Volt\Component;

new class extends Component {
    public Plant $plant;
    public string $name = '';
    public string $latin_name = '';
    public string $description = '';
    public string $category = '';
    public string $plant_type = '';
    public string $image_url = '';

    public function mount(Plant $plant): void
    {
        $this->authorize('update', $plant);

        $this->plant = $plant;
        $this->name = $plant->name ?? '';
        $this->latin_name = $plant->latin_name ?? '';
        $this->description = $plant->description ?? '';
        $this->category = $plant->category?->value ?? '';
        $this->plant_type = $plant->plant_type?->value ?? '';
        $this->image_url = $plant->image_url ?? '';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'plant_type' => 'nullable|in:annual,biennial,perennial,shrub,tree,vine,fern,moss,succulent',
            'image_url' => 'nullable|url',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        foreach ($validated as $key => $value) {
            if ($value === '') {
                $validated[$key] = null;
            }
        }

        $this->plant->update($validated);

        session()->flash('message', 'Pflanze erfolgreich aktualisiert!');
        $this->redirect(route('plants.show', $this->plant));
    }
}; ?>

<div>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Pflanze bearbeiten</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $plant->name }} bearbeiten</p>
            </div>

            <form wire:submit="save">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium">Grundinformationen</h3>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" wire:model="name" placeholder="z.B. Basilikum"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lateinischer Name</label>
                                <input type="text" wire:model="latin_name" placeholder="z.B. Ocimum basilicum"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                @error('latin_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Beschreibung</label>
                                <textarea wire:model="description" rows="4" placeholder="Allgemeine Beschreibung..."
                                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategorie</label>
                                <select wire:model="category"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Bitte wählen...</option>
                                    @foreach(\App\Enums\Plant\PlantCategoryEnum::cases() as $type)
                                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                                @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pflanzentyp</label>
                                <select wire:model="plant_type"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Bitte wählen...</option>
                                    @foreach(\App\Enums\Plant\PlantTypeEnum::cases() as $type)
                                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                                @error('plant_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>


                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bild URL</label>
                            <input type="url" wire:model="image_url" placeholder="https://..."
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                            @error('image_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                        <a href="{{ route('plants.show', $plant) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Abbrechen
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Pflanze aktualisieren
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
