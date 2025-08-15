<?php

namespace Database\Factories;

use App\Enums\Plant\PlantCategoryEnum;
use App\Enums\Plant\PlantTypeEnum;
use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plant>
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
        $categories = PlantCategoryEnum::values();
        $plantTypes = PlantTypeEnum::values();
        $genus = fake()->firstName();
        $species = fake()->lastName();
        $heightMin = fake()->numberBetween(10, 100);
        $heightMax = fake()->numberBetween($heightMin + 10, $heightMin + 200);
        $widthMin = fake()->numberBetween(10, 80);
        $widthMax = fake()->numberBetween($widthMin + 5, $widthMin + 100);

        return [
            'name' => fake()->words(2, true),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement($categories),
            'plant_type' => fake()->randomElement($plantTypes),
            'image_url' => fake()->imageUrl(400, 300, 'plants'),
        ];
    }
}
