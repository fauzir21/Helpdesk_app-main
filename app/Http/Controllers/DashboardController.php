<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Layanan;
use App\Models\Pengajuan;
use App\Models\RiwayatDisposisi;
use App\Models\SurveiKepuasanMasyarakat;
use App\Models\TimKerja;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Pengajuan::query();

        if ($user->tipe === 'users') {
            $query->where('id_user', $user->id);
        } elseif ($user->tipe === 'pegawai') {
            $userTeamIds = $user->timKerjas->pluck('id_tim_kerja')->toArray();
            $query->whereHas('layanan', function ($q) use ($userTeamIds) {
                $q->whereIn('tim_kerja_id', $userTeamIds);
            })->where('status_pengajuan', '!=', 'DRAFT');
        } elseif ($user->tipe === 'helpdesk' || $user->tipe === 'admin') {
            $query->where('status_pengajuan', '!=', 'DRAFT');
        }

        $stats = [
            'totalTiket' => (clone $query)->count(),
            'sedangDiproses' => (clone $query)->whereIn('status_pengajuan', ['MENUNGGU_DIPROSES', 'DIPROSES', 'PERBAIKAN', 'SELESAI_PEMERIKSAAN'])->count(),
            'selesai' => (clone $query)->where('status_pengajuan', 'SELESAI')->count(),
        ];

        // Chart Data Tim Kerja (Selesai vs Diproses)
        $teamChartData = null;
        if (in_array($user->tipe, ['admin', 'helpdesk', 'pegawai'])) {
            $teamQuery = TimKerja::query()
                ->select('tb_tim_kerja.nama_timkerja')
                ->selectRaw("SUM(CASE WHEN p.status_pengajuan = 'SELESAI' THEN 1 ELSE 0 END) as selesai")
                ->selectRaw("SUM(CASE WHEN p.status_pengajuan IN ('MENUNGGU_DIPROSES', 'DIPROSES', 'PERBAIKAN', 'SELESAI_PEMERIKSAAN') THEN 1 ELSE 0 END) as proses")
                ->leftJoin('tb_layanan as l', 'l.tim_kerja_id', '=', 'tb_tim_kerja.id_tim_kerja')
                ->leftJoin('tb_pengajuan as p', function ($join) {
                    $join->on('p.id_layanan', '=', 'l.id')
                        ->where('p.status_pengajuan', '!=', 'DRAFT');
                });

            if ($user->tipe === 'pegawai') {
                $userTeamIds = $user->timKerjas->pluck('id_tim_kerja')->toArray();
                $teamQuery->whereIn('tb_tim_kerja.id_tim_kerja', $userTeamIds);
            }

            $teamStats = $teamQuery->groupBy('tb_tim_kerja.id_tim_kerja', 'tb_tim_kerja.nama_timkerja')->get();

            $teamChartData = [
                'labels' => $teamStats->pluck('nama_timkerja'),
                'selesai' => $teamStats->pluck('selesai'),
                'proses' => $teamStats->pluck('proses'),
            ];
        }

        // Chart Data for Admin/Helpdesk (SKM)
        $chartData = null;
        if ($user->tipe === 'admin' || $user->tipe === 'helpdesk') {
            $surveyStats = SurveiKepuasanMasyarakat::select('id_master_survei_kepuasan', DB::raw('AVG(nilai) as rata_rata'))
                ->groupBy('id_master_survei_kepuasan')
                ->with('masterSurvei')
                ->get();

            $chartData = [
                'labels' => $surveyStats->map(fn ($s) => $s->masterSurvei->nama_survei ?? 'Unknown'),
                'values' => $surveyStats->map(fn ($s) => round($s->rata_rata, 2)),
            ];
        }

        $layananPopuler = Layanan::withCount('pengajuan')
            ->where('status', 'aktif')
            ->orderBy('pengajuan_count', 'desc')
            ->take(4)
            ->get();

        $aktivitasTerbaru = RiwayatDisposisi::with('pengajuan')
            ->whereHas('pengajuan', function ($q) use ($user) {
                if ($user->tipe === 'users') {
                    $q->where('id_user', $user->id);
                } elseif ($user->tipe === 'pegawai') {
                    $userTeamIds = $user->timKerjas->pluck('id_tim_kerja')->toArray();
                    $q->whereHas('layanan', function ($ql) use ($userTeamIds) {
                        $ql->whereIn('tim_kerja_id', $userTeamIds);
                    });
                }
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $userLogs = ActivityLog::with('user')
            ->when($user->tipe !== 'admin', function ($q) use ($user) {
                return $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Calculate pending count for popup
        $pendingCount = 0;
        if ($user->tipe === 'helpdesk') {
            $pendingCount = Pengajuan::where('status_pengajuan', 'MENUNGGU_DIPROSES')->count();
        } elseif ($user->tipe === 'pegawai') {
            $userTeamIds = $user->timKerjas->pluck('id_tim_kerja')->toArray();
            $pendingCount = Pengajuan::whereIn('status_pengajuan', ['DIPROSES', 'PERBAIKAN'])
                ->whereHas('layanan', function ($q) use ($userTeamIds) {
                    $q->whereIn('tim_kerja_id', $userTeamIds);
                })->count();
        }

        return view('dashboard', compact('stats', 'layananPopuler', 'aktivitasTerbaru', 'userLogs', 'chartData', 'teamChartData', 'pendingCount'));
    }

    public function logData(Request $request): JsonResponse
    {
        $user = Auth::user();
        $query = ActivityLog::with('user');

        if ($user->tipe !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $totalData = (clone $query)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        // Simple search
        if (! empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('activity', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('ip_address', 'LIKE', "%{$search}%");

                $q->orWhereHas('user', function ($qu) use ($search) {
                    $qu->where('name', 'LIKE', "%{$search}%");
                });
            });
            $totalFiltered = $query->count();
        }

        $logs = $query->offset($start)
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [];
        foreach ($logs as $log) {
            $nestedData = [];
            $nestedData['waktu'] = $log->created_at->translatedFormat('d M Y H:i');

            if ($user->tipe === 'admin') {
                $nestedData['user'] = '<div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2"><img class="avatar-img img-fluid" src="https://ui-avatars.com/api/?name='.urlencode($log->user->name).'&background=random" /></div>
                                        <span class="small">'.$log->user->name.'</span>
                                       </div>';
            }

            $nestedData['aktivitas'] = '<span class="badge bg-blue-soft text-blue">'.$log->activity.'</span>';
            $nestedData['keterangan'] = '<span class="small">'.$log->description.'</span>';
            $nestedData['ip'] = '<code class="small text-muted">'.$log->ip_address.'</code>';

            $data[] = $nestedData;
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data,
        ]);
    }
}
