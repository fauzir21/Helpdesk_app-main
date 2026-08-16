<?php

namespace Database\Factories;

use App\Models\Layanan;
use App\Models\LayananPersyaratan;
use App\Models\Persyaratan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LayananPersyaratanFactory extends Factory
{
    protected $model = LayananPersyaratan::class;

    public function definition(): array
    {
        return [
            'layanan_id' => Layanan::factory(),
            'persyaratan_id' => Persyaratan::factory(),
        ];
    }
}
