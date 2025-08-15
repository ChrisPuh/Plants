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
    <x-plants.layout 
        heading="Pflanze bearbeiten" 
        :subheading="$plant->name . ' bearbeiten'">
        
        @if (session()->has('message'))
            <x-alert type="success" class="mb-6">
                {{ session('message') }}
            </x-alert>
        @endif

        <form wire:submit="save" class="space-y-6">
            <x-form.fieldset 
                legend="Grundinformationen" 
                description="Bearbeiten Sie die wichtigsten Informationen der Pflanze">
                
                <x-form.grid cols="2">
                    <x-form.input 
                        name="name" 
                        label="Pflanzenname" 
                        wire-model="name" 
                        required 
                        placeholder="z.B. Basilikum" />
                    
                    <x-form.input 
                        name="latin_name" 
                        label="Lateinischer Name" 
                        wire-model="latin_name" 
                        placeholder="z.B. Ocimum basilicum" />
                </x-form.grid>

                <x-form.textarea 
                    name="description" 
                    label="Beschreibung" 
                    wire-model="description" 
                    placeholder="Beschreiben Sie die Pflanze..."
                    rows="3" />
            </x-form.fieldset>

            <x-form.fieldset 
                legend="Klassifizierung" 
                description="Aktualisieren Sie die botanische Einordnung">
                
                <x-form.grid cols="2">
                    <x-form.select 
                        name="category" 
                        label="Kategorie" 
                        wire-model="category" 
                        placeholder="Bitte wählen...">
                        @foreach(\App\Enums\Plant\PlantCategoryEnum::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </x-form.select>
                    
                    <x-form.select 
                        name="plant_type" 
                        label="Pflanzentyp" 
                        wire-model="plant_type" 
                        placeholder="Bitte wählen...">
                        @foreach(\App\Enums\Plant\PlantTypeEnum::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </x-form.select>
                </x-form.grid>
            </x-form.fieldset>

            <x-form.fieldset 
                legend="Bild" 
                description="Aktualisieren Sie das Pflanzenbild">
                
                @if($plant->image_url)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Aktuelles Bild</label>
                        <x-plants.image :plant="$plant" size="md" class="rounded-lg" />
                    </div>
                @endif
                
                <x-form.input 
                    name="image_url" 
                    label="Bild URL" 
                    wire-model="image_url" 
                    type="url"
                    placeholder="https://example.com/image.jpg" 
                    help-text="Geben Sie eine neue URL ein, um das Bild zu ändern" />
            </x-form.fieldset>

            <div class="flex justify-between items-center pt-6 border-t border-border">
                <a href="{{ route('plants.show', $plant) }}" class="btn-secondary">
                    Abbrechen
                </a>
                
                <button type="submit" class="btn-primary">
                    Pflanze aktualisieren
                </button>
            </div>
        </form>
    </x-plants.layout>
</div>
