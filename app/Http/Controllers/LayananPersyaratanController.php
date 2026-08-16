<?php

namespace App\Http\Controllers;

use App\Http\Requests\LayananPersyaratanStoreRequest;
use App\Http\Requests\LayananPersyaratanUpdateRequest;
use App\Models\Layanan;
use App\Models\LayananPersyaratan;
use App\Models\Persyaratan;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class LayananPersyaratanController extends Controller
{
    use LogsActivity;

    public function index(): View
    {
        $persyaratan = Persyaratan::where('status', 'aktif')->get();
        $layanan = Layanan::where('status', 'aktif')->get();

        return view('admin.layanan-persyaratan.index', compact('persyaratan', 'layanan'));
    }

    public function manage(Layanan $layanan): View
    {
        $layanan->load([
            'persyaratan',
            'kategori.users',
            'kategori.layananPersyaratan.persyaratan',
            'layananPersyaratan.persyaratan',
        ]);

        $uncategorized = $layanan->layananPersyaratan()->whereNull('layanan_kategori_id')->with('persyaratan')->get();

        // Get employees from the same Work Team as the service
        $users = $layanan->timKerja
            ? $layanan->timKerja->users()->where('tipe', 'pegawai')->get()
            : collect();

        // get user with tipe 'helpdesk' and add to $users
        $helpdesks = User::where('tipe', 'helpdesk')->get();
        $users = $users->concat($helpdesks);

        // Get requirements NOT already in this service

        $existingPersyaratanIds = $layanan->persyaratan->pluck('id')->toArray();
        $availablePersyaratan = Persyaratan::where('status', 'aktif')
            ->whereNotIn('id', $existingPersyaratanIds)
            ->get();

        return view('admin.layanan-persyaratan.manage', compact('layanan', 'users', 'availablePersyaratan', 'uncategorized'));
    }

    public function addToService(Request $request, Layanan $layanan): JsonResponse
    {
        $request->validate([
            'persyaratan_id' => 'required|exists:tb_persyaratan,id',
        ]);

        $layanan->persyaratan()->attach($request->persyaratan_id);

        return response()->json([
            'success' => true,
            'message' => 'Persyaratan ditambahkan ke layanan.',
        ]);
    }

    public function removeFromService(string $pivotId): JsonResponse
    {
        LayananPersyaratan::findOrFail($pivotId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Persyaratan dihapus dari layanan.',
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $columns = [
            'nama_layanan',
            'jumlah_persyaratan',
            'persyaratan',
        ];

        $total_data = Layanan::count();
        $totalFiltered = $total_data;

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $orderColumnIndex = $request->input('order.0.column', 0);
        // Map DataTables column index to database column name
        $orderMapping = [
            0 => 'id',
            1 => 'nama_layanan',
        ];
        $order = $orderMapping[$orderColumnIndex] ?? 'id';

        $dir = $request->input('order.0.direction', 'desc');
        if (! in_array(strtolower($dir), ['asc', 'desc'])) {
            $dir = 'desc';
        }

        $query = Layanan::with('persyaratan');

        if (! empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'LIKE', "%{$search}%")
                    ->orWhereHas('persyaratan', function ($q2) use ($search) {
                        $q2->where('nama_persyaratan', 'LIKE', "%{$search}%");
                    });
            });

            $totalFiltered = $query->count();
        }

        $layanan = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];
        $no = $start + 1;
        foreach ($layanan as $l) {
            $nestedData['DT_RowIndex'] = $no++;
            $nestedData['nama_layanan'] = $l->nama_layanan;
            $nestedData['jumlah_persyaratan'] = $l->persyaratan->count();

            $nestedData['action_kategori'] = "<a href='".route('layanan-persyaratan.manage', $l->id)."' class='btn btn-sm btn-info'>
                                                <i data-feather='settings' class='me-1'></i> Kelola Struktur
                                              </a>";

            $nestedData['action'] = "&emsp;<a href='javascript:void(0)' onclick='editData({$l->id})' class='btn btn-datatable btn-icon btn-transparent-dark me-2' title='Edit' data-bs-toggle='tooltip'><i data-feather='edit'></i></a>
                                    <a href='javascript:void(0)' onclick='deleteData({$l->id})' class='btn btn-datatable btn-icon btn-transparent-dark' title='Hapus' data-bs-toggle='tooltip'><i data-feather='trash-2'></i></a>";
            $data[] = $nestedData;
        }

        $json_data = [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($total_data),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data,
        ];

        return response()->json($json_data);
    }

    public function show(string $id)
    {
        $layanan = Layanan::with('persyaratan')->findOrFail($id);

        return response()->json([
            'layanan' => $layanan,
            'selected_ids' => $layanan->persyaratan->pluck('id'),
        ]);
    }

    public function store(LayananPersyaratanStoreRequest $request): JsonResponse
    {
        $layanan = Layanan::findOrFail($request->layanan_id);
        $layanan->persyaratan()->sync($request->persyaratan_ids);

        // log activity
        $this->logActivity(
            'Tambah Layanan Persyaratan',
            "Menambahkan persyaratan {$layanan->nama_layanan}",
            ['nama_layanan' => $layanan->nama_layanan]
        );

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function edit(string $id): JsonResponse
    {
        $layanan = Layanan::with('persyaratan')->findOrFail($id);

        return response()->json([
            'layanan' => $layanan,
            'selected_ids' => $layanan->persyaratan->pluck('id'),
        ]);
    }

    public function update(LayananPersyaratanUpdateRequest $request, string $id): JsonResponse
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->persyaratan()->sync($request->persyaratan_ids);

        // log activity
        $this->logActivity(
            'Update Layanan Persyaratan',
            "Mengupdate persyaratan {$layanan->nama_layanan}",
            ['nama_layanan' => $layanan->nama_layanan]
        );

        return response()->json([
            'message' => 'Data berhasil diupdate',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->persyaratan()->detach();

        // log activity
        $this->logActivity(
            'Hapus Layanan Persyaratan',
            "Menghapus persyaratan {$layanan->nama_layanan}",
            ['nama_layanan' => $layanan->nama_layanan]
        );

        return response()->json([
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
