<?php

use App\Models\PlantRequest;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $latin_name = '';
    public string $family = '';
    public string $description = '';
    public string $reason = '';

    public function mount(): void
    {
        $this->authorize('create', PlantRequest::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'family' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'reason' => 'required|string|max:500',
        ];
    }

    public function submit(): void
    {
        $validated = $this->validate();

        PlantRequest::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'latin_name' => $validated['latin_name'],
            'family' => $validated['family'],
            'description' => $validated['description'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        session()->flash('message', 'Ihr Pflanzen-Vorschlag wurde erfolgreich eingereicht und wird von einem Admin geprüft.');
        
        $this->reset();
    }
}; ?>

<div>
    <x-plants.layout 
        heading="Neue Pflanze vorschlagen" 
        subheading="Schlagen Sie eine neue Pflanze vor, die zur Datenbank hinzugefügt werden soll">
        
        @if (session()->has('message'))
            <x-alert type="success" class="mb-6">
                {{ session('message') }}
            </x-alert>
        @endif

        <form wire:submit="submit" class="space-y-6">
            <x-form.fieldset 
                legend="Grundinformationen" 
                description="Geben Sie die wichtigsten Informationen zu der vorgeschlagenen Pflanze ein">
                
                <x-form.grid cols="2">
                    <x-form.input 
                        name="name" 
                        label="Name der Pflanze" 
                        wire-model="name" 
                        required 
                        placeholder="z.B. Basilikum, Lavendel, Rose" />
                    
                    <x-form.input 
                        name="latin_name" 
                        label="Lateinischer Name" 
                        wire-model="latin_name" 
                        placeholder="z.B. Ocimum basilicum (falls bekannt)" />
                </x-form.grid>

                <x-form.input 
                    name="family" 
                    label="Pflanzenfamilie" 
                    wire-model="family" 
                    placeholder="z.B. Lamiaceae (falls bekannt)" />

                <x-form.textarea 
                    name="description" 
                    label="Beschreibung" 
                    wire-model="description" 
                    placeholder="Beschreiben Sie die Pflanze: Aussehen, Größe, Besonderheiten..."
                    rows="4" />
            </x-form.fieldset>

            <x-form.fieldset 
                legend="Begründung" 
                description="Erklären Sie, warum diese Pflanze zur Datenbank hinzugefügt werden sollte">
                
                <x-form.textarea 
                    name="reason" 
                    label="Begründung" 
                    wire-model="reason" 
                    required
                    placeholder="Warum sollte diese Pflanze zur Datenbank hinzugefügt werden? Ist sie besonders interessant, selten, oder haben Sie spezielle Erfahrungen damit?"
                    rows="3"
                    help-text="Maximal 500 Zeichen" />
            </x-form.fieldset>

            <div class="flex justify-between items-center pt-6 border-t border-border">
                <a href="{{ route('plants.index') }}" class="btn-secondary">
                    Abbrechen
                </a>
                
                <button type="submit" class="btn-primary">
                    Vorschlag einreichen
                </button>
            </div>
        </form>

        <!-- Info Box -->
        <x-alert type="info" class="mt-8" title="Hinweis">
            Alle Pflanzen-Vorschläge werden von Administratoren geprüft, bevor sie zur Datenbank hinzugefügt werden. 
            Sie erhalten eine Benachrichtigung über den Status Ihres Vorschlags.
        </x-alert>
    </x-plants.layout>
</div>
