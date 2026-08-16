<?php

namespace Database\Factories;

use App\Models\Persyaratan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersyaratanFactory extends Factory
{
    protected $model = Persyaratan::class;

    public function definition(): array
    {
        return [
            'nama_persyaratan' => $this->faker->words(3, true),
            'deskripsi' => $this->faker->sentence(),
            'tipe' => $this->faker->randomElement(['file', 'text']),
            'wajib' => $this->faker->boolean(),
            'status' => 'aktif',
        ];
    }
}
