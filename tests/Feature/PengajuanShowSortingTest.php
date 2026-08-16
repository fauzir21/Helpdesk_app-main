<?php

use App\Models\Layanan;
use App\Models\LayananPersyaratan;
use App\Models\Pengajuan;
use App\Models\Persyaratan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('sorts persyaratan by text type first then file type in show page', function () {
    $user = User::factory()->create(['tipe' => 'users', 'kategori_user' => 'umum']);

    $layanan = Layanan::create([
        'nama_layanan' => 'Layanan Tes',
        'slug' => 'layanan-tes',
        'deskripsi' => 'Deskripsi layanan tes',
        'status' => 'aktif',
        'durasi_hari' => 5,
        'user_category' => 'semua',
    ]);

    // Create 'file' type first
    $persyaratanFile = Persyaratan::create([
        'nama_persyaratan' => 'Persyaratan File',
        'tipe' => 'file',
        'status' => 'aktif',
    ]);

    // Create 'text' type second
    $persyaratanText = Persyaratan::create([
        'nama_persyaratan' => 'Persyaratan Text',
        'tipe' => 'text',
        'status' => 'aktif',
    ]);

    // Connect them to layanan
    $lpFile = LayananPersyaratan::create([
        'layanan_id' => $layanan->id,
        'persyaratan_id' => $persyaratanFile->id,
    ]);

    $lpText = LayananPersyaratan::create([
        'layanan_id' => $layanan->id,
        'persyaratan_id' => $persyaratanText->id,
    ]);

    $pengajuan = Pengajuan::create([
        'id_layanan' => $layanan->id,
        'id_user' => $user->id,
        'status_pengajuan' => 'DRAFT',
        'nomor_tiket' => 'TK-TEST-123',
        'tanggal_pengajuan' => now(),
    ]);

    $response = $this->actingAs($user)
        ->get(route('permohonan.show', $pengajuan->id));

    $response->assertSuccessful();

    $data = $response->getOriginalContent()->getData();
    $sortedLp = $data['pengajuan']->layanan->layananPersyaratan;

    expect($sortedLp->first()->id)->toBe($lpText->id);
    expect($sortedLp->last()->id)->toBe($lpFile->id);
    expect($sortedLp->first()->persyaratan->tipe)->toBe('text');
    expect($sortedLp->last()->persyaratan->tipe)->toBe('file');
});
