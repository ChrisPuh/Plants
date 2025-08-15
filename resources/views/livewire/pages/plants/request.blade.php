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
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Neue Pflanze vorschlagen</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Schlagen Sie eine neue Pflanze vor, die zur Datenbank hinzugefügt werden soll. 
                    Ein Admin wird Ihren Vorschlag prüfen.
                </p>
            </div>

            @if (session()->has('message'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit="submit">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium">Pflanzen-Details</h3>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Name (Required) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name der Pflanze *</label>
                            <input type="text" wire:model="name" placeholder="z.B. Basilikum, Lavendel, Rose" required
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Latin Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lateinischer Name</label>
                            <input type="text" wire:model="latin_name" placeholder="z.B. Ocimum basilicum (falls bekannt)"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                            @error('latin_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Family -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pflanzenfamilie</label>
                            <input type="text" wire:model="family" placeholder="z.B. Lamiaceae (falls bekannt)"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                            @error('family') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Beschreibung</label>
                            <textarea wire:model="description" rows="4" placeholder="Beschreiben Sie die Pflanze: Aussehen, Größe, Besonderheiten..."
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Reason (Required) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Begründung *</label>
                            <textarea wire:model="reason" rows="3" required
                                      placeholder="Warum sollte diese Pflanze zur Datenbank hinzugefügt werden? Ist sie besonders interessant, selten, oder haben Sie spezielle Erfahrungen damit?"
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <div class="mt-1 text-xs text-gray-500">
                                Maximal 500 Zeichen
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                        <a href="{{ route('plants.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Abbrechen
                        </a>
                        
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Vorschlag einreichen
                        </button>
                    </div>
                </div>
            </form>

            <!-- Info Box -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            <strong>Hinweis:</strong> Alle Pflanzen-Vorschläge werden von Administratoren geprüft, bevor sie zur Datenbank hinzugefügt werden. 
                            Sie erhalten eine Benachrichtigung über den Status Ihres Vorschlags.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
