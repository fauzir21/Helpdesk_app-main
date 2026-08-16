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

            $query->whereIn(
                'user_category',
                [$category, 'semua']
            );
        } else {
            $query->whereIn(
                'user_category',
                ['umum', 'semua']
            );
        }

        $layanan = $query->take(6)->get();

        // Ambil beberapa nama layanan untuk hero section
        $sampleLayanan = Layanan::where('status', 'aktif')
            ->inRandomOrder()
            ->take(3)
            ->pluck('nama_layanan')
            ->toArray();

        $sampleLayananText =
            implode(
                ', ',
                array_slice($sampleLayanan, 0, 2)
            )
            . ', hingga '
            . end($sampleLayanan);


        // Statistik Dinamis
        $tiketSelesai = Pengajuan::whereIn(
            'status_pengajuan',
            [
                'SELESAI',
                'SELESAI_PEMERIKSAAN'
            ]
        )->count();


        $totalNilai =
            SurveiKepuasanMasyarakat::avg('nilai')
            ?? 0;


        // Konversi nilai kepuasan menjadi persentase
        $kepuasanLayanan =
            $totalNilai > 0
                ? round(($totalNilai / 6) * 100)
                : 98;


        $totalLayanan =
            Layanan::where(
                'status',
                'aktif'
            )->count();


        return view(
            'welcome',
            compact(
                'layanan',
                'tiketSelesai',
                'kepuasanLayanan',
                'totalLayanan',
                'sampleLayananText'
            )
        );
    }


    /**
     * Menampilkan seluruh layanan.
     *
     * Mendukung:
     * - Search layanan
     * - Filter eksternal
     * - Filter internal
     * - Pagination
     */
    public function semuaLayanan(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | QUERY DASAR
        |--------------------------------------------------------------------------
        */

        $query = Layanan::where(
            'status',
            'aktif'
        );


        /*
        |--------------------------------------------------------------------------
        | FILTER BERDASARKAN KATEGORI USER
        |--------------------------------------------------------------------------
        */

        if (
            auth()->check()
            &&
            auth()->user()->tipe === 'users'
        ) {

            $category =
                auth()->user()->kategori_user;


            $query->whereIn(
                'user_category',
                [
                    $category,
                    'semua'
                ]
            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | GUEST
            |--------------------------------------------------------------------------
            |
            | Pengunjung yang belum login hanya melihat
            | layanan umum dan layanan untuk semua kategori.
            |
            */

            $query->whereIn(
                'user_category',
                [
                    'umum',
                    'semua'
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SEARCH LAYANAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('q')) {

            $search =
                trim(
                    $request->input('q')
                );


            $query->where(
                function ($searchQuery) use ($search) {

                    $searchQuery
                        ->where(
                            'nama_layanan',
                            'like',
                            '%' . $search . '%'
                        )

                        ->orWhere(
                            'deskripsi',
                            'like',
                            '%' . $search . '%'
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER EKSTERNAL / INTERNAL
        |--------------------------------------------------------------------------
        |
        | Sesuai struktur data layanan:
        |
        | umum       = Eksternal
        | pemerintah = Internal
        |
        */

        if ($request->filled('kategori')) {

            $kategori =
                strtolower(
                    $request->input('kategori')
                );


            if ($kategori === 'eksternal') {

                $query->where(
                    'user_category',
                    'umum'
                );

            } elseif ($kategori === 'internal') {

                $query->where(
                    'user_category',
                    'pemerintah'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $layanan =
            $query
                ->latest()
                ->paginate(12)
                ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'layanan.index',
            compact('layanan')
        );
    }


    public function detailLayanan($slug)
    {
        $query = Layanan::with([
            'persyaratan' => function ($query) {
                $query->where(
                    'tb_persyaratan.status',
                    'aktif'
                );
            }
        ])->where(
            'status',
            'aktif'
        );


        if (
            auth()->check()
            &&
            auth()->user()->tipe === 'users'
        ) {

            $category =
                auth()->user()->kategori_user;


            $query->whereIn(
                'user_category',
                [
                    $category,
                    'semua'
                ]
            );

        } else {

            $query->whereIn(
                'user_category',
                [
                    'umum',
                    'semua'
                ]
            );
        }


        $layanan =
            $query
                ->where(
                    'slug',
                    $slug
                )
                ->firstOrFail();


        return view(
            'layanan.show',
            compact('layanan')
        );
    }


    public function lacak()
    {
        $nomor_tiket =
            request('nomor_tiket');


        $pengajuan = null;


        if ($nomor_tiket) {

            $pengajuan =
                Pengajuan::with([
                    'layanan',

                    'riwayat' => function ($query) {

                        $query->orderBy(
                            'created_at',
                            'desc'
                        );
                    }
                ])
                ->where(
                    'nomor_tiket',
                    $nomor_tiket
                )
                ->first();


            if (! $pengajuan) {

                session()->flash(
                    'error',
                    'Nomor tiket tidak ditemukan.'
                );
            }
        }


        return view(
            'lacak',
            compact(
                'pengajuan',
                'nomor_tiket'
            )
        );
    }


    public function prosesLacak(
        Request $request
    ) {

        $request->validate(
            [
                'nomor_tiket' =>
                    'required|string',

                'captcha' => [
                    'required',

                    function (
                        $attribute,
                        $value,
                        $fail
                    ) {

                        if (
                            ! Captcha::validate(
                                (string) $value
                            )
                        ) {

                            $fail(
                                __(
                                    'Kode captcha tidak sesuai'
                                )
                            );
                        }
                    },
                ],
            ],
            [
                'captcha.required' =>
                    'Captcha wajib diisi',
            ]
        );


        return redirect()->route(
            'lacak',
            [
                'nomor_tiket' =>
                    $request->nomor_tiket
            ]
        );
    }
}