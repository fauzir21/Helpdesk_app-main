<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{
    use HasFactory;

    protected $table = 'tb_catatan';

    protected $fillable = [
        'id_dokumen',
        'id_dokumen_tambahan',
        'catatan',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen');
    }

    public function dokumenTambahan()
    {
        return $this->belongsTo(DokumenTambahan::class, 'id_dokumen_tambahan');
    }
}
