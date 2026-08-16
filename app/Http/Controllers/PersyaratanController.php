<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersyaratanStoreRequest;
use App\Http\Requests\PersyaratanUpdateRequest;
use App\Models\Persyaratan;
use App\Traits\LogsActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersyaratanController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.persyaratan.index');
    }

    public function data(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id', // Mapping No to ID or just a default
            1 => 'nama_persyaratan',
            2 => 'deskripsi',
            3 => 'tipe',
            4 => 'wajib',
            5 => 'status',
        ];

        $total_data = Persyaratan::count();
        $totalFiltered = $total_data;

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $orderColumn = $request->input('order.0.column', 0);
        $order = $columns[$orderColumn] ?? 'id';

        $dir = $request->input('order.0.direction', 'desc');
        if (! in_array(strtolower($dir), ['asc', 'desc'])) {
            $dir = 'desc';
        }

        $query = Persyaratan::query();

        if (! empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $query->where(function ($q) use ($search) {
                $q->where('nama_persyaratan', 'LIKE', "%{$search}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                    ->orWhere('tipe', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");
            });

            $totalFiltered = $query->count();
        }

        $persyaratan = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];
        $no = $start + 1;
        if (! empty($persyaratan)) {
            foreach ($persyaratan as $p) {
                $nestedData['DT_RowIndex'] = $no++;
                $nestedData['nama_persyaratan'] = $p->nama_persyaratan;
                $nestedData['deskripsi'] = $p->deskripsi;
                $nestedData['tipe'] = $p->tipe;
                $nestedData['wajib'] = $p->wajib_label;
                $nestedData['status'] = $p->status;
                $nestedData['created_at'] = $p->created_at->format('d-m-Y H:i:s');
                $nestedData['action'] = "&emsp;<a href='javascript:void(0)' onclick='editData({$p->id})' class='btn btn-datatable btn-icon btn-transparent-dark me-2' title='Edit' data-bs-toggle='tooltip'><i data-feather='edit'></i></a>
                                        <a href='javascript:void(0)' onclick='deleteData({$p->id})' class='btn btn-datatable btn-icon btn-transparent-dark' title='Hapus' data-bs-toggle='tooltip'><i data-feather='trash-2'></i></a>";
                $data[] = $nestedData;
            }
        }

        $json_data = [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($total_data),
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
    public function store(PersyaratanStoreRequest $request): JsonResponse
    {
        $persyaratan = Persyaratan::create($request->validated());

        // log activity
        $this->logActivity(
            'Tambah Persyaratan',
            "Menambahkan persyaratan {$persyaratan->nama_persyaratan}",
            ['nama_persyaratan' => $persyaratan->nama_persyaratan]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data persyaratan berhasil disimpan.',
            'data' => $persyaratan,
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
    public function edit(Persyaratan $persyaratan): JsonResponse
    {
        return response()->json($persyaratan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersyaratanUpdateRequest $request, Persyaratan $persyaratan): JsonResponse
    {
        $persyaratan->update($request->validated());

        // log activity
        $this->logActivity(
            'Update Persyaratan',
            "Mengupdate persyaratan {$persyaratan->nama_persyaratan}",
            ['nama_persyaratan' => $persyaratan->nama_persyaratan]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data persyaratan berhasil diperbarui.',
            'data' => $persyaratan,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persyaratan $persyaratan): JsonResponse
    {
        $persyaratan->delete();

        // log activity
        $this->logActivity(
            'Hapus Persyaratan',
            "Menghapus persyaratan {$persyaratan->nama_persyaratan}",
            ['nama_persyaratan' => $persyaratan->nama_persyaratan]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data persyaratan berhasil dihapus.',
        ]);
    }
}
