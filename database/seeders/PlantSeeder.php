<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some specific well-known plants with botanical focus
        Plant::create([
            'name' => 'Basilikum',
            'latin_name' => 'Ocimum basilicum',
            'family' => 'Lamiaceae',
            'genus' => 'Ocimum',
            'species' => 'basilicum',
            'common_names' => 'Sweet Basil, Genovese Basil',
            'description' => 'Aromatisches Kraut, das häufig in der italienischen Küche verwendet wird.',
            'botanical_description' => 'Einjährige Pflanze mit eiförmigen, glänzenden Blättern und kleinen weißen Blüten in terminalen Ähren.',
            'category' => 'herb',
            'plant_type' => 'annual',
            'growth_habit' => 'upright',
            'native_region' => 'Asia',
            'height_min_cm' => 20,
            'height_max_cm' => 60,
            'width_min_cm' => 15,
            'width_max_cm' => 30,
            'bloom_time' => 'summer',
            'flower_color' => 'white',
            'leaf_characteristics' => [
                'shape' => 'oval',
                'arrangement' => 'opposite',
                'margin' => 'entire',
                'texture' => 'smooth',
            ],
            'flower_characteristics' => [
                'type' => 'single',
                'arrangement' => 'spike',
                'petals' => 4,
                'fragrant' => true,
            ],
            'fruit_characteristics' => [
                'type' => 'achene',
                'color' => 'black',
                'edible' => false,
            ],
            'foliage_type' => 'deciduous',
            'is_edible' => true,
            'is_toxic' => false,
            'propagation_methods' => ['seed', 'cutting'],
            'images' => [
                'https://example.com/basil-plant.jpg',
                'https://example.com/basil-flowers.jpg',
                'https://example.com/basil-leaves.jpg',
            ],
        ]);

        Plant::create([
            'name' => 'Echter Lavendel',
            'latin_name' => 'Lavandula angustifolia',
            'family' => 'Lamiaceae',
            'genus' => 'Lavandula',
            'species' => 'angustifolia',
            'common_names' => 'English Lavender, True Lavender',
            'description' => 'Duftende Staude mit violetten Blüten, bekannt für ihren entspannenden Duft.',
            'botanical_description' => 'Mehrjähriger Halbstrauch mit linearen, graugrünen Blättern und violetten Blüten in endständigen Ähren.',
            'category' => 'herb',
            'plant_type' => 'perennial',
            'growth_habit' => 'clumping',
            'native_region' => 'Mediterranean',
            'height_min_cm' => 30,
            'height_max_cm' => 90,
            'width_min_cm' => 30,
            'width_max_cm' => 90,
            'bloom_time' => 'summer',
            'flower_color' => 'purple',
            'leaf_characteristics' => [
                'shape' => 'lance',
                'arrangement' => 'opposite',
                'margin' => 'entire',
                'texture' => 'hairy',
            ],
            'flower_characteristics' => [
                'type' => 'single',
                'arrangement' => 'spike',
                'petals' => 5,
                'fragrant' => true,
            ],
            'fruit_characteristics' => [
                'type' => 'achene',
                'color' => 'brown',
                'edible' => false,
            ],
            'foliage_type' => 'evergreen',
            'is_edible' => true,
            'is_toxic' => false,
            'propagation_methods' => ['cutting', 'division'],
            'images' => [
                'https://example.com/lavender-plant.jpg',
                'https://example.com/lavender-flowers.jpg',
            ],
        ]);

        Plant::create([
            'name' => 'Tomate',
            'latin_name' => 'Solanum lycopersicum',
            'family' => 'Solanaceae',
            'genus' => 'Solanum',
            'species' => 'lycopersicum',
            'common_names' => 'Tomato, Love Apple',
            'description' => 'Beliebtes Gemüse mit roten, saftigen Früchten.',
            'botanical_description' => 'Einjährige Pflanze mit zusammengesetzten Blättern und gelben Blüten, die rote Beerenfrüchte entwickeln.',
            'category' => 'vegetable',
            'plant_type' => 'annual',
            'growth_habit' => 'climbing',
            'native_region' => 'South America',
            'height_min_cm' => 50,
            'height_max_cm' => 200,
            'width_min_cm' => 30,
            'width_max_cm' => 60,
            'bloom_time' => 'summer',
            'flower_color' => 'yellow',
            'leaf_characteristics' => [
                'shape' => 'compound',
                'arrangement' => 'alternate',
                'margin' => 'serrated',
                'texture' => 'hairy',
            ],
            'flower_characteristics' => [
                'type' => 'single',
                'arrangement' => 'cluster',
                'petals' => 5,
                'fragrant' => false,
            ],
            'fruit_characteristics' => [
                'type' => 'berry',
                'color' => 'red',
                'edible' => true,
            ],
            'foliage_type' => 'deciduous',
            'is_edible' => true,
            'is_toxic' => false,
            'propagation_methods' => ['seed'],
            'images' => [
                'https://example.com/tomato-plant.jpg',
                'https://example.com/tomato-fruits.jpg',
                'https://example.com/tomato-flowers.jpg',
            ],
        ]);

        Plant::create([
            'name' => 'Rotbuche',
            'latin_name' => 'Fagus sylvatica',
            'family' => 'Fagaceae',
            'genus' => 'Fagus',
            'species' => 'sylvatica',
            'common_names' => 'European Beech, Common Beech',
            'description' => 'Majestätischer Laubbaum mit glatter grauer Rinde und bronzefarbener Herbstfärbung.',
            'botanical_description' => 'Großer Laubbaum mit ovalen, wellrandigen Blättern und charakteristischer glatter Rinde.',
            'category' => 'tree',
            'plant_type' => 'tree',
            'growth_habit' => 'upright',
            'native_region' => 'Europe',
            'height_min_cm' => 2000,
            'height_max_cm' => 4000,
            'width_min_cm' => 1500,
            'width_max_cm' => 2500,
            'bloom_time' => 'spring',
            'flower_color' => 'green',
            'leaf_characteristics' => [
                'shape' => 'oval',
                'arrangement' => 'alternate',
                'margin' => 'serrated',
                'texture' => 'smooth',
            ],
            'flower_characteristics' => [
                'type' => 'single',
                'arrangement' => 'cluster',
                'petals' => 0,
                'fragrant' => false,
            ],
            'fruit_characteristics' => [
                'type' => 'achene',
                'color' => 'brown',
                'edible' => true,
            ],
            'bark_type' => 'smooth',
            'foliage_type' => 'deciduous',
            'is_edible' => true,
            'is_toxic' => false,
            'propagation_methods' => ['seed'],
            'images' => [
                'https://example.com/beech-tree.jpg',
                'https://example.com/beech-leaves.jpg',
                'https://example.com/beech-bark.jpg',
            ],
        ]);

        // Create random plants
        Plant::factory(20)->create();
    }
}
