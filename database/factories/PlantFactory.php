<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plant>
 */
class PlantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['herb', 'flower', 'vegetable', 'tree', 'shrub', 'houseplant', 'wildflower', 'grass', 'fern'];
        $families = ['Rosaceae', 'Lamiaceae', 'Asteraceae', 'Fabaceae', 'Solanaceae', 'Apiaceae', 'Brassicaceae', 'Poaceae'];
        $plantTypes = ['annual', 'biennial', 'perennial', 'shrub', 'tree', 'vine', 'fern', 'moss', 'succulent'];
        $growthHabits = ['upright', 'spreading', 'climbing', 'trailing', 'rosette', 'clumping'];
        $foliageTypes = ['deciduous', 'evergreen', 'semi-evergreen'];
        $bloomTimes = ['spring', 'summer', 'fall', 'spring-summer', 'summer-fall', 'year-round'];
        $flowerColors = ['white', 'red', 'pink', 'purple', 'blue', 'yellow', 'orange', 'green', 'mixed'];
        $nativeRegions = ['Europe', 'North America', 'South America', 'Asia', 'Africa', 'Australia', 'Mediterranean'];
        $propagationMethods = [
            ['seed'],
            ['cutting'],
            ['division'],
            ['layering'],
            ['seed', 'cutting'],
            ['cutting', 'division'],
            ['seed', 'cutting', 'division'],
        ];

        $genus = fake()->firstName();
        $species = fake()->lastName();
        $heightMin = fake()->numberBetween(10, 100);
        $heightMax = fake()->numberBetween($heightMin + 10, $heightMin + 200);
        $widthMin = fake()->numberBetween(10, 80);
        $widthMax = fake()->numberBetween($widthMin + 5, $widthMin + 100);

        return [
            'name' => fake()->words(2, true),
            'latin_name' => $genus.' '.$species,
            'family' => fake()->randomElement($families),
            'genus' => $genus,
            'species' => $species,
            'common_names' => implode(', ', fake()->words(fake()->numberBetween(1, 3))),
            'description' => fake()->paragraph(),
            'botanical_description' => fake()->paragraph(2),
            'category' => fake()->randomElement($categories),
            'plant_type' => fake()->randomElement($plantTypes),
            'growth_habit' => fake()->randomElement($growthHabits),
            'native_region' => fake()->randomElement($nativeRegions),
            'height_min_cm' => $heightMin,
            'height_max_cm' => $heightMax,
            'width_min_cm' => $widthMin,
            'width_max_cm' => $widthMax,
            'bloom_time' => fake()->randomElement($bloomTimes),
            'flower_color' => fake()->randomElement($flowerColors),
            'leaf_characteristics' => [
                'shape' => fake()->randomElement(['oval', 'lance', 'heart', 'palmate', 'compound']),
                'arrangement' => fake()->randomElement(['alternate', 'opposite', 'whorled']),
                'margin' => fake()->randomElement(['entire', 'serrated', 'lobed']),
                'texture' => fake()->randomElement(['smooth', 'hairy', 'waxy', 'rough']),
            ],
            'flower_characteristics' => [
                'type' => fake()->randomElement(['single', 'double', 'semi-double']),
                'arrangement' => fake()->randomElement(['solitary', 'cluster', 'spike', 'panicle']),
                'petals' => fake()->numberBetween(3, 12),
                'fragrant' => fake()->boolean(),
            ],
            'fruit_characteristics' => [
                'type' => fake()->randomElement(['berry', 'drupe', 'capsule', 'achene', 'pod']),
                'color' => fake()->randomElement(['red', 'blue', 'black', 'brown', 'green']),
                'edible' => fake()->boolean(30),
            ],
            'bark_type' => fake()->randomElement(['smooth', 'rough', 'furrowed', 'peeling', 'scaly']),
            'foliage_type' => fake()->randomElement($foliageTypes),
            'is_edible' => fake()->boolean(20),
            'is_toxic' => fake()->boolean(10),
            'propagation_methods' => fake()->randomElement($propagationMethods),
            'image_url' => fake()->imageUrl(400, 300, 'plants'),
            'images' => [
                fake()->imageUrl(400, 300, 'plants', true, 'flower'),
                fake()->imageUrl(400, 300, 'plants', true, 'leaf'),
                fake()->imageUrl(400, 300, 'plants', true, 'habit'),
            ],
        ];
    }
}
