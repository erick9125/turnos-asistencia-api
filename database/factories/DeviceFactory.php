<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'area_id' => \App\Models\Client\Area::factory(),
            'name' => fake()->words(3, true),
            'device_key' => fake()->unique()->bothify('DEV-####'),
            'type' => fake()->randomElement(['clock', 'logical', 'external']),
            'status' => 'active',
        ];
    }
}
