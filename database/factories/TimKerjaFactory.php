<?php

namespace Database\Factories;

use App\Models\TimKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimKerja>
 */
class TimKerjaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_timkerja' => fake()->company(),
        ];
    }
}
