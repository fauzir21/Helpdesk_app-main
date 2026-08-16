<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPengerjaanController extends Controller
{
    public function index()
    {
        return view('permohonan.riwayat_pegawai');
    }

    public function data(Request $request)
    {
        $user = Auth::user();
        $userTeamIds = $user->timKerjas->pluck('id_tim_kerja')->toArray();

        // Get applications that belong to user's team AND have been handled by this user
        // Or simply show all COMPLETED applications for their team(s)
        // User asked: "sudah diproses dan diselesaikan oleh pegawai tersebut berdasarkan tim kerjanya"

        $query = Pengajuan::with(['layanan', 'user'])
            ->whereHas('layanan', function ($q) use ($userTeamIds) {
                $q->whereIn('tim_kerja_id', $userTeamIds);
            })
            ->whereHas('riwayat', function ($q) use ($user) {
                $q->where('id_user', $user->id);
            });

        if ($request->has('status') && $request->status != '') {
            $query->where('status_pengajuan', $request->status);
        } else {
            // Default to show only processed ones (usually not DRAFT or MENUNGGU_DIPROSES if they handled it)
            // But user specifically mentioned "diselesaikan", so maybe show all except DRAFT
            $query->where('status_pengajuan', '!=', 'DRAFT');
        }

        $layanans = $query->orderBy('updated_at', 'desc')->get();

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
            $nestedData['nomor_tiket'] = '<span class="badge bg-primary">'.$item->nomor_tiket.'</span>';
            $nestedData['nama_layanan'] = $item->layanan->nama_layanan;
            $nestedData['user'] = $item->user->name;
            $nestedData['tanggal'] = Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y');
            $nestedData['status'] = '<span class="badge '.$statusClass.'">'.str_replace('_', ' ', $item->status_pengajuan).'</span>';

            $action = '<a href="'.route('permohonan.show', $item->id).'" class="btn btn-datatable btn-icon btn-transparent-dark me-1" title="Lihat Detail" data-bs-toggle="tooltip"><i data-feather="eye"></i></a>';

            $nestedData['action'] = $action;
            $data[] = $nestedData;
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
