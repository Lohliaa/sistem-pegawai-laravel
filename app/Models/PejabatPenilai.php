<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PejabatPenilai extends Model
{
    protected $table = 'pejabat_penilai';

    protected $fillable = [
        'pegawai_id',
        'nama',
        'jabatan',
        'keterangan',
        'status',
        'unit',
    ];

    // Relationships
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianKinerja::class, 'pejabat_penilai_id');
    }

    public function getNamaLengkapAttribute(): string
    {
        return trim(sprintf('%s%s', $this->nama, $this->jabatan ? ' - '.$this->jabatan : ''));
    }
}