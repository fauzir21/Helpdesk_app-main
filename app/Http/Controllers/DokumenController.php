<?php

namespace App\Http\Controllers;

use App\Http\Requests\DokumenStoreRequest;
use App\Http\Requests\DokumenTambahanStoreRequest;
use App\Models\Catatan;
use App\Models\Dokumen;
use App\Models\DokumenTambahan;
use App\Models\Pengajuan;
use App\Models\RiwayatDisposisi;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    use LogsActivity;

    public function store(DokumenStoreRequest $request)
    {
        $validated = $request->validated();
        $pengajuan = Pengajuan::findOrFail($validated['id_pengajuan']);

        if ($pengajuan->status_pengajuan !== 'DRAFT' && $pengajuan->status_pengajuan !== 'PERBAIKAN' && $pengajuan->status_pengajuan !== 'DITOLAK') {
            return response()->json(['message' => 'Status pengajuan tidak memungkinkan untuk perubahan.'], 403);
        }

        $dokumen = Dokumen::updateOrCreate(
            [
                'id_pengajuan' => $validated['id_pengajuan'],
                'id_layanan_persyaratan' => $validated['id_layanan_persyaratan'],
            ],
            [
                'text' => $validated['text'] ?? null,
                'status' => 'DRAFT',
            ]
        );

        // Security check: cannot modify if already SELESAI
        if ($dokumen->wasRecentlyCreated === false && $dokumen->status === 'SELESAI') {
            return response()->json(['message' => 'Dokumen yang sudah selesai diperiksa tidak dapat diubah.'], 403);
        }

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($dokumen->file) {
                Storage::disk('local')->delete($dokumen->file);
            }
            $dokumen->file = $request->file('file')->store('dokumen_pengajuan/' . $validated['id_pengajuan'], 'local');
            $dokumen->save();
        }

        // log activity
        $namaDokumen = $dokumen->layananPersyaratan->persyaratan->nama_persyaratan;
        $this->logActivity(
            'Upload Dokumen',
            "Mengunggah dokumen {$namaDokumen} pada pengajuan {$pengajuan->nomor_tiket}",
            ['nomor_tiket' => $pengajuan->nomor_tiket, 'nama_dokumen' => $namaDokumen]
        );

        // Record to tb_catatan for history tracking
        if (Auth::user()->tipe === 'users') {
            Catatan::create([
                'id_dokumen' => $dokumen->id,
                'catatan' => 'Pemohon mengunggah berkas: ' . $namaDokumen,
                'user_id' => Auth::id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Persyaratan berhasil disimpan.',
            'data' => $dokumen,
        ]);
    }

    public function storeTambahan(DokumenTambahanStoreRequest $request)
    {
        $validated = $request->validated();
        $pengajuan = Pengajuan::findOrFail($validated['id_pengajuan']);

        if ($pengajuan->status_pengajuan !== 'DRAFT' && $pengajuan->status_pengajuan !== 'PERBAIKAN' && $pengajuan->status_pengajuan !== 'DITOLAK') {
            return response()->json(['message' => 'Status pengajuan tidak memungkinkan untuk perubahan.'], 403);
        }

        $file = $request->file('file')->store('dokumen_tambahan/' . $validated['id_pengajuan'], 'local');

        $dokumen = DokumenTambahan::create([
            'id_pengajuan' => $validated['id_pengajuan'],
            'nama_dokumen' => $validated['nama_dokumen'],
            'file' => $file,
            'status' => 'DRAFT',
        ]);

        // log activity
        $this->logActivity(
            'Tambah Dokumen',
            "Menambahkan dokumen {$dokumen->nama_dokumen} pada pengajuan {$pengajuan->nomor_tiket}",
            ['nomor_tiket' => $pengajuan->nomor_tiket, 'nama_dokumen' => $dokumen->nama_dokumen]
        );

        // Record to tb_catatan for history tracking
        if (Auth::user()->tipe === 'users') {
            Catatan::create([
                'id_dokumen_tambahan' => $dokumen->id,
                'catatan' => 'Pemohon mengunggah dokumen tambahan: ' . $dokumen->nama_dokumen,
                'user_id' => Auth::id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Dokumen tambahan berhasil diunggah.',
            'data' => $dokumen,
        ]);
    }

    public function destroyTambahan(DokumenTambahan $dokumen)
    {
        $pengajuan = $dokumen->pengajuan;
        if ($pengajuan->status_pengajuan !== 'DRAFT' && $pengajuan->status_pengajuan !== 'PERBAIKAN' && $pengajuan->status_pengajuan !== 'DITOLAK') {
            return response()->json(['message' => 'Status pengajuan tidak memungkinkan untuk perubahan.'], 403);
        }

        // Security check: cannot delete if already SELESAI
        if ($dokumen->status === 'SELESAI') {
            return response()->json(['message' => 'Dokumen yang sudah selesai diperiksa tidak dapat dihapus.'], 403);
        }

        if ($dokumen->file) {
            Storage::disk('local')->delete($dokumen->file);
        }
        $dokumen->delete();

        // log activity
        $this->logActivity(
            'Hapus Dokumen',
            "Menghapus dokumen {$dokumen->nama_dokumen} pada pengajuan {$pengajuan->nomor_tiket}",
            ['nomor_tiket' => $pengajuan->nomor_tiket, 'nama_dokumen' => $dokumen->nama_dokumen]
        );

        return response()->json([
            'success' => true,
            'message' => 'Dokumen tambahan berhasil dihapus.',
        ]);
    }

    public function view(Dokumen $dokumen)
    {
        $pengajuan = $dokumen->pengajuan;

        // Authorization: owner or admin/pegawai
        if (Auth::user()->tipe !== 'admin' && Auth::user()->tipe !== 'pegawai' && Auth::id() !== $pengajuan->id_user && Auth::user()->tipe !== 'helpdesk') {
            abort(403);
        }

        if (!Storage::disk('local')->exists($dokumen->file)) {
            abort(404);
        }

        return Storage::disk('local')->response($dokumen->file);
    }

    public function viewTambahan(DokumenTambahan $dokumen)
    {
        $pengajuan = $dokumen->pengajuan;

        // Authorization: owner or admin/pegawai
        if (Auth::user()->tipe !== 'admin' && Auth::user()->tipe !== 'pegawai' && Auth::id() !== $pengajuan->id_user && Auth::user()->tipe !== 'helpdesk') {
            abort(403);
        }

        if (!Storage::disk('local')->exists($dokumen->file)) {
            abort(404);
        }

        return Storage::disk('local')->response($dokumen->file);
    }

    public function submit(Pengajuan $pengajuan)
    {
        if (Auth::id() !== $pengajuan->id_user) {
            abort(403);
        }

        if ($pengajuan->status_pengajuan !== 'DRAFT' && $pengajuan->status_pengajuan !== 'PERBAIKAN' && $pengajuan->status_pengajuan !== 'DITOLAK') {
            return redirect()->back()->with('error', 'Status pengajuan tidak memungkinkan untuk dikirim.');
        }

        // Validate all mandatory requirements are filled
        $layananPersyaratan = $pengajuan->layanan->layananPersyaratan()->with('persyaratan')->get();
        $mandatoryRequirements = $layananPersyaratan->filter(function ($lp) {
            return $lp->persyaratan->wajib;
        });

        $mandatoryIds = $mandatoryRequirements->pluck('id');

        $filledMandatoryCount = $pengajuan->dokumen()
            ->whereIn('id_layanan_persyaratan', $mandatoryIds)
            ->where(function ($q) {
                $q->whereNotNull('file')->orWhereNotNull('text');
            })
            ->count();

        if ($filledMandatoryCount < $mandatoryRequirements->count()) {
            return redirect()->back()->with('error', 'Harap lengkapi semua persyaratan wajib sebelum mengirim.');
        }

        if ($pengajuan->status_pengajuan === 'PERBAIKAN') {
            $pengajuan->update([
                'status_pengajuan' => 'DIPROSES',
            ]);
        } else {
            $deadline = now()->addDays($pengajuan->layanan->durasi_hari);
            $pengajuan->update([
                'status_pengajuan' => 'MENUNGGU_DIPROSES',
                'tanggal_pengajuan' => now(),
                'deadline' => $deadline,
            ]);
        }

        // Update document statuses to BELUM_SELESAI (except those already SELESAI)
        $pengajuan->dokumen()->where('status', '!=', 'SELESAI')->update(['status' => 'BELUM_SELESAI']);
        $pengajuan->dokumenTambahan()->where('status', '!=', 'SELESAI')->update(['status' => 'BELUM_SELESAI']);

        // Log riwayat
        if ($pengajuan->status_pengajuan === 'DIPROSES') {
            RiwayatDisposisi::create([
                'id_pengajuan' => $pengajuan->id,
                'nomor_tiket' => $pengajuan->nomor_tiket,
                'status' => 'DIPROSES',
                'tanggal_disposisi' => now(),
                'keterangan' => 'Permohonan dikirim oleh pemohon.',
                'id_user' => Auth::id(),
            ]);
        } else {
            RiwayatDisposisi::create([
                'id_pengajuan' => $pengajuan->id,
                'nomor_tiket' => $pengajuan->nomor_tiket,
                'status' => 'MENUNGGU_DIPROSES',
                'tanggal_disposisi' => now(),
                'keterangan' => 'Permohonan dikirim oleh pemohon.',
                'id_user' => Auth::id(),
            ]);
        }

        // log activity
        $this->logActivity(
            'Kirim Permohonan',
            "Permohonan {$pengajuan->nomor_tiket} berhasil dikirim.",
            ['nomor_tiket' => $pengajuan->nomor_tiket]
        );

        return redirect()->route('permohonan.show', $pengajuan->id)->with('success', 'Permohonan berhasil dikirim.');
    }

    public function review(Request $request, Dokumen $dokumen)
    {
        $dokumen->load('pengajuan.layanan', 'layananPersyaratan.persyaratan', 'layananPersyaratan.kategori');
        if (Auth::user()->tipe !== 'pegawai') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pengajuan = $dokumen->pengajuan;
        $userTeamIds = Auth::user()->timKerjas->pluck('id_tim_kerja')->toArray();
        if (!in_array($pengajuan->layanan->tim_kerja_id, $userTeamIds)) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke pengajuan ini.'], 403);
        }

        // Check Category Access
        $layananKategoriId = $dokumen->layananPersyaratan->layanan_kategori_id;
        if ($layananKategoriId) {
            $userKategoriIds = Auth::user()->kategoriPersyaratan()->pluck('tb_layanan_kategoris.id')->toArray();
            if (!in_array($layananKategoriId, $userKategoriIds)) {
                return response()->json(['message' => 'Anda tidak memiliki akses pemeriksaan untuk kategori dokumen ini.'], 403);
            }
        }

        $request->validate([
            'status' => 'required|in:SELESAI,PERBAIKAN',
            'catatan' => 'nullable|string',
        ]);

        $dokumen->update([
            'status' => $request->status,
        ]);

        if ($request->catatan || $request->status == "SELESAI") {
            $catatan = $request->catatan;
            if ($request->status == "SELESAI") {
                $catatan = "Dokumen ini telah diperiksa dan disetujui.";
            } else {
                $catatan = $request->catatan;
            }

            Catatan::create([
                'id_dokumen' => $dokumen->id,
                'catatan' => $catatan,
                'user_id' => Auth::id(),
            ]);
        }

        // log activity
        $namaDokumen = $dokumen->layananPersyaratan->persyaratan->nama_persyaratan;
        $this->logActivity(
            'Review Dokumen',
            "Review dokumen {$namaDokumen} pada pengajuan {$pengajuan->nomor_tiket}",
            ['nomor_tiket' => $pengajuan->nomor_tiket, 'nama_dokumen' => $namaDokumen]
        );

        return response()->json([
            'success' => true,
            'message' => 'Review dokumen berhasil disimpan.',
        ]);
    }

    public function reviewTambahan(Request $request, DokumenTambahan $dokumen)
    {
        $dokumen->load('pengajuan.layanan');
        if (Auth::user()->tipe !== 'pegawai') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pengajuan = $dokumen->pengajuan;
        $userTeamIds = Auth::user()->timKerjas->pluck('id_tim_kerja')->toArray();
        if (!in_array($pengajuan->layanan->tim_kerja_id, $userTeamIds)) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke pengajuan ini.'], 403);
        }

        $request->validate([
            'status' => 'required|in:SELESAI,PERBAIKAN',
            'catatan' => 'nullable|string',
        ]);

        $dokumen->update([
            'status' => $request->status,
        ]);

        if ($request->catatan) {
            Catatan::create([
                'id_dokumen_tambahan' => $dokumen->id,
                'catatan' => $request->catatan,
                'user_id' => Auth::id(),
            ]);
        }

        // log activity
        $this->logActivity(
            'Review Dokumen Tambahan',
            "Review dokumen tambahan {$dokumen->nama_dokumen} pada pengajuan {$pengajuan->nomor_tiket}",
            ['nomor_tiket' => $pengajuan->nomor_tiket, 'nama_dokumen' => $dokumen->nama_dokumen]
        );

        return response()->json([
            'success' => true,
            'message' => 'Review dokumen tambahan berhasil disimpan.',
        ]);
    }
}
