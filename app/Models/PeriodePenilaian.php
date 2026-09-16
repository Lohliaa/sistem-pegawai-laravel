<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodePenilaian extends Model
{
    protected $table = 'periode_penilaian';

    /**
     * Pilihan nama kuartal yang tersedia.
     */
    public const KUARTAL = ['Q1', 'Q2', 'Q3', 'Q4'];

    protected $fillable = [
        'nama_kuartal',
        'periode_bulan',
        'tahun',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    // Relationships
    public function penilaian()
    {
        return $this->hasMany(PenilaianKinerja::class, 'periode_id');
    }

    /**
     * Label periode, contoh: "Q1 (Januari - Maret 2026)".
     */
    public function getLabelAttribute(): string
    {
        return trim(sprintf('%s (%s %s)', $this->nama_kuartal, $this->periode_bulan, $this->tahun));
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('tahun')->orderBy('nama_kuartal');
    }
}