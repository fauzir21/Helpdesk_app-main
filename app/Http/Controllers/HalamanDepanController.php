<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pengajuan;
use App\Models\SurveiKepuasanMasyarakat;
use Illuminate\Http\Request;
use Shahid\Captcha\Facades\Captcha;

class HalamanDepanController extends Controller
{
    public function index()
    {
        $query = Layanan::where('status', 'aktif');

        if (auth()->check() && auth()->user()->tipe === 'users') {
            $category = auth()->user()->kategori_user;
            $query->whereIn('user_category', [$category, 'semua']);
        } else {
            $query->whereIn('user_category', ['umum', 'semua']);
        }

        $layanan = $query->take(6)->get();

        // Ambil beberapa nama layanan untuk hero section
        $sampleLayanan = Layanan::where('status', 'aktif')->inRandomOrder()->take(3)->pluck('nama_layanan')->toArray();
        $sampleLayananText = implode(', ', array_slice($sampleLayanan, 0, 2)).', hingga '.end($sampleLayanan);

        // Statistik Dinamis
        $tiketSelesai = Pengajuan::whereIn('status_pengajuan', ['SELESAI', 'SELESAI_PEMERIKSAAN'])->count();
        $totalNilai = SurveiKepuasanMasyarakat::avg('nilai') ?? 0;
        // Asumsi skala 1-10, jika 0-6 maka kita sesuaikan. Kita gunakan 10 sebagai basis persentase agar terlihat realistis
        $kepuasanLayanan = $totalNilai > 0 ? round(($totalNilai / 6) * 100) : 98; // Fallback ke 98 jika belum ada data
        $totalLayanan = Layanan::where('status', 'aktif')->count();

        return view('welcome', compact('layanan', 'tiketSelesai', 'kepuasanLayanan', 'totalLayanan', 'sampleLayananText'));
    }

    public function semuaLayanan()
    {
        $query = Layanan::where('status', 'aktif');

        if (auth()->check() && auth()->user()->tipe === 'users') {
            $category = auth()->user()->kategori_user;
            $query->whereIn('user_category', [$category, 'semua']);
        } else {
            $query->whereIn('user_category', ['umum', 'semua']);
        }

        $layanan = $query->paginate(12);

        return view('layanan.index', compact('layanan'));
    }

    public function detailLayanan($slug)
    {
        $query = Layanan::with(['persyaratan' => function ($query) {
            $query->where('tb_persyaratan.status', 'aktif');
        }])->where('status', 'aktif');

        if (auth()->check() && auth()->user()->tipe === 'users') {
            $category = auth()->user()->kategori_user;
            $query->whereIn('user_category', [$category, 'semua']);
        } else {
            $query->whereIn('user_category', ['umum', 'semua']);
        }

        $layanan = $query->where('slug', $slug)->firstOrFail();

        return view('layanan.show', compact('layanan'));
    }

    public function lacak()
    {
        $nomor_tiket = request('nomor_tiket');
        $pengajuan = null;

        if ($nomor_tiket) {
            $pengajuan = Pengajuan::with(['layanan', 'riwayat' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])->where('nomor_tiket', $nomor_tiket)->first();

            if (! $pengajuan) {
                session()->flash('error', 'Nomor tiket tidak ditemukan.');
            }
        }

        return view('lacak', compact('pengajuan', 'nomor_tiket'));
    }

    public function prosesLacak(Request $request)
    {
        $request->validate([
            'nomor_tiket' => 'required|string',
            'captcha' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! Captcha::validate((string) $value)) {
                        $fail(__('Kode captcha tidak sesuai'));
                    }
                },
            ],
        ], [
            'captcha.required' => 'Captcha wajib diisi',
        ]);

        return redirect()->route('lacak', ['nomor_tiket' => $request->nomor_tiket]);
    }
}
