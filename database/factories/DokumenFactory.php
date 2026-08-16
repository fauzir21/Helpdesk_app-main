<?php

namespace Database\Factories;

use App\Models\Dokumen;
use App\Models\LayananPersyaratan;
use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Factories\Factory;

class DokumenFactory extends Factory
{
    protected $model = Dokumen::class;

    public function definition(): array
    {
        return [
            'id_pengajuan' => Pengajuan::factory(),
            'id_layanan_persyaratan' => LayananPersyaratan::factory(),
            'file' => null,
            'text' => $this->faker->sentence(),
            'status' => 'BELUM_SELESAI',
        ];
    }
}
