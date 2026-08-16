<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSurveiKepuasan extends Model
{
    use HasFactory;

    protected $table = 'tb_master_survei_kepuasan';

    protected $fillable = [
        'nama_survei',
        'status',
    ];
}
