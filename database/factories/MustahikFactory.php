<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mustahik>
 */
class MustahikFactory extends Factory
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
            'nik' => fake()->unique()->numerify('################'),
            'gender' => fake()->randomElement(['male', 'female']),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'province' => fake()->state(),
            'phone' => fake()->phoneNumber(),
            'date_of_birth' => fake()->date(),
            'category' => fake()->randomElement(['fakir', 'miskin', 'amil', 'muallaf', 'riqab', 'gharim', 'fisabilillah', 'ibnu_sabil']),
            'category_description' => fake()->sentence(),
            'family_status' => fake()->randomElement(['single', 'married', 'widow', 'orphan']),
            'family_members' => fake()->numberBetween(1, 10),
            'monthly_income' => fake()->numberBetween(500000, 5000000),
            'income_source' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
