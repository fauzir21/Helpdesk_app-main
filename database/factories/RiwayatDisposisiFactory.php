<?php

namespace Database\Factories;

use App\Models\Pengajuan;
use App\Models\RiwayatDisposisi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiwayatDisposisiFactory extends Factory
{
    protected $model = RiwayatDisposisi::class;

    public function definition(): array
    {
        return [
            'id_pengajuan' => Pengajuan::factory(),
            'nomor_tiket' => fn (array $attributes) => Pengajuan::find($attributes['id_pengajuan'])->nomor_tiket,
            'status' => 'DRAFT',
            'tanggal_disposisi' => now(),
            'keterangan' => 'Status diperbarui.',
            'id_user' => User::factory(),
        ];
    }
}
