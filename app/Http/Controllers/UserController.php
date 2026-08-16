<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\TimKerja;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timKerja = TimKerja::all();

        return view('admin.users.index', compact('timKerja'));
    }

    public function data(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'tipe',
            4 => 'id', // Tim Kerja - non-orderable in UI
            5 => 'status',
            6 => 'id',
        ];

        $totalData = User::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $orderColumn = $request->input('order.0.column', 0);
        $order = $columns[$orderColumn] ?? 'id';

        $dir = $request->input('order.0.direction', 'desc');
        if (! in_array(strtolower($dir), ['asc', 'desc'])) {
            $dir = 'desc';
        }

        $query = User::with('timKerjas');

        if (! empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('created_at', 'LIKE', "%{$search}%")
                    ->orWhere('tipe', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");
            });

            $totalFiltered = $query->count();
        }

        $users = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];
        $no = $start + 1;
        if (! empty($users)) {
            foreach ($users as $user) {
                $editUrl = route('user.edit', $user->id);
                $deleteUrl = route('user.destroy', $user->id);

                $nestedData['DT_RowIndex'] = $no++;
                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['created_at'] = $user->created_at->format('Y-m-d H:i:s');

                $tipeBadge = match (strtolower($user->tipe)) {
                    'admin' => '<span class="badge bg-danger">Admin</span>',
                    'pegawai' => '<span class="badge bg-success">Pegawai</span>',
                    'helpdesk' => '<span class="badge bg-warning">Layanan</span>',
                    default => '<span class="badge bg-primary">User</span>',
                };

                $kategoriBadge = '';
                if ($user->tipe === 'users') {
                    $kategoriBadge = match ($user->kategori_user) {
                        'pemerintah' => ' <span class="badge bg-info">Pemerintah</span>',
                        default => ' <span class="badge bg-secondary">Umum</span>',
                    };
                }
                $nestedData['tipe'] = $tipeBadge.$kategoriBadge;

                $statusBadge = strtolower($user->status) === 'aktif' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>';
                $nestedData['status'] = $statusBadge;

                if ($user->timKerjas->isEmpty()) {
                    $nestedData['tim_kerja_id'] = '<span class="badge bg-warning text-dark">Tidak Memiliki Tim Kerja</span>';
                } else {
                    $teams = $user->timKerjas->map(function ($team) {
                        return '<span class="badge bg-outline-primary text-primary border border-primary me-1 mb-1">'.$team->nama_timkerja.'</span>';
                    })->implode('');
                    $nestedData['tim_kerja_id'] = $teams;
                }
                $nestedData['action'] = "&emsp;<a href='javascript:void(0)' onclick='editData({$user->id})' class='btn btn-datatable btn-icon btn-transparent-dark me-2' title='Edit' data-bs-toggle='tooltip'><i data-feather='edit'></i></a>
                                        <a href='javascript:void(0)' onclick='deleteData({$user->id})' class='btn btn-datatable btn-icon btn-transparent-dark' title='Hapus' data-bs-toggle='tooltip'><i data-feather='trash-2'></i></a>";
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
    public function store(UserStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        if ($validated['tipe'] !== 'users') {
            $validated['kategori_user'] = null;
        }
        $user = User::create($validated);

        if (! empty($validated['tim_kerja_id'])) {
            $user->timKerjas()->sync($validated['tim_kerja_id']);
        }

        $this->logActivity(
            'Tambah User',
            "Menambahkan user baru: {$user->name} ({$user->email})",
            ['user_id' => $user->id, 'name' => $user->name, 'email' => $user->email]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil disimpan.',
            'data' => $user,
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
    public function edit(User $user): JsonResponse
    {
        return response()->json($user->load('timKerjas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();
        if (empty($validated['password'])) {
            unset($validated['password']);
        }
        if ($validated['tipe'] !== 'users') {
            $validated['kategori_user'] = null;
        }
        $user->update($validated);

        if (isset($validated['tim_kerja_id'])) {
            $user->timKerjas()->sync($validated['tim_kerja_id']);
        } else {
            $user->timKerjas()->detach();
        }

        $this->logActivity(
            'Update User',
            "Memperbarui data user: {$user->name} ({$user->email})",
            ['user_id' => $user->id, 'name' => $user->name, 'email' => $user->email]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diperbarui.',
            'data' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $userName = $user->name;
        $userEmail = $user->email;
        $userId = $user->id;

        $user->delete();

        $this->logActivity(
            'Hapus User',
            "Menghapus user: {$userName} ({$userEmail})",
            ['user_id' => $userId, 'name' => $userName, 'email' => $userEmail]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil dihapus.',
        ]);
    }
}
