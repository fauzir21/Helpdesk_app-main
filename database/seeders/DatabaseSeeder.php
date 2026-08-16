<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\LayananPersyaratan;
use App\Models\Pengajuan;
use App\Models\Persyaratan;
use App\Models\TimKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'tipe' => 'admin',
        ]);

        // Create Helpdesk
        User::factory()->create([
            'name' => 'Layanan User',
            'email' => 'helpdesk@example.com',
            'password' => Hash::make('password'),
            'tipe' => 'helpdesk',
        ]);

        // Create Pegawai
        $pegawai1 = User::factory()->create([
            'name' => 'Pegawai 1',
            'email' => 'pegawai1@example.com',
            'password' => Hash::make('password'),
            'tipe' => 'pegawai',
        ]);

        $pegawai2 = User::factory()->create([
            'name' => 'Pegawai 2',
            'email' => 'pegawai2@example.com',
            'password' => Hash::make('password'),
            'tipe' => 'pegawai',
        ]);

        // Create Regular Users
        $user1 = User::factory()->create([
            'name' => 'User Umum',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'tipe' => 'users',
            'kategori_user' => 'umum',
        ]);

        $user2 = User::factory()->create([
            'name' => 'User Pemerintah',
            'email' => 'pemda@example.com',
            'password' => Hash::make('password'),
            'tipe' => 'users',
            'kategori_user' => 'pemerintah',
        ]);

        // Create Tim Kerja
        $tim1 = TimKerja::factory()->create(['nama_timkerja' => 'Tim Infrastruktur']);
        $tim2 = TimKerja::factory()->create(['nama_timkerja' => 'Tim Aplikasi']);

        // Attach Pegawai to Tim Kerja
        $pegawai1->timKerjas()->attach($tim1->id_tim_kerja);
        $pegawai2->timKerjas()->attach($tim2->id_tim_kerja);

        // Create Persyaratan
        $req1 = Persyaratan::factory()->create(['nama_persyaratan' => 'KTP / Identitas', 'tipe' => 'file']);
        $req2 = Persyaratan::factory()->create(['nama_persyaratan' => 'Surat Permohonan', 'tipe' => 'file']);
        $req3 = Persyaratan::factory()->create(['nama_persyaratan' => 'Alasan Pengajuan', 'tipe' => 'text']);

        // Create Layanan
        $layanan1 = Layanan::factory()->create([
            'nama_layanan' => 'Peminjaman Server',
            'tim_kerja_id' => $tim1->id_tim_kerja,
            'user_category' => 'pemerintah',
            'durasi_hari' => 3,
        ]);

        $layanan2 = Layanan::factory()->create([
            'nama_layanan' => 'Pembuatan Email Dinas',
            'tim_kerja_id' => $tim2->id_tim_kerja,
            'user_category' => 'semua',
            'durasi_hari' => 1,
        ]);

        // Attach Persyaratan to Layanan
        LayananPersyaratan::create(['layanan_id' => $layanan1->id, 'persyaratan_id' => $req1->id]);
        LayananPersyaratan::create(['layanan_id' => $layanan1->id, 'persyaratan_id' => $req2->id]);
        LayananPersyaratan::create(['layanan_id' => $layanan1->id, 'persyaratan_id' => $req3->id]);

        LayananPersyaratan::create(['layanan_id' => $layanan2->id, 'persyaratan_id' => $req1->id]);
        LayananPersyaratan::create(['layanan_id' => $layanan2->id, 'persyaratan_id' => $req3->id]);

        // Create some Pengajuan
        Pengajuan::factory()->count(5)->create([
            'id_user' => $user1->id,
            'id_layanan' => $layanan2->id,
            'status_pengajuan' => 'DIPROSES',
        ]);

        Pengajuan::factory()->count(3)->create([
            'id_user' => $user2->id,
            'id_layanan' => $layanan1->id,
            'status_pengajuan' => 'MENUNGGU_DIPROSES',
        ]);
    }
}
