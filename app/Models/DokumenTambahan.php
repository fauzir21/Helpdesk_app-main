<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenTambahan extends Model
{
    use HasFactory;

    protected $table = 'tb_dokumen_tambahan';

    protected $fillable = [
        'id_pengajuan',
        'nama_dokumen',
        'file',
        'status',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }

    public function catatan()
    {
        return $this->hasOne(Catatan::class, 'id_dokumen_tambahan')->latestOfMany();
    }

    public function catatans()
    {
        return $this->hasMany(Catatan::class, 'id_dokumen_tambahan')->orderBy('created_at', 'desc');
    }
}
