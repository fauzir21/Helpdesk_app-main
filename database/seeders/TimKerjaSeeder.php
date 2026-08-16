<?php

namespace Database\Seeders;

use App\Models\TimKerja;
use Illuminate\Database\Seeder;

class TimKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TimKerja::factory(10)->create();
    }
}
