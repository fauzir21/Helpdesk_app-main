<?php

namespace App\Http\Controllers;

use App\Models\MasterSurveiKepuasan;
use App\Models\Pengajuan;
use App\Models\SurveiKepuasanMasyarakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveiKepuasanMasyarakatController extends Controller
{
    public function show(Pengajuan $pengajuan)
    {
        // Ensure the application is finished and belongs to the user
        if ($pengajuan->id_user !== Auth::id() || $pengajuan->status_pengajuan !== 'SELESAI') {
            abort(403);
        }

        // Ensure user hasn't filled survey for this application yet
        $alreadyFilled = SurveiKepuasanMasyarakat::where('id_pengajuan', $pengajuan->id)->exists();
        if ($alreadyFilled) {
            return redirect()->route('permohonan.index')->with('success', 'Anda sudah mengisi survei untuk permohonan ini.');
        }

        $questions = MasterSurveiKepuasan::where('status', 'aktif')->get();

        return view('permohonan.survei', compact('pengajuan', 'questions'));
    }

    public function store(Request $request, Pengajuan $pengajuan)
    {
        if ($pengajuan->id_user !== Auth::id() || $pengajuan->status_pengajuan !== 'SELESAI') {
            abort(403);
        }

        $questions = MasterSurveiKepuasan::where('status', 'aktif')->get();

        $rules = [];
        foreach ($questions as $q) {
            $rules['nilai.'.$q->id] = 'required|integer|between:1,6';
        }

        $request->validate($rules, [
            'nilai.*.required' => 'Harap isi semua penilaian.',
        ]);

        foreach ($request->nilai as $masterId => $nilai) {
            SurveiKepuasanMasyarakat::create([
                'id_user' => Auth::id(),
                'id_pengajuan' => $pengajuan->id,
                'id_master_survei_kepuasan' => $masterId,
                'nilai' => $nilai,
            ]);
        }

        return redirect()->route('permohonan.index')->with('success', 'Terima kasih telah mengisi survei kepuasan masyarakat.');
    }
}
