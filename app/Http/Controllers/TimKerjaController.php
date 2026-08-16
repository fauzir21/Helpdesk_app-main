<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimKerjaStoreRequest;
use App\Http\Requests\TimKerjaUpdateRequest;
use App\Models\TimKerja;
use App\Traits\LogsActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimKerjaController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.tim-kerja.index');
    }

    /**
     * Get data for DataTables (Server Side).
     */
    public function data(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id_tim_kerja',
            1 => 'nama_timkerja',
            2 => 'id_tim_kerja', // placeholder for anggota, sorting by ID for now or maybe count?
            3 => 'created_at',
            4 => 'id_tim_kerja', // action column
        ];

        $totalData = TimKerja::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $orderColumn = $request->input('order.0.column', 0);
        $order = $columns[$orderColumn] ?? 'id_tim_kerja';

        $dir = $request->input('order.0.direction', 'desc');
        if (! in_array(strtolower($dir), ['asc', 'desc'])) {
            $dir = 'desc';
        }

        $query = TimKerja::with(['users', 'ketua']);

        if (! empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $query->where(function ($q) use ($search) {
                $q->where('id_tim_kerja', 'LIKE', "%{$search}%")
                    ->orWhere('nama_timkerja', 'LIKE', "%{$search}%")
                    ->orWhereHas('ketua', function ($q2) use ($search) {
                        $q2->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('users', function ($q2) use ($search) {
                        $q2->where('name', 'LIKE', "%{$search}%");
                    });
            });

            $totalFiltered = $query->count();
        }

        $timKerjas = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];
        $no = $start + 1;
        if (! empty($timKerjas)) {
            foreach ($timKerjas as $tk) {
                $nestedData['DT_RowIndex'] = $no++;
                $nestedData['nama_timkerja'] = $tk->nama_timkerja;
                $nestedData['ketua'] = $tk->ketua->name ?? '-';
                $nestedData['anggota'] = $tk->users->pluck('name')->implode(', ');
                $nestedData['created_at'] = $tk->created_at->format('Y-m-d H:i:s');
                $nestedData['action'] = "&emsp;<a href='javascript:void(0)' onclick='editData({$tk->id_tim_kerja})' class='btn btn-datatable btn-icon btn-transparent-dark me-2' title='Edit' data-bs-toggle='tooltip'><i data-feather='edit'></i></a>
                                        <a href='javascript:void(0)' onclick='deleteData({$tk->id_tim_kerja})' class='btn btn-datatable btn-icon btn-transparent-dark' title='Hapus' data-bs-toggle='tooltip'><i data-feather='trash-2'></i></a>";
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
     * Store a newly created resource in storage.
     */
    public function store(TimKerjaStoreRequest $request): JsonResponse
    {
        $timKerja = TimKerja::create($request->validated());

        if ($request->has('anggota_ids')) {
            $timKerja->users()->sync($request->anggota_ids);
        }

        // log activity
        $this->logActivity(
            'Tambah Tim Kerja',
            "Menambahkan tim kerja {$timKerja->nama_timkerja}",
            ['nama_timkerja' => $timKerja->nama_timkerja]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data tim kerja berhasil disimpan.',
            'data' => $timKerja,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimKerja $timKerja): JsonResponse
    {
        return response()->json($timKerja->load(['ketua', 'users']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimKerjaUpdateRequest $request, TimKerja $timKerja): JsonResponse
    {
        $timKerja->update($request->validated());

        if ($request->has('anggota_ids')) {
            $timKerja->users()->sync($request->anggota_ids);
        } else {
            $timKerja->users()->detach();
        }

        // log activity
        $this->logActivity(
            'Update Tim Kerja',
            "Mengupdate tim kerja {$timKerja->nama_timkerja}",
            ['nama_timkerja' => $timKerja->nama_timkerja]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data tim kerja berhasil diperbarui.',
            'data' => $timKerja,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimKerja $timKerja): JsonResponse
    {
        // log activity
        $this->logActivity(
            'Hapus Tim Kerja',
            "Menghapus tim kerja {$timKerja->nama_timkerja}",
            ['nama_timkerja' => $timKerja->nama_timkerja]
        );

        $timKerja->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data tim kerja berhasil dihapus.',
        ]);
    }
}
