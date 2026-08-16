<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'tb_dokumen';

    protected $fillable = [
        'id_pengajuan',
        'id_layanan_persyaratan',
        'file',
        'text',
        'status',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }

    public function layananPersyaratan()
    {
        return $this->belongsTo(LayananPersyaratan::class, 'id_layanan_persyaratan');
    }

    public function catatan()
    {
        return $this->hasOne(Catatan::class, 'id_dokumen')->latestOfMany();
    }

    public function catatans()
    {
        return $this->hasMany(Catatan::class, 'id_dokumen')->orderBy('created_at', 'desc');
    }
}
