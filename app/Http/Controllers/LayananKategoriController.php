<?php

namespace App\Http\Controllers;

use App\Http\Requests\LayananKategoriStoreRequest;
use App\Http\Requests\LayananKategoriUpdateRequest;
use App\Models\Layanan;
use App\Models\LayananKategori;
use App\Models\LayananPersyaratan;
use App\Traits\LogsActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LayananKategoriController extends Controller
{
    use LogsActivity;

    public function index(Layanan $layanan): JsonResponse
    {
        $kategori = $layanan->kategori()->with('users', 'layananPersyaratan.persyaratan')->get();

        return response()->json($kategori);
    }

    public function store(LayananKategoriStoreRequest $request): JsonResponse
    {
        $kategori = LayananKategori::create($request->validated());

        if ($request->has('user_ids')) {
            $kategori->users()->sync($request->user_ids);
        }

        $this->logActivity(
            'Tambah Kategori Layanan',
            "Menambahkan kategori: {$kategori->nama_kategori} pada layanan ID: {$kategori->layanan_id}",
            ['kategori_id' => $kategori->id, 'nama_kategori' => $kategori->nama_kategori]
        );

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => $kategori->load('users'),
        ]);
    }

    public function show(LayananKategori $kategori): JsonResponse
    {
        return response()->json($kategori->load('users', 'layananPersyaratan'));
    }

    public function update(LayananKategoriUpdateRequest $request, LayananKategori $kategori): JsonResponse
    {
        $kategori->update($request->validated());

        if ($request->has('user_ids')) {
            $kategori->users()->sync($request->user_ids);
        }

        $this->logActivity(
            'Update Kategori Layanan',
            "Memperbarui kategori: {$kategori->nama_kategori}",
            ['kategori_id' => $kategori->id, 'nama_kategori' => $kategori->nama_kategori]
        );

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data' => $kategori->load('users'),
        ]);
    }

    public function destroy(LayananKategori $kategori): JsonResponse
    {
        $namaKategori = $kategori->nama_kategori;
        $kategori->delete();

        $this->logActivity(
            'Hapus Kategori Layanan',
            "Menghapus kategori: {$namaKategori}",
            ['nama_kategori' => $namaKategori]
        );

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }

    public function assignPersyaratan(Request $request): JsonResponse
    {
        $request->validate([
            'layanan_persyaratan_ids' => 'required|array',
            'layanan_persyaratan_ids.*' => 'exists:tb_layanan_persyaratan,id',
            'layanan_kategori_id' => 'nullable',
        ]);

        LayananPersyaratan::whereIn('id', $request->layanan_persyaratan_ids)
            ->update(['layanan_kategori_id' => $request->layanan_kategori_id ?: null]);

        return response()->json([
            'success' => true,
            'message' => 'Status kategori persyaratan berhasil diperbarui.',
        ]);
    }
}
