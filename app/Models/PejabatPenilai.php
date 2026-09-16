<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PejabatPenilai extends Model
{
    protected $table = 'pejabat_penilai';

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';

    public const STATUSES = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_NONAKTIF => 'Nonaktif',
    ];

    protected $fillable = [
        'pegawai_id',
        'nama',
        'jabatan',
        'keterangan',
        'status_aktif',
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

    public function scopeAktif($query)
    {
        return $query->where('status_aktif', self::STATUS_AKTIF);
    }

    public function getNamaLengkapAttribute(): string
    {
        return trim(sprintf('%s%s', $this->nama, $this->jabatan ? ' - '.$this->jabatan : ''));
    }
}