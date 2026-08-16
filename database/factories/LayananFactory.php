<?php

namespace Database\Factories;

use App\Models\Layanan;
use App\Models\TimKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

class LayananFactory extends Factory
{
    protected $model = Layanan::class;

    public function definition(): array
    {
        return [
            'tim_kerja_id' => TimKerja::factory(),
            'nama_layanan' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph(),
            'status' => 'aktif',
            'durasi_hari' => $this->faker->numberBetween(1, 14),
            'user_category' => $this->faker->randomElement(['umum', 'pemerintah', 'semua']),
        ];
    }
}
