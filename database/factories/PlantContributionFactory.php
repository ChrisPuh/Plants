<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlantContribution>
 */
class PlantContributionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'plant_id' => \App\Models\Plant::factory(),
            'field_name' => fake()->randomElement([
                'description', 'botanical_description', 'category', 'plant_type',
                'growth_habit', 'native_region', 'bloom_time', 'flower_color',
            ]),
            'current_value' => fake()->sentence(),
            'proposed_value' => fake()->sentence(),
            'reason' => fake()->sentence(),
            'status' => 'pending',
            'admin_notes' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }
}
