<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AgeGroup>
 */
class AgeGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'from_age' => fake()->numberBetween(3,10),
            'to_age' => fake()->numberBetween(11,18),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
