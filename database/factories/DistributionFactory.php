<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mustahik;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distribution>
 */
class DistributionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'distribution_code' => 'DIST-' . date('Y') . '-' . fake()->unique()->numberBetween(100, 999),
            'mustahik_id' => Mustahik::factory(),
            'amount' => fake()->randomElement([50000, 100000, 150000, 200000, 250000]),
            'distribution_type' => fake()->randomElement(['cash', 'goods', 'voucher', 'service']),
            'goods_description' => fake()->optional()->sentence(),
            'distribution_date' => fake()->date(),
            'notes' => fake()->optional()->sentence(),
            'program_name' => fake()->optional()->sentence(),
            'distributed_by' => User::factory(),
            'location' => fake()->optional()->city(),
            'is_received' => fake()->boolean(),
            'received_date' => fake()->optional()->date(),
            'received_by_name' => fake()->optional()->name(),
            'received_notes' => fake()->optional()->sentence(),
        ];
    }
}
