<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'tb_pengajuan';

    protected $fillable = [
        'id_layanan',
        'id_user',
        'status_pengajuan',
        'nomor_tiket',
        'tanggal_pengajuan',
        'tanggal_selesai',
        'deadline',
        'detail_tambahan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
            'tanggal_selesai' => 'date',
            'deadline' => 'date',
            'detail_tambahan' => 'array',
        ];
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatDisposisi::class, 'id_pengajuan');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_pengajuan');
    }

    public function dokumenTambahan()
    {
        return $this->hasMany(DokumenTambahan::class, 'id_pengajuan');
    }

    public function dokumenHasil()
    {
        return $this->hasMany(DokumenHasil::class, 'id_pengajuan');
    }

    public function surveiKepuasan()
    {
        return $this->hasMany(SurveiKepuasanMasyarakat::class, 'id_pengajuan');
    }

    /**
     * Check if all requirements and additional documents are approved (status SELESAI).
     */
    public function isAllDocumentsApproved(): bool
    {
        // 1. Check Requirements (tb_dokumen)
        // Count how many requirements are mandatory for this service
        $requiredCount = $this->layanan->layananPersyaratan()->count();

        // Count how many documents are uploaded and approved
        $approvedDocsCount = $this->dokumen()->where('status', 'SELESAI')->count();

        // If not all requirements have been uploaded or some are not approved, return false
        if ($approvedDocsCount < $requiredCount) {
            return false;
        }

        // 2. Check Additional Documents (tb_dokumen_tambahan)
        // If there are additional documents, they must all be SELESAI
        $hasUnfinishedAdditional = $this->dokumenTambahan()
            ->where('status', '!=', 'SELESAI')
            ->exists();

        if ($hasUnfinishedAdditional) {
            return false;
        }

        return true;
    }
}
