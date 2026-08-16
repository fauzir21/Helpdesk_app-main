<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananKategori extends Model
{
    use HasFactory;

    protected $table = 'tb_layanan_kategoris';

    protected $fillable = [
        'layanan_id',
        'nama_kategori',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tb_layanan_kategori_user', 'layanan_kategori_id', 'user_id');
    }

    public function layananPersyaratan()
    {
        return $this->hasMany(LayananPersyaratan::class, 'layanan_kategori_id');
    }
}
