<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'holding_id' => \App\Models\Client\Holding::factory(),
            'name' => fake()->company(),
            'rut' => fake()->unique()->numerify('########-#'),
        ];
    }
}
