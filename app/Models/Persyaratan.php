<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persyaratan extends Model
{
    use HasFactory;

    protected $table = 'tb_persyaratan';

    protected $fillable = [
        'nama_persyaratan',
        'deskripsi',
        'tipe',
        'wajib',
        'status',
    ];

    protected $casts = [
        'wajib' => 'boolean',
    ];

    public function getTipeLabelAttribute()
    {
        return $this->tipe === 'file' ? 'File' : 'Text';
    }

    public function getWajibLabelAttribute()
    {
        return $this->wajib ? 'Wajib' : 'Opsional';
    }

    public function getStatusLabelAttribute()
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Nonaktif';
    }
}
