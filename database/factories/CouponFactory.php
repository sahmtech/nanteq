<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->numberBetween(0, 99999),
            'start' => now(),
            'end' => now()->addMonth(),
            'name' => fake()->unique()->name(),
            'uses_limit' => fake()->numberBetween(0, 100),
            'type' => fake()->randomElement(['amount', 'percentage']),
            'value' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
