<?php

namespace App\Models;

use Database\Factories\DokumenHasilFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenHasil extends Model
{
    /** @use HasFactory<DokumenHasilFactory> */
    use HasFactory;

    protected $table = 'tb_dokumen_hasil';

    protected $fillable = [
        'id_pengajuan',
        'nama_file',
        'path',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }
}
