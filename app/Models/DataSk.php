<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSk extends Model
{
    protected $table = 'data_sk';
    
    protected $fillable = [
        'no_sk',
        'no_tambahan',
        'nama',
        'gelar',
        'tempat_lahir',
        'tanggal_lahir',
        'nipy',
        'gol_ruang',
        'status_kepegawaian',
        'unit_kerja',
        'tmt',
        'tgl_mulai',
        'berlaku',
        'tanggal_akhir',
        'tanggal_ditetapkan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tmt' => 'date',
        'tgl_mulai' => 'date',
        'tanggal_akhir' => 'date',
        'tanggal_ditetapkan' => 'date',
    ];
}

