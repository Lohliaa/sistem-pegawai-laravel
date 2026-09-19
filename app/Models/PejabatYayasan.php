<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PejabatYayasan extends Model
{
    protected $table = 'pejabat_yayasan';

    protected $fillable = [
        'pegawai_id',
        'nama',
        'jabatan',
        'status',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}

