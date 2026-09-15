<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataMou extends Model
{
    protected $table = 'data_mou';
    
    protected $fillable = [
        'no_sk',
        'no_tambahan',
        'status_kepegawaian',
        'status_detail',
        'nama',
        'gelar',
        'hari_kerja',
        'jam_kerja',
        'alamat',
        'hari',
        'tgl_mou',
        'tempat_lahir',
        'tanggal_lahir',
        'unit_kerja',
        'gaji_pokok',
        'tunjangan_jabatan',
        'tunjangan_transport',
        'tunjangan_kinerja',
        'tunjangan_fungsional',
        'thp',
        'terbilang',
        'tgl_mulai',
        'berlaku',
        'tanggal_akhir',
        'saksi1',
        'saksi2',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tgl_mulai' => 'date',
        'tanggal_akhir' => 'date',
    ];
}

