<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatDisposisi extends Model
{
    use HasFactory;

    protected $table = 'tb_riwayat_disposisi';

    protected $fillable = [
        'id_pengajuan',
        'nomor_tiket',
        'status',
        'tanggal_disposisi',
        'keterangan',
        'id_user',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
