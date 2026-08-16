<?php

namespace App\Http\Controllers;

use App\Exports\PengajuanExport;
use App\Http\Requests\PengajuanStoreRequest;
use App\Models\DokumenHasil;
use App\Models\Layanan;
use App\Models\Pengajuan;
use App\Models\RiwayatDisposisi;
use App\Models\TimKerja;
use App\Models\User;
use App\Notifications\NewPengajuanNotification;
use App\Traits\LogsActivity;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PengajuanController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->tipe === 'helpdesk' || Auth::user()->tipe === 'admin') {
            $timKerja = TimKerja::all();

            return view('permohonan.helpdesk', compact('timKerja'));
        }

        if (Auth::user()->tipe === 'pegawai') {
            return view('permohonan.pegawai');
        }

        return view('permohonan.index');
    }

    public function list()
    {
        return view('permohonan.list');
    }

    public function data(Request $request)
    {
        $query = Pengajuan::with(['layanan', 'user', 'surveiKepuasan']);

        // Filter based on status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pengajuan', $request->status);
        }

        if ($request->has('tim_kerja') && $request->tim_kerja != '') {
            $query->whereHas('layanan', function ($q) use ($request) {
                $q->where('tim_kerja_id', $request->tim_kerja);
            });
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('tanggal_pengajuan', $request->date);
        }

        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('tanggal_pengajuan', $request->month)
                ->whereYear('tanggal_pengajuan', $request->year ?: date('Y'));
        }

        if ($request->has('quarter') && $request->quarter != '') {
            $months = match ((int) $request->quarter) {
                1 => [1, 2, 3],
                2 => [4, 5, 6],
                3 => [7, 8, 9],
                4 => [10, 11, 12],
                default => []
            };
            $query->whereIn(\DB::raw('MONTH(tanggal_pengajuan)'), $months)
                ->whereYear('tanggal_pengajuan', $request->year ?: date('Y'));
        }

        // Filter based on role
        if (Auth::user()->tipe === 'helpdesk') {
            // Layanan sees all that are not DRAFT (already submitted)
            $query->where('status_pengajuan', '!=', 'DRAFT');
        } elseif (Auth::user()->tipe === 'pegawai') {
            // Pegawai sees submissions that are in DIPROSES or PERBAIKAN for their team
            $userTeamIds = Auth::user()->timKerjas->pluck('id_tim_kerja')->toArray();
            $query->whereIn('status_pengajuan', ['DIPROSES', 'PERBAIKAN'])
                ->whereHas('layanan', function ($q) use ($userTeamIds) {
                    $q->whereIn('tim_kerja_id', $userTeamIds);
                });
        } elseif (Auth::user()->tipe === 'users') {
            // Regular user only sees their own
            $query->where('id_user', Auth::id());
        }

        $layanans = $query->orderBy('created_at', 'desc')->get();

        $data = [];
        $no = 1;
        foreach ($layanans as $item) {
            $statusClass = match ($item->status_pengajuan) {
                'DRAFT' => 'bg-secondary',
                'MENUNGGU_DIPROSES' => 'bg-warning',
                'DIPROSES' => 'bg-info',
                'DITOLAK' => 'bg-danger',
                'PERBAIKAN' => 'bg-dark',
                'SELESAI' => 'bg-success',
                'SELESAI_PEMERIKSAAN' => 'bg-primary',
                default => 'bg-light text-dark'
            };

            $nestedData['DT_RowIndex'] = $no++;
            $nestedData['DT_RowId'] = $item->id;
            $nestedData['nomor_tiket'] = '<span class="badge bg-primary">' . $item->nomor_tiket . '</span>';

            $deadlineInfo = '';
            if ($item->deadline && !in_array($item->status_pengajuan, ['SELESAI', 'DITOLAK'])) {
                if ($item->deadline->isPast()) {
                    $deadlineInfo = '<br><span class="badge bg-danger mt-1"><i class="fas fa-exclamation-circle me-1"></i> Melewati Deadline</span>';
                } elseif ($item->deadline->isTomorrow()) {
                    $deadlineInfo = '<br><span class="badge bg-warning mt-1 text-dark"><i class="fas fa-clock me-1"></i> Sisa 1 Hari!</span>';
                }
            }

            $nestedData['nama_layanan'] = $item->layanan->nama_layanan . $deadlineInfo;

            // Format Detail Tambahan
            $detailHtml = '';
            if ($item->detail_tambahan && count($item->detail_tambahan) > 0) {
                $detailHtml = '<div class="extra-small text-muted">';
                foreach ($item->detail_tambahan as $key => $value) {
                    $label = str_replace('_', ' ', ucfirst($key));
                    $detailHtml .= '<strong>' . $label . ':</strong> ' . $value . '<br>';
                }
                $detailHtml .= '</div>';
            }
            $nestedData['detail_tambahan'] = $detailHtml ?: '-';

            $nestedData['user'] = $item->user->name;
            $nestedData['tanggal'] = Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y');
            $nestedData['deadline'] = $item->deadline ? $item->deadline->translatedFormat('d F Y') : '-';
            $nestedData['status'] = '<span class="badge ' . $statusClass . '">' . str_replace('_', ' ', $item->status_pengajuan) . '</span>';

            $action = '<a href="' . route('permohonan.show', $item->id) . '" class="btn btn-datatable btn-icon btn-transparent-dark me-1" title="Lihat Detail" data-bs-toggle="tooltip"><i data-feather="eye"></i></a>';
            $action .= '<button type="button" onclick="showRiwayat(\'' . $item->nomor_tiket . '\')" class="btn btn-datatable btn-icon btn-transparent-dark me-1" title="Lihat Riwayat Status" data-bs-toggle="tooltip"><i data-feather="list"></i></button>';

            if (Auth::user()->tipe === 'users' && $item->status_pengajuan === 'SELESAI' && $item->surveiKepuasan->count() === 0) {
                $action .= '<a href="' . route('permohonan.survei', $item->id) . '" class="btn btn-datatable btn-icon btn-transparent-dark text-warning" title="Isi Survei Kepuasan" data-bs-toggle="tooltip"><i data-feather="heart"></i></a>';
            }

            if (Auth::user()->tipe === 'helpdesk' || Auth::user()->tipe === 'admin') {
                if ($item->status_pengajuan === 'MENUNGGU_DIPROSES') {
                    $action .= '<button onclick="updateStatus(' . $item->id . ', \'DIPROSES\')" class="btn btn-datatable btn-icon btn-transparent-dark text-success me-1" title="Lanjutkan" data-bs-toggle="tooltip"><i data-feather="check-circle"></i></button>';
                    $action .= '<button onclick="updateStatus(' . $item->id . ', \'DITOLAK\')" class="btn btn-datatable btn-icon btn-transparent-dark text-danger" title="Tolak" data-bs-toggle="tooltip"><i data-feather="x-circle"></i></button>';
                } elseif ($item->status_pengajuan === 'SELESAI_PEMERIKSAAN') {
                    $action .= '<button onclick="updateStatus(' . $item->id . ', \'SELESAI\')" class="btn btn-datatable btn-icon btn-transparent-dark text-success me-1" title="Selesaikan" data-bs-toggle="tooltip"><i data-feather="check-circle"></i></button>';
                }
            }

            $nestedData['action'] = $action;
            $data[] = $nestedData;
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $status = $request->get('status');
        $date = $request->get('date');
        $month = $request->get('month');
        $quarter = $request->get('quarter');
        $year = $request->get('year', date('Y'));
        $tim_kerja = $request->get('tim_kerja');

        $filename = 'rekap_pengajuan_' . date('YmdHis');

        if ($format === 'pdf') {
            $query = Pengajuan::with(['layanan', 'user']);

            if ($tim_kerja) {
                $query->whereHas('layanan', function ($q) use ($tim_kerja) {
                    $q->where('tim_kerja_id', $tim_kerja);
                });
            }

            if ($status) {
                $query->where('status_pengajuan', $status);
            }

            if ($date) {
                $query->whereDate('tanggal_pengajuan', $date);
            }

            if ($month) {
                $query->whereMonth('tanggal_pengajuan', $month)
                    ->whereYear('tanggal_pengajuan', $year);
            }

            if ($quarter) {
                $months = match ((int) $quarter) {
                    1 => [1, 2, 3],
                    2 => [4, 5, 6],
                    3 => [7, 8, 9],
                    4 => [10, 11, 12],
                    default => []
                };
                $query->whereIn(\DB::raw('MONTH(tanggal_pengajuan)'), $months)
                    ->whereYear('tanggal_pengajuan', $year);
            }

            $pengajuans = $query->orderBy('created_at', 'desc')->get();

            $pdf = Pdf::loadView('permohonan.pdf', compact('pengajuans', 'status', 'date', 'month', 'quarter', 'year'))
                ->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        return Excel::download(new PengajuanExport($status, $date, $month, $quarter, $year, $tim_kerja), $filename . '.xlsx');
    }

    public function downloadReport($id)
    {
        $pengajuan = Pengajuan::with([
            'layanan.timKerja',
            'layanan.layananPersyaratan.persyaratan',
            'layanan.layananPersyaratan.kategori',
            'user',
            'dokumen.catatans.user',
            'dokumenTambahan.catatans.user',
        ])->findOrFail($id);

        // Authorization: Only Ketua Tim of the service team can download
        $timKerja = $pengajuan->layanan->timKerja;
        if (Auth::user()->tipe !== 'pegawai' || !$timKerja || $timKerja->ketua_id !== Auth::id()) {
            abort(403, 'Hanya Ketua Tim yang dapat mendownload laporan ini.');
        }

        // Requirement check: All documents must be approved
        if (!$pengajuan->isAllDocumentsApproved()) {
            abort(403, 'Laporan hanya tersedia jika seluruh dokumen persyaratan sudah berstatus SELESAI.');
        }

        // Prepare data for the report
        $allLp = $pengajuan->layanan->layananPersyaratan;
        $groupedPersyaratan = $allLp->groupBy(function ($lp) {
            return $lp->kategori ? $lp->kategori->nama_kategori : 'Umum';
        });

        $pdf = Pdf::loadView('permohonan.report-pdf', compact('pengajuan', 'groupedPersyaratan'))
            ->setPaper('a4', 'portrait');

        $filename = 'Laporan_Pemenuhan_' . $pengajuan->nomor_tiket . '.pdf';

        return $pdf->download($filename);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:DIPROSES,DITOLAK,PERBAIKAN,SELESAI,SELESAI_PEMERIKSAAN',
            'keterangan' => 'nullable|string',
            'file_hasil' => 'nullable|array',
            'file_hasil.*' => 'file|max:10240', // Max 10MB per file
        ]);

        $pengajuan = Pengajuan::with(['dokumen', 'dokumenTambahan', 'layanan'])->findOrFail($id);

        // Layanan logic
        if (Auth::user()->tipe === 'helpdesk') {
            if ($pengajuan->status_pengajuan === 'SELESAI_PEMERIKSAAN') {
                if ($request->status !== 'SELESAI') {
                    return response()->json(['message' => 'Layanan hanya bisa menyelesaikan permohonan yang sudah selesai diperiksa.'], 403);
                }

                if (!$request->hasFile('file_hasil')) {
                    return response()->json(['message' => 'Harap sertakan dokumen hasil permohonan.'], 422);
                }
            } else {
                if (!in_array($request->status, ['DIPROSES', 'DITOLAK'])) {
                    return response()->json(['message' => 'Layanan hanya bisa memproses atau menolak permohonan.'], 403);
                }

                if ($pengajuan->status_pengajuan !== 'MENUNGGU_DIPROSES') {
                    return response()->json(['message' => 'Hanya permohonan dengan status MENUNGGU DIPROSES yang dapat diproses oleh Layanan.'], 422);
                }
            }
        }
        // Pegawai logic
        elseif (Auth::user()->tipe === 'pegawai') {
            $userTeamIds = Auth::user()->timKerjas->pluck('id_tim_kerja')->toArray();
            if (!in_array($pengajuan->layanan->tim_kerja_id, $userTeamIds)) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke pengajuan ini.'], 403);
            }

            // Otorisasi Ketua Tim: Hanya Ketua Tim yang bisa menyelesaikan (SELESAI) atau meminta perbaikan (PERBAIKAN)
            if (in_array($request->status, ['SELESAI', 'PERBAIKAN'])) {
                $timKerja = $pengajuan->layanan->timKerja;
                if (!$timKerja || $timKerja->ketua_id !== Auth::id()) {
                    $action = $request->status === 'SELESAI' ? 'menyelesaikan' : 'meminta perbaikan pada';

                    return response()->json(['message' => "Hanya Ketua Tim yang memiliki otoritas untuk {$action} permohonan ini."], 403);
                }
            }

            if (!in_array($request->status, ['PERBAIKAN', 'SELESAI_PEMERIKSAAN', 'SELESAI'])) {
                return response()->json(['message' => 'Pegawai hanya bisa menandai sebagai Perbaikan, Selesai Pemeriksaan, atau Selesai.'], 403);
            }

            // Check if all documents are reviewed
            $allReviewed = true;
            $allSelesai = true;
            $hasPerbaikan = false;

            foreach ($pengajuan->dokumen as $dok) {
                if (in_array($dok->status, ['DRAFT', 'BELUM_SELESAI'])) {
                    $allReviewed = false;
                    $allSelesai = false;
                }
                if ($dok->status === 'PERBAIKAN') {
                    $hasPerbaikan = true;
                    $allSelesai = false;
                }
            }

            foreach ($pengajuan->dokumenTambahan as $dok) {
                if (in_array($dok->status, ['DRAFT', 'BELUM_SELESAI'])) {
                    $allReviewed = false;
                    $allSelesai = false;
                }
                if ($dok->status === 'PERBAIKAN') {
                    $hasPerbaikan = true;
                    $allSelesai = false;
                }
            }

            if (!$allReviewed) {
                return response()->json(['message' => 'Seluruh dokumen harus selesai diperiksa sebelum merubah status pengajuan.'], 422);
            }

            if ($request->status === 'PERBAIKAN' && !$hasPerbaikan) {
                return response()->json(['message' => 'Status hanya bisa dirubah ke Perbaikan jika ada minimal satu dokumen yang berstatus PERBAIKAN.'], 422);
            }

            if (($request->status === 'SELESAI_PEMERIKSAAN' || $request->status === 'SELESAI') && !$allSelesai) {
                return response()->json(['message' => 'Seluruh dokumen harus berstatus SELESAI sebelum menandai pemeriksaan selesai atau menyelesaikan permohonan.'], 422);
            }

            if ($request->status === 'SELESAI' && !$request->hasFile('file_hasil')) {
                return response()->json(['message' => 'Harap sertakan dokumen hasil permohonan.'], 422);
            }
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pengajuan->update([
            'status_pengajuan' => $request->status,
            'tanggal_selesai' => $request->status === 'SELESAI' ? now() : $pengajuan->tanggal_selesai,
        ]);

        // Trigger notification for pegawai when helpdesk forwards (DIPROSES)
        if (Auth::user()->tipe === 'helpdesk' && $request->status === 'DIPROSES') {
            $pegawai = User::where('tipe', 'pegawai')
                ->whereHas('timKerjas', function ($q) use ($pengajuan) {
                    $q->where('tb_tim_kerja.id_tim_kerja', $pengajuan->layanan->tim_kerja_id);
                })
                ->get();

            Notification::send($pegawai, new NewPengajuanNotification($pengajuan));
        }

        $this->logActivity(
            'Update Status Permohonan',
            "Mengubah status permohonan {$pengajuan->nomor_tiket} menjadi {$request->status}",
            ['nomor_tiket' => $pengajuan->nomor_tiket, 'status' => $request->status]
        );

        if ($request->status === 'SELESAI' && $request->hasFile('file_hasil')) {
            foreach ($request->file('file_hasil') as $file) {
                $path = $file->store('dokumen_hasil/' . $pengajuan->id, 'local');
                DokumenHasil::create([
                    'id_pengajuan' => $pengajuan->id,
                    'nama_file' => $file->getClientOriginalName(),
                    'path' => $path,
                ]);
            }
        }

        RiwayatDisposisi::create([
            'id_pengajuan' => $pengajuan->id,
            'nomor_tiket' => $pengajuan->nomor_tiket,
            'status' => $request->status,
            'tanggal_disposisi' => now(),
            'keterangan' => $request->keterangan ?? 'Status diperbarui oleh ' . ucfirst(Auth::user()->tipe) . '.',
            'id_user' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status permohonan berhasil diperbarui.',
        ]);
    }

    public function layanan(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();

        $query = Layanan::withCount('persyaratan')
            ->where('status', 'aktif');

        if ($user->tipe === 'users') {
            $query->whereIn('user_category', [$user->kategori_user, 'semua']);
        }

        $layanan = $query->when($search, function ($query, $search) {
            $query->where('nama_layanan', 'LIKE', "%{$search}%")
                ->orWhere('deskripsi', 'LIKE', "%{$search}%");
        })
            ->get();

        return response()->json($layanan);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Check if user has any finished application without survey
        // if ($user->tipe === 'users') {
        //     $unsurveyed = Pengajuan::where('id_user', $user->id)
        //         ->where('status_pengajuan', 'SELESAI')
        //         ->whereDoesntHave('surveiKepuasan')
        //         ->first();

        //     if ($unsurveyed) {
        //         return redirect()->route('permohonan.survei', $unsurveyed->id)
        //             ->with('error', 'Silahkan isi survei kepuasan masyarakat terlebih dahulu untuk layanan yang telah selesai.');
        //     }
        // }

        $slug = $request->query('layanan');

        $query = Layanan::with([
            'layananPersyaratan' => function ($q) {
                $q->with('persyaratan', 'kategori');
            },
        ])
            ->where('slug', $slug)
            ->where('status', 'aktif');

        if ($user->tipe === 'users') {
            $query->whereIn('user_category', [$user->kategori_user, 'semua']);
        }

        $layanan = $query->firstOrFail();

        // Group requirements by category
        $groupedPersyaratan = $layanan->layananPersyaratan->groupBy(function ($lp) {
            return $lp->kategori ? $lp->kategori->nama_kategori : 'Umum';
        });

        return view('permohonan.create', compact('layanan', 'groupedPersyaratan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PengajuanStoreRequest $request)
    {
        $user = Auth::user();

        // Check if user has any finished application without survey
        // if ($user->tipe === 'users') {
        //     $unsurveyed = Pengajuan::where('id_user', $user->id)
        //         ->where('status_pengajuan', 'SELESAI')
        //         ->whereDoesntHave('surveiKepuasan')
        //         ->first();

        //     if ($unsurveyed) {
        //         return redirect()->route('permohonan.survei', $unsurveyed->id)
        //             ->with('error', 'Silahkan isi survei kepuasan masyarakat terlebih dahulu untuk layanan yang telah selesai.');
        //     }
        // }

        $validated = $request->validated();

        // Generate nomor tiket: TK-YYYYMMDD-RANDOM
        $nomor_tiket = 'TK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $layanan = Layanan::findOrFail($validated['id_layanan']);
        $deadline = now()->addDays($layanan->durasi_hari);

        $pengajuan = Pengajuan::create([
            'id_layanan' => $validated['id_layanan'],
            'id_user' => Auth::id(),
            'status_pengajuan' => 'DRAFT',
            'nomor_tiket' => $nomor_tiket,
            'tanggal_pengajuan' => now(),
            'deadline' => $deadline,
            'detail_tambahan' => $validated['detail_tambahan'] ?? null,
        ]);

        $this->logActivity(
            'Buat Permohonan',
            "Membuat permohonan baru dengan nomor tiket {$nomor_tiket}",
            ['nomor_tiket' => $nomor_tiket]
        );

        // Log riwayat disposisi
        RiwayatDisposisi::create([
            'id_pengajuan' => $pengajuan->id,
            'nomor_tiket' => $nomor_tiket,
            'status' => 'DRAFT',
            'tanggal_disposisi' => now(),
            'keterangan' => 'Permohonan baru dibuat oleh pemohon.',
            'id_user' => Auth::id(),
        ]);

        return redirect()->route('permohonan.show', $pengajuan->id)
            ->with('success', 'Permohonan berhasil dibuat dengan nomor tiket: ' . $nomor_tiket);
    }

    public function show(string $id)
    {
        $pengajuan = Pengajuan::with([
            'layanan.layananPersyaratan.persyaratan',
            'layanan.layananPersyaratan.kategori',
            'layanan.timKerja',
            'user',
            'dokumen.catatans.user',
            'dokumenTambahan.catatans.user',
            'riwayat' => function ($q) {
                $q->orderBy('tanggal_disposisi', 'desc');
            },
        ])->findOrFail($id);

        // Get ALL requirements for this service
        $allLp = $pengajuan->layanan->layananPersyaratan;

        // Jika user adalah helpdesk, filter hanya untuk kategori Helpdesk
        if (Auth::user()->tipe === 'helpdesk') {
            $allLp = $allLp->filter(function ($lp) {
                return $lp->kategori && strtolower($lp->kategori->nama_kategori) === 'helpdesk';
            })->values();
        }

        // Sort: text type first, then file
        // $sortedLp = $allLp->sortBy(function ($lp) {
        //     return $lp->persyaratan->tipe === 'text' ? 1 : 2;
        // })->values();

        // sort: layanan kategori == helpdesk or like helpdesk make first, then umum
        $sortedLp = $allLp->sortBy(function ($lp) {
            return $lp->kategori ? $lp->kategori->nama_kategori === 'Helpdesk' || $lp->kategori->nama_kategori === 'helpdesk' ? 1 : 2 : 1;
        })->values();

        $pengajuan->layanan->setRelation('layananPersyaratan', $sortedLp);

        // Group ALL requirements by category for display
        $groupedPersyaratan = $sortedLp->groupBy(function ($lp) {
            return $lp->kategori ? $lp->kategori->nama_kategori : 'Umum';
        });

        // Check if user is owner or admin/pegawai
        if (Auth::user()->tipe !== 'admin' && Auth::user()->tipe !== 'pegawai' && Auth::id() !== $pengajuan->id_user && Auth::user()->tipe !== 'helpdesk') {
            abort(403);
        }

        return view('permohonan.show', compact('pengajuan', 'groupedPersyaratan'));
    }

    public function riwayat($nomor_tiket, Request $request)
    {
        $pengajuan = Pengajuan::with([
            'layanan.layananPersyaratan.persyaratan',
            'layanan.layananPersyaratan.kategori',
            'user',
            'dokumen.catatans.user',
            'dokumenTambahan.catatans.user',
            'riwayat.user',
        ])->where('nomor_tiket', $nomor_tiket)->firstOrFail();

        // Get status history
        $riwayatStatus = $pengajuan->riwayat->map(function ($item) {
            return [
                'tanggal' => $item->tanggal_disposisi,
                'status' => $item->status,
                'keterangan' => $item->keterangan,
                'user' => $item->user->name ?? 'System',
                'type' => 'status',
            ];
        });

        // Get document update history from Catatan (only those made by pemohon)
        $riwayatDokumen = collect();

        foreach ($pengajuan->dokumen as $dokumen) {
            foreach ($dokumen->catatans as $catatan) {
                if ($catatan->user && $catatan->user->tipe === 'users') {
                    $riwayatDokumen->push([
                        'tanggal' => $catatan->created_at,
                        'status' => 'UPDATE_DOKUMEN',
                        'keterangan' => $catatan->catatan,
                        'user' => $catatan->user->name,
                        'type' => 'dokumen',
                    ]);
                }
            }
        }

        foreach ($pengajuan->dokumenTambahan as $dokumen) {
            foreach ($dokumen->catatans as $catatan) {
                if ($catatan->user && $catatan->user->tipe === 'users') {
                    $riwayatDokumen->push([
                        'tanggal' => $catatan->created_at,
                        'status' => 'UPDATE_DOKUMEN',
                        'keterangan' => $catatan->catatan,
                        'user' => $catatan->user->name,
                        'type' => 'dokumen',
                    ]);
                }
            }
        }

        // Merge and sort by date descending
        $timeline = $riwayatStatus->concat($riwayatDokumen)->sortByDesc('tanggal');

        if ($request->ajax()) {
            return view('permohonan.partials.riwayat-timeline', compact('pengajuan', 'timeline'));
        }

        return view('permohonan.riwayat', compact('pengajuan', 'timeline'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        // Authorization: owner or admin
        if (Auth::user()->role !== 'admin' && Auth::id() !== $pengajuan->id_user) {
            abort(403);
        }

        // Status check: only DRAFT or DITOLAK
        if (!in_array($pengajuan->status_pengajuan, ['DRAFT', 'DITOLAK'])) {
            return redirect()->back()->with('error', 'Hanya permohonan dengan status DRAFT atau DITOLAK yang dapat dihapus.');
        }

        // Clean up files in local storage
        foreach ($pengajuan->dokumen as $dokumen) {
            if ($dokumen->file) {
                Storage::disk('local')->delete($dokumen->file);
            }
        }

        foreach ($pengajuan->dokumenTambahan as $dokumen) {
            if ($dokumen->file) {
                Storage::disk('local')->delete($dokumen->file);
            }
        }

        $this->logActivity(
            'Hapus Permohonan',
            "Menghapus permohonan dengan nomor tiket {$pengajuan->nomor_tiket}",
            ['nomor_tiket' => $pengajuan->nomor_tiket]
        );

        $pengajuan->delete();

        return redirect()->route('permohonan.index')->with('success', 'Permohonan berhasil dihapus.');
    }

    public function viewHasil(DokumenHasil $dokumen)
    {
        $pengajuan = $dokumen->pengajuan;

        // Authorization: owner or admin/pegawai/helpdesk
        if (Auth::user()->tipe !== 'admin' && Auth::user()->tipe !== 'pegawai' && Auth::id() !== $pengajuan->id_user && Auth::user()->tipe !== 'helpdesk') {
            abort(403);
        }

        if (!Storage::disk('local')->exists($dokumen->path)) {
            abort(404);
        }

        return Storage::disk('local')->response($dokumen->path, $dokumen->nama_file);
    }
}
