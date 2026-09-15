<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSk extends Model
{
    protected $table = 'data_sk';
    
    protected $fillable = [
        'no_sk',
        'status_kepegawaian',
        'nama',
        'gelar',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'unit_kerja',
        'jabatan',
        'tgl_mulai',
        'gaji_pokok',
        'tunjangan_jabatan',
        'tunjangan_transport',
        'tunjangan_kinerja',
        'tunjangan_fungsional',
        'thp',
        'terbilang',
        'saksi1',
        'saksi2',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tgl_mulai' => 'date',
    ];
}

