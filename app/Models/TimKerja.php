<?php

namespace App\Models;

use Database\Factories\TimKerjaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimKerja extends Model
{
    /** @use HasFactory<TimKerjaFactory> */
    use HasFactory;

    protected $table = 'tb_tim_kerja';

    protected $primaryKey = 'id_tim_kerja';

    protected $fillable = [
        'nama_timkerja',
        'ketua_id',
    ];

    public function ketua()
    {
        return $this->belongsTo(User::class, 'ketua_id');
    }

    public function layanan()
    {
        return $this->hasMany(Layanan::class, 'tim_kerja_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tb_tim_kerja_user', 'tim_kerja_id', 'user_id');
    }
}
