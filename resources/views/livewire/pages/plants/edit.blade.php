<?php

use App\Models\Plant;
use Livewire\Volt\Component;

new class extends Component {
    public Plant $plant;
    public string $name = '';
    public string $latin_name = '';
    public string $family = '';
    public string $genus = '';
    public string $species = '';
    public string $common_names = '';
    public string $description = '';
    public string $botanical_description = '';
    public string $category = '';
    public string $plant_type = '';
    public string $growth_habit = '';
    public string $native_region = '';
    public string $height_min_cm = '';
    public string $height_max_cm = '';
    public string $width_min_cm = '';
    public string $width_max_cm = '';
    public string $bloom_time = '';
    public string $flower_color = '';
    public string $bark_type = '';
    public string $foliage_type = '';
    public bool $is_edible = false;
    public bool $is_toxic = false;
    public string $image_url = '';

    public function mount(Plant $plant): void
    {
        $this->authorize('update', $plant);
        
        $this->plant = $plant;
        $this->name = $plant->name ?? '';
        $this->latin_name = $plant->latin_name ?? '';
        $this->family = $plant->family ?? '';
        $this->genus = $plant->genus ?? '';
        $this->species = $plant->species ?? '';
        $this->common_names = $plant->common_names ?? '';
        $this->description = $plant->description ?? '';
        $this->botanical_description = $plant->botanical_description ?? '';
        $this->category = $plant->category ?? '';
        $this->plant_type = $plant->plant_type ?? '';
        $this->growth_habit = $plant->growth_habit ?? '';
        $this->native_region = $plant->native_region ?? '';
        $this->height_min_cm = $plant->height_min_cm ? (string) $plant->height_min_cm : '';
        $this->height_max_cm = $plant->height_max_cm ? (string) $plant->height_max_cm : '';
        $this->width_min_cm = $plant->width_min_cm ? (string) $plant->width_min_cm : '';
        $this->width_max_cm = $plant->width_max_cm ? (string) $plant->width_max_cm : '';
        $this->bloom_time = $plant->bloom_time ?? '';
        $this->flower_color = $plant->flower_color ?? '';
        $this->bark_type = $plant->bark_type ?? '';
        $this->foliage_type = $plant->foliage_type ?? '';
        $this->is_edible = $plant->is_edible ?? false;
        $this->is_toxic = $plant->is_toxic ?? false;
        $this->image_url = $plant->image_url ?? '';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'family' => 'nullable|string|max:255',
            'genus' => 'nullable|string|max:255',
            'species' => 'nullable|string|max:255',
            'common_names' => 'nullable|string',
            'description' => 'nullable|string',
            'botanical_description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'plant_type' => 'nullable|in:annual,biennial,perennial,shrub,tree,vine,fern,moss,succulent',
            'growth_habit' => 'nullable|in:upright,spreading,climbing,trailing,rosette,clumping',
            'native_region' => 'nullable|string|max:255',
            'height_min_cm' => 'nullable|integer|min:0',
            'height_max_cm' => 'nullable|integer|min:0|gte:height_min_cm',
            'width_min_cm' => 'nullable|integer|min:0',
            'width_max_cm' => 'nullable|integer|min:0|gte:width_min_cm',
            'bloom_time' => 'nullable|string|max:255',
            'flower_color' => 'nullable|string|max:255',
            'bark_type' => 'nullable|string|max:255',
            'foliage_type' => 'nullable|in:deciduous,evergreen,semi-evergreen',
            'is_edible' => 'boolean',
            'is_toxic' => 'boolean',
            'image_url' => 'nullable|url',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        foreach ($validated as $key => $value) {
            if (is_string($value) && $value === '') {
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

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Familie</label>
                                    <input type="text" wire:model="family" placeholder="z.B. Lamiaceae"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                    @error('family') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Gattung</label>
                                    <input type="text" wire:model="genus" placeholder="z.B. Ocimum"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                    @error('genus') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Art</label>
                                    <input type="text" wire:model="species" placeholder="z.B. basilicum"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                    @error('species') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alternative Namen</label>
                                <input type="text" wire:model="common_names" placeholder="z.B. Sweet Basil, Genovese Basil"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                @error('common_names') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Beschreibung</label>
                                    <textarea wire:model="description" rows="4" placeholder="Allgemeine Beschreibung..."
                                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Botanische Beschreibung</label>
                                    <textarea wire:model="botanical_description" rows="4" placeholder="Detaillierte botanische Beschreibung..."
                                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                    @error('botanical_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategorie</label>
                                    <input type="text" wire:model="category" placeholder="z.B. herb, flower, tree"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                    @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pflanzentyp</label>
                                    <select wire:model="plant_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Bitte wählen...</option>
                                        <option value="annual">Einjährig</option>
                                        <option value="biennial">Zweijährig</option>
                                        <option value="perennial">Mehrjährig</option>
                                        <option value="shrub">Strauch</option>
                                        <option value="tree">Baum</option>
                                        <option value="vine">Kletterpflanze</option>
                                        <option value="fern">Farn</option>
                                        <option value="moss">Moos</option>
                                        <option value="succulent">Sukkulente</option>
                                    </select>
                                    @error('plant_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Wuchsform</label>
                                    <select wire:model="growth_habit" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Bitte wählen...</option>
                                        <option value="upright">Aufrecht</option>
                                        <option value="spreading">Ausbreitend</option>
                                        <option value="climbing">Kletternd</option>
                                        <option value="trailing">Hängend</option>
                                        <option value="rosette">Rosettenförmig</option>
                                        <option value="clumping">Horstbildend</option>
                                    </select>
                                    @error('growth_habit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Herkunftsregion</label>
                                    <input type="text" wire:model="native_region" placeholder="z.B. Mediterranean, Asia"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                    @error('native_region') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Laubtyp</label>
                                    <select wire:model="foliage_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Bitte wählen...</option>
                                        <option value="deciduous">Laubabwerfend</option>
                                        <option value="evergreen">Immergrün</option>
                                        <option value="semi-evergreen">Halbimmergrün</option>
                                    </select>
                                    @error('foliage_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Size Ranges -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Höhe (cm)</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="number" wire:model="height_min_cm" placeholder="Min"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                        <input type="number" wire:model="height_max_cm" placeholder="Max"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                    </div>
                                    @error('height_min_cm') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    @error('height_max_cm') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Breite (cm)</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="number" wire:model="width_min_cm" placeholder="Min"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                        <input type="number" wire:model="width_max_cm" placeholder="Max"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                    </div>
                                    @error('width_min_cm') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    @error('width_max_cm') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Blütezeit</label>
                                    <input type="text" wire:model="bloom_time" placeholder="z.B. Frühjahr, Sommer"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                    @error('bloom_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Blütenfarbe</label>
                                    <input type="text" wire:model="flower_color" placeholder="z.B. weiß, rot, mixed"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                    @error('flower_color') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rindentyp</label>
                                    <input type="text" wire:model="bark_type" placeholder="z.B. glatt, rau, schuppig"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                                    @error('bark_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="is_edible" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"/>
                                    <label class="ml-2 text-sm text-gray-700">Essbar</label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="is_toxic" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"/>
                                    <label class="ml-2 text-sm text-gray-700">Giftig</label>
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
                            <a href="{{ route('plants.show', $plant) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Abbrechen
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Pflanze aktualisieren
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
