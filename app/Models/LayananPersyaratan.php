<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananPersyaratan extends Model
{
    use HasFactory;

    protected $table = 'tb_layanan_persyaratan';

    protected $fillable = [
        'layanan_id',
        'persyaratan_id',
        'layanan_kategori_id',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function persyaratan()
    {
        return $this->belongsTo(Persyaratan::class);
    }

    public function kategori()
    {
        return $this->belongsTo(LayananKategori::class, 'layanan_kategori_id');
    }
}
