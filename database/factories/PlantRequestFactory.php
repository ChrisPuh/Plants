<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlantRequest>
 */
class PlantRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plantNames = [
            'Basilikum', 'Rosmarin', 'Thymian', 'Oregano', 'Salbei', 'Lavendel',
            'Rose', 'Tulpe', 'Sonnenblume', 'Tomate', 'Paprika', 'Gurke',
        ];

        return [
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->randomElement($plantNames),
            'latin_name' => fake()->optional()->words(2, true),
            'family' => fake()->optional()->word(),
            'description' => fake()->optional()->sentence(),
            'reason' => fake()->sentence(),
            'proposed_data' => fake()->optional()->randomElement([
                ['category' => 'herb', 'plant_type' => 'annual'],
                ['category' => 'flower', 'plant_type' => 'perennial'],
                ['category' => 'vegetable', 'plant_type' => 'annual'],
            ]),
            'status' => 'pending',
            'admin_notes' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'created_plant_id' => null,
        ];
    }
}
