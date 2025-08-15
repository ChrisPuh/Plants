<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPath = database_path('seeders/data');

        // Alle JSON-Dateien im Verzeichnis laden
        $files = File::files($dataPath);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            $json = File::get($file->getRealPath());
            $plants = json_decode($json, true);

            if (!is_array($plants)) {
                $this->command->warn("⚠️ Datei {$file->getFilename()} enthält keine gültigen Pflanzendaten.");
                continue;
            }

            foreach ($plants as $plantData) {
                Plant::create([
                    'name' => $plantData['name'] ?? null,
                    'latin_name' => $plantData['latin_name'] ?? null,
                    'description' => $plantData['description'] ?? null,
                    'category' => $plantData['category'] ?? null,
                    'plant_type' => $plantData['plant_type'] ?? null,
                    'image_url' => $plantData['image_url'] ?? null,
                ]);
            }

            $this->command->info("✅ Datei {$file->getFilename()} erfolgreich gesendet.");
        }
    }
}
