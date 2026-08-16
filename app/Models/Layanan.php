<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'tb_layanan';

    protected $fillable = [
        'tim_kerja_id',
        'nama_layanan',
        'slug',
        'deskripsi',
        'status',
        'durasi_hari',
        'user_category',
        'input_tambahan',
    ];

    protected function casts(): array
    {
        return [
            'input_tambahan' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($layanan) {
            $layanan->slug = Str::slug($layanan->nama_layanan);
        });

        static::updating(function ($layanan) {
            if ($layanan->isDirty('nama_layanan')) {
                $layanan->slug = Str::slug($layanan->nama_layanan);
            }
        });
    }

    public function timKerja()
    {
        return $this->belongsTo(TimKerja::class, 'tim_kerja_id');
    }

    public function kategori()
    {
        return $this->hasMany(LayananKategori::class, 'layanan_id');
    }

    public function persyaratan()
    {
        return $this->belongsToMany(Persyaratan::class, 'tb_layanan_persyaratan', 'layanan_id', 'persyaratan_id')->withPivot('id');
    }

    public function layananPersyaratan()
    {
        return $this->hasMany(LayananPersyaratan::class, 'layanan_id');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'id_layanan');
    }
}
