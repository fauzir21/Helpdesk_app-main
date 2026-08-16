<?php

use App\Models\Dokumen;
use App\Models\Layanan;
use App\Models\Pengajuan;
use App\Models\TimKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pegawai can give review and it creates a history of catatan', function () {
    $this->withoutExceptionHandling();
    $tim = TimKerja::create(['nama_timkerja' => 'Tim Test']);
    
    $pegawai1 = User::create([
        'name' => 'Pegawai 1',
        'email' => 'p1@test.com',
        'password' => bcrypt('password'),
        'tipe' => 'pegawai'
    ]);
    $pegawai1->timKerjas()->attach($tim->id_tim_kerja);
    
    $pegawai2 = User::create([
        'name' => 'Pegawai 2',
        'email' => 'p2@test.com',
        'password' => bcrypt('password'),
        'tipe' => 'pegawai'
    ]);
    $pegawai2->timKerjas()->attach($tim->id_tim_kerja);

    $user = User::create([
        'name' => 'User Test',
        'email' => 'u@test.com',
        'password' => bcrypt('password'),
        'tipe' => 'users'
    ]);

    $layanan = Layanan::create([
        'nama_layanan' => 'Layanan Test',
        'tim_kerja_id' => $tim->id_tim_kerja,
        'status' => 'aktif',
        'durasi_hari' => 1
    ]);
    
    $pengajuan = Pengajuan::create([
        'id_layanan' => $layanan->id,
        'id_user' => $user->id,
        'status_pengajuan' => 'DIPROSES',
        'nomor_tiket' => 'TEST-001',
        'tanggal_pengajuan' => now()
    ]);

    $dokumen = Dokumen::create([
        'id_pengajuan' => $pengajuan->id,
        'id_layanan_persyaratan' => 1, // dummy
        'status' => 'BELUM_SELESAI'
    ]);

    // Pegawai 1 review
    $this->withoutMiddleware()
        ->actingAs($pegawai1)
        ->post("/dokumen/{$dokumen->id}/review", [
            'status' => 'PERBAIKAN',
            'catatan' => 'Catatan pertama dari pegawai 1'
        ])
        ->assertSuccessful();

    $this->assertDatabaseHas('tb_catatan', [
        'id_dokumen' => $dokumen->id,
        'catatan' => 'Catatan pertama dari pegawai 1',
        'user_id' => $pegawai1->id
    ]);

    // Pegawai 2 review
    $this->withoutMiddleware()
        ->actingAs($pegawai2)
        ->post("/dokumen/{$dokumen->id}/review", [
            'status' => 'PERBAIKAN',
            'catatan' => 'Catatan kedua dari pegawai 2'
        ])
        ->assertSuccessful();

    $this->assertDatabaseHas('tb_catatan', [
        'id_dokumen' => $dokumen->id,
        'catatan' => 'Catatan kedua dari pegawai 2',
        'user_id' => $pegawai2->id
    ]);

    // Check count of catatans
    expect($dokumen->catatans()->count())->toBe(2);
    
    // Check latestOfMany relationship
    expect($dokumen->refresh()->catatan->catatan)->toBe('Catatan kedua dari pegawai 2');
});

test('history button is visible for pegawai and contains data', function () {
    $tim = TimKerja::factory()->create();
    $pegawai = User::factory()->pegawai()->create();
    $pegawai->timKerjas()->attach($tim->id_tim_kerja);

    $user = User::factory()->users()->create();
    $layanan = Layanan::factory()->create(['tim_kerja_id' => $tim->id_tim_kerja]);
    
    $pengajuan = Pengajuan::factory()->create([
        'id_layanan' => $layanan->id,
        'id_user' => $user->id,
        'status_pengajuan' => 'DIPROSES'
    ]);

    $dokumen = Dokumen::factory()->create([
        'id_pengajuan' => $pengajuan->id,
        'status' => 'PERBAIKAN'
    ]);
    
    // Seed a catatan
    $dokumen->catatans()->create([
        'catatan' => 'Test History',
        'user_id' => $pegawai->id
    ]);

    $response = $this->actingAs($pegawai)
        ->get(route('permohonan.show', $pengajuan->id));

    $response->assertStatus(200)
        ->assertSee('Riwayat')
        ->assertSee('Test History');
});
