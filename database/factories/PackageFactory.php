<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'price' => fake()->numberBetween(100,2000),
            'patiant_count' => fake()->numberBetween(1,20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
