<?php

namespace App\Http\Controllers;

use App\Http\Requests\LayananStoreRequest;
use App\Http\Requests\LayananUpdateRequest;
use App\Models\Layanan;
use App\Models\TimKerja;
use App\Traits\LogsActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timKerja = TimKerja::all();

        return view('admin.layanan.index', compact('timKerja'));
    }

    public function data(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'nama_layanan',
            2 => 'deskripsi',
            3 => 'status',
            4 => 'tim_kerja_id',
            5 => 'created_at',
        ];

        $totalData = Layanan::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $orderColumn = $request->input('order.0.column', 0);
        $order = $columns[$orderColumn] ?? 'id';

        $dir = $request->input('order.0.direction', 'desc');
        if (! in_array(strtolower($dir), ['asc', 'desc'])) {
            $dir = 'desc';
        }

        $query = Layanan::with('timKerja');

        if (! empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama_layanan', 'LIKE', "%{$search}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhere('tim_kerja_id', 'LIKE', "%{$search}%")
                    ->orWhere('created_at', 'LIKE', "%{$search}%");
            });

            $totalFiltered = $query->count();
        }

        $layanans = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];
        $no = $start + 1;
        if (! empty($layanans)) {
            foreach ($layanans as $layanan) {
                $nestedData['DT_RowIndex'] = $no++;
                $nestedData['nama_layanan'] = $layanan->nama_layanan;
                $nestedData['deskripsi'] = $layanan->deskripsi;
                $nestedData['status'] = $layanan->status;
                $nestedData['durasi_hari'] = $layanan->durasi_hari.' Hari';
                $nestedData['input_tambahan'] = $layanan->input_tambahan ? count($layanan->input_tambahan).' Field' : '-';
                $nestedData['user_category'] = match ($layanan->user_category) {
                    'umum' => '<span class="badge bg-secondary">Umum</span>',
                    'pemerintah' => '<span class="badge bg-info">Pemerintah</span>',
                    default => '<span class="badge bg-primary">Semua</span>',
                };
                $nestedData['tim_kerja_id'] = $layanan->timKerja->nama_timkerja ?? '-';
                $nestedData['created_at'] = $layanan->created_at->format('Y-m-d H:i:s');
                $nestedData['action'] = "&emsp;<a href='javascript:void(0)' onclick='editData({$layanan->id})' class='btn btn-datatable btn-icon btn-transparent-dark me-2' title='Edit' data-bs-toggle='tooltip'><i data-feather='edit'></i></a>
                                        <a href='javascript:void(0)' onclick='deleteData({$layanan->id})' class='btn btn-datatable btn-icon btn-transparent-dark' title='Hapus' data-bs-toggle='tooltip'><i data-feather='trash-2'></i></a>";
                $data[] = $nestedData;
            }
        }

        $json_data = [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data,
        ];

        return response()->json($json_data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LayananStoreRequest $request): JsonResponse
    {
        $layanan = Layanan::create($request->validated());

        $this->logActivity(
            'Tambah Layanan',
            "Menambahkan layanan baru: {$layanan->nama_layanan}",
            ['layanan_id' => $layanan->id, 'nama_layanan' => $layanan->nama_layanan]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data layanan berhasil disimpan.',
            'data' => $layanan,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layanan $layanan): JsonResponse
    {
        return response()->json($layanan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LayananUpdateRequest $request, Layanan $layanan): JsonResponse
    {
        $layanan->update($request->validated());

        $this->logActivity(
            'Update Layanan',
            "Memperbarui data layanan: {$layanan->nama_layanan}",
            ['layanan_id' => $layanan->id, 'nama_layanan' => $layanan->nama_layanan]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data layanan berhasil diperbarui.',
            'data' => $layanan,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layanan $layanan): JsonResponse
    {
        $layananName = $layanan->nama_layanan;
        $layananId = $layanan->id;

        $layanan->delete();

        $this->logActivity(
            'Hapus Layanan',
            "Menghapus layanan: {$layananName}",
            ['layanan_id' => $layananId, 'nama_layanan' => $layananName]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data layanan berhasil dihapus.',
        ]);
    }
}
