<?php

namespace Database\Factories;

use App\Models\Layanan;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengajuanFactory extends Factory
{
    protected $model = Pengajuan::class;

    public function definition(): array
    {
        return [
            'id_layanan' => Layanan::factory(),
            'id_user' => User::factory()->users(),
            'status_pengajuan' => $this->faker->randomElement([
                'DRAFT',
                'MENUNGGU_DIPROSES',
                'DIPROSES',
                'DITOLAK',
                'PERBAIKAN',
                'SELESAI',
                'SELESAI_PEMERIKSAAN',
            ]),
            'nomor_tiket' => 'TK-'.date('Ymd').'-'.strtoupper($this->faker->unique()->lexify('?????')),
            'tanggal_pengajuan' => now(),
            'deadline' => now()->addDays(7),
        ];
    }
}
