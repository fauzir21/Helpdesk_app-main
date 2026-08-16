<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiKepuasanMasyarakat extends Model
{
    use HasFactory;

    protected $table = 'tb_survei_kepuasan_masyarakat';

    protected $fillable = [
        'id_user',
        'id_pengajuan',
        'id_master_survei_kepuasan',
        'nilai',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }

    public function masterSurvei()
    {
        return $this->belongsTo(MasterSurveiKepuasan::class, 'id_master_survei_kepuasan');
    }
}
