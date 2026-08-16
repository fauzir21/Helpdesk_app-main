<?php

use App\Http\Controllers\Auth\ConsentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\HalamanDepanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\LayananKategoriController;
use App\Http\Controllers\LayananPersyaratanController;
use App\Http\Controllers\MasterSurveiKepuasanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PersyaratanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\RiwayatPengerjaanController;
use App\Http\Controllers\SurveiKepuasanMasyarakatController;
use App\Http\Controllers\TimKerjaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HalamanDepanController::class, 'index'])->name('home');
Route::get('/daftar-layanan', [HalamanDepanController::class, 'semuaLayanan'])->name('layanan.semua');
Route::get('/daftar-layanan/{slug}', [HalamanDepanController::class, 'detailLayanan'])->name('layanan.detail');
Route::get('/lacak', [HalamanDepanController::class, 'lacak'])->name('lacak');
Route::post('/lacak', [HalamanDepanController::class, 'prosesLacak'])->name('lacak.proses');

Route::middleware(['auth'])->group(function () {
    Route::get('/consent', [ConsentController::class, 'show'])->name('consent.show');
    Route::post('/consent', [ConsentController::class, 'store'])->name('consent.store');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'role:admin,pegawai,users,helpdesk'])->name('dashboard');
Route::get('/dashboard/log-data', [DashboardController::class, 'logData'])->middleware(['auth', 'role:admin,pegawai,users,helpdesk'])->name('dashboard.log-data');

Route::middleware(['auth', 'role:admin,pegawai,users,helpdesk'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('permohonan/layanan', [PengajuanController::class, 'layanan'])->name('permohonan.layanan');
    Route::get('permohonan/list', [PengajuanController::class, 'list'])->name('permohonan.list');
    Route::get('permohonan/data', [PengajuanController::class, 'data'])->name('permohonan.data');
    Route::get('permohonan/export', [PengajuanController::class, 'export'])->name('permohonan.export');
    Route::get('permohonan/{pengajuan}/report', [PengajuanController::class, 'downloadReport'])->name('permohonan.report');
    Route::get('permohonan/riwayat/{nomor_tiket}', [PengajuanController::class, 'riwayat'])->name('permohonan.riwayat');

    // Riwayat Pengerjaan Pegawai
    Route::get('riwayat-pengerjaan', [RiwayatPengerjaanController::class, 'index'])->name('riwayat-pengerjaan.index');
    Route::get('riwayat-pengerjaan/data', [RiwayatPengerjaanController::class, 'data'])->name('riwayat-pengerjaan.data');

    Route::resource('permohonan', PengajuanController::class);

    Route::post('dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::post('dokumen-tambahan', [DokumenController::class, 'storeTambahan'])->name('dokumen-tambahan.store');
    Route::delete('dokumen-tambahan/{dokumen}', [DokumenController::class, 'destroyTambahan'])->name('dokumen-tambahan.destroy');
    Route::post('permohonan/{pengajuan}/submit', [DokumenController::class, 'submit'])->name('permohonan.submit');
    Route::post('permohonan/{pengajuan}/update-status', [PengajuanController::class, 'updateStatus'])->name('permohonan.update-status');

    Route::get('dokumen/{dokumen}/view', [DokumenController::class, 'view'])->name('dokumen.view');
    Route::get('dokumen-tambahan/{dokumen}/view', [DokumenController::class, 'viewTambahan'])->name('dokumen-tambahan.view');
    Route::get('dokumen-hasil/{dokumen}/view', [PengajuanController::class, 'viewHasil'])->name('permohonan.view-hasil');

    Route::get('permohonan/{pengajuan}/survei', [SurveiKepuasanMasyarakatController::class, 'show'])->name('permohonan.survei');
    Route::post('permohonan/{pengajuan}/survei', [SurveiKepuasanMasyarakatController::class, 'store'])->name('permohonan.survei.store');

    Route::post('dokumen/{dokumen}/review', [DokumenController::class, 'review'])->name('dokumen.review');
    Route::post('dokumen-tambahan/{dokumen}/review', [DokumenController::class, 'reviewTambahan'])->name('dokumen-tambahan.review');

    // Push Subscriptions
    Route::post('subscriptions', [PushSubscriptionController::class, 'update']);
    Route::post('subscriptions/delete', [PushSubscriptionController::class, 'destroy']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('tim-kerja/data', [TimKerjaController::class, 'data'])->name('tim-kerja.data');
    Route::resource('tim-kerja', TimKerjaController::class);

    Route::get('user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('user', UserController::class);

    Route::get('layanan/data', [LayananController::class, 'data'])->name('layanan.data');
    Route::resource('layanan', LayananController::class);

    Route::get('persyaratan/data', [PersyaratanController::class, 'data'])->name('persyaratan.data');
    Route::resource('persyaratan', PersyaratanController::class);

    Route::get('layanan-persyaratan/data', [LayananPersyaratanController::class, 'data'])->name('layanan-persyaratan.data');
    Route::get('layanan-persyaratan/{layanan}/manage', [LayananPersyaratanController::class, 'manage'])->name('layanan-persyaratan.manage');
    Route::post('layanan-persyaratan/{layanan}/add-to-service', [LayananPersyaratanController::class, 'addToService'])->name('layanan-persyaratan.add-to-service');
    Route::delete('layanan-persyaratan/remove-from-service/{pivot_id}', [LayananPersyaratanController::class, 'removeFromService'])->name('layanan-persyaratan.remove-from-service');
    Route::resource('layanan-persyaratan', LayananPersyaratanController::class);

    Route::get('layanan-kategori/{layanan}', [LayananKategoriController::class, 'index'])->name('layanan-kategori.index');
    Route::post('layanan-kategori', [LayananKategoriController::class, 'store'])->name('layanan-kategori.store');
    Route::get('layanan-kategori/show/{kategori}', [LayananKategoriController::class, 'show'])->name('layanan-kategori.show');
    Route::patch('layanan-kategori/{kategori}', [LayananKategoriController::class, 'update'])->name('layanan-kategori.update');
    Route::delete('layanan-kategori/{kategori}', [LayananKategoriController::class, 'destroy'])->name('layanan-kategori.destroy');
    Route::post('layanan-persyaratan/assign-kategori', [LayananKategoriController::class, 'assignPersyaratan'])->name('layanan-kategori.assign-persyaratan');

    Route::get('master-survei/data', [MasterSurveiKepuasanController::class, 'data'])->name('master-survei.data');
    Route::resource('master-survei', MasterSurveiKepuasanController::class);

    Route::get('api/pegawai', [App\Http\Controllers\Api\UserController::class, 'index'])->name('api.pegawai');
});

require __DIR__.'/auth.php';
