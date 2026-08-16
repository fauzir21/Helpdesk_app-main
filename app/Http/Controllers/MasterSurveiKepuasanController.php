<?php

namespace App\Http\Controllers;

use App\Models\MasterSurveiKepuasan;
use App\Traits\LogsActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MasterSurveiKepuasanController extends Controller
{
    use LogsActivity;

    public function index()
    {
        return view('admin.master-survei.index');
    }

    public function data(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'nama_survei',
            2 => 'status',
            3 => 'created_at',
        ];

        $totalData = MasterSurveiKepuasan::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $orderColumn = $request->input('order.0.column', 0);
        $order = $columns[$orderColumn] ?? 'id';

        $dir = $request->input('order.0.direction', 'desc');

        $query = MasterSurveiKepuasan::query();

        if (! empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('nama_survei', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");
            });
            $totalFiltered = $query->count();
        }

        $items = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];
        $no = $start + 1;
        foreach ($items as $item) {
            $nestedData['DT_RowIndex'] = $no++;
            $nestedData['nama_survei'] = $item->nama_survei;
            $nestedData['status'] = $item->status == 'aktif'
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-danger">Nonaktif</span>';
            $nestedData['created_at'] = $item->created_at->format('Y-m-d H:i:s');
            $nestedData['action'] = "&emsp;<a href='javascript:void(0)' onclick='editData({$item->id})' class='btn btn-datatable btn-icon btn-transparent-dark me-2' title='Edit' data-bs-toggle='tooltip'><i data-feather='edit'></i></a>
                                    <a href='javascript:void(0)' onclick='deleteData({$item->id})' class='btn btn-datatable btn-icon btn-transparent-dark' title='Hapus' data-bs-toggle='tooltip'><i data-feather='trash-2'></i></a>";
            $data[] = $nestedData;
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_survei' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $item = MasterSurveiKepuasan::create($validated);

        $this->logActivity('Tambah Master Survei', "Menambahkan pertanyaan survei: {$item->nama_survei}");

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    public function edit(MasterSurveiKepuasan $masterSurvei): JsonResponse
    {
        return response()->json($masterSurvei);
    }

    public function update(Request $request, MasterSurveiKepuasan $masterSurvei): JsonResponse
    {
        $validated = $request->validate([
            'nama_survei' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $masterSurvei->update($validated);

        $this->logActivity('Update Master Survei', "Memperbarui pertanyaan survei: {$masterSurvei->nama_survei}");

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    public function destroy(MasterSurveiKepuasan $masterSurvei): JsonResponse
    {
        $name = $masterSurvei->nama_survei;
        $masterSurvei->delete();

        $this->logActivity('Hapus Master Survei', "Menghapus pertanyaan survei: {$name}");

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}
