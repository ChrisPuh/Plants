<?php

use App\Models\Plant;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $latin_name = '';
    public string $description = '';
    public string $category = '';
    public string $plant_type = '';

    public string $image_url = '';

    public function mount(): void
    {
        $this->authorize('create', Plant::class);
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

        // Convert empty strings to null for nullable fields
        foreach ($validated as $key => $value) {
            if ($value === '') {
                $validated[$key] = null;
            }
        }

        Plant::create($validated);

        session()->flash('message', 'Pflanze erfolgreich erstellt!');
        $this->redirect(route('plants.index'));
    }
}; ?>

<div>
    <x-plants.layout 
        heading="Neue Pflanze hinzufügen" 
        subheading="Fügen Sie eine neue Pflanze zur Datenbank hinzu">
        
        @if (session()->has('message'))
            <x-alert type="success" class="mb-6">
                {{ session('message') }}
            </x-alert>
        @endif

        <form wire:submit="save" class="space-y-6">
            <x-form.fieldset 
                legend="Grundinformationen" 
                description="Geben Sie die wichtigsten Informationen zu der Pflanze ein">
                
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
                description="Ordnen Sie die Pflanze botanisch ein">
                
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
                description="Fügen Sie ein Bild der Pflanze hinzu">
                
                <x-form.input 
                    name="image_url" 
                    label="Bild URL" 
                    wire-model="image_url" 
                    type="url"
                    placeholder="https://example.com/image.jpg" 
                    help-text="Geben Sie eine URL zu einem Bild der Pflanze an" />
            </x-form.fieldset>

            <div class="flex justify-between items-center pt-6 border-t border-border">
                <a href="{{ route('plants.index') }}" class="btn-secondary">
                    Abbrechen
                </a>
                
                <button type="submit" class="btn-primary">
                    Pflanze erstellen
                </button>
            </div>
        </form>
    </x-plants.layout>
</div>
