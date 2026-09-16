<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PenilaianKinerja extends Model
{
    protected $table = 'penilaian_kinerja';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPROVED = 'approved';

    public const STATUSES = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_SUBMITTED => 'Diajukan',
        self::STATUS_APPROVED => 'Disetujui',
    ];

    /**
     * Daftar aspek penilaian beserta kode & labelnya.
     */
    public const ASPEK = [
        'nilai_orientasi_pelayanan' => ['kode' => 'A', 'label' => 'Orientasi Pelayanan'],
        'nilai_integritas' => ['kode' => 'B', 'label' => 'Integritas'],
        'nilai_komitmen' => ['kode' => 'C', 'label' => 'Komitmen'],
        'nilai_disiplin' => ['kode' => 'D', 'label' => 'Disiplin'],
        'nilai_kerjasama' => ['kode' => 'E', 'label' => 'Kerjasama'],
        'nilai_kepemimpinan' => ['kode' => 'F', 'label' => 'Kepemimpinan'],
    ];

    /**
     * Rentang nilai yang diperbolehkan untuk setiap aspek.
     */
    public const NILAI_MIN = 0;
    public const NILAI_MAX = 4;

    protected $fillable = [
        'pegawai_id',
        'periode_id',
        'pejabat_penilai_id',
        'nilai_orientasi_pelayanan',
        'nilai_integritas',
        'nilai_komitmen',
        'nilai_disiplin',
        'nilai_kerjasama',
        'nilai_kepemimpinan',
        'nilai_total',
        'catatan',
        'status',
    ];

    protected $casts = [
        'nilai_orientasi_pelayanan' => 'integer',
        'nilai_integritas' => 'integer',
        'nilai_komitmen' => 'integer',
        'nilai_disiplin' => 'integer',
        'nilai_kerjasama' => 'integer',
        'nilai_kepemimpinan' => 'integer',
        'nilai_total' => 'decimal:2',
    ];

    // Relationships
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function periode()
    {
        return $this->belongsTo(PeriodePenilaian::class, 'periode_id');
    }

    public function pejabatPenilai()
    {
        return $this->belongsTo(PejabatPenilai::class, 'pejabat_penilai_id');
    }

    /**
     * Nama kolom nilai aspek.
     *
     * @return array<int, string>
     */
    public static function aspekColumns(): array
    {
        return array_keys(self::ASPEK);
    }

    /**
     * Hitung rata-rata nilai aspek yang terisi (0-4).
     *
     * @param  array<string, int|string|null>  $nilai
     */
    public static function hitungNilaiTotal(array $nilai): ?float
    {
        $terisi = array_filter($nilai, static function ($value) {
            return $value !== null && $value !== '';
        });

        if ($terisi === []) {
            return null;
        }

        return round(array_sum($terisi) / count($terisi), 2);
    }

    /**
     * Ambil seluruh nilai aspek dalam bentuk array.
     *
     * @return array<string, int|null>
     */
    public function nilaiAspek(): array
    {
        $nilai = [];

        foreach (self::aspekColumns() as $column) {
            $nilai[$column] = $this->{$column};
        }

        return $nilai;
    }

    /**
     * Predikat berdasarkan nilai total (0-4).
     *
     * @return array{label: string, kode: string}
     */
    public static function hitungPredikat(float|int|string|null $total): array
    {
        $total = (float) $total;

        return match (true) {
            $total >= 3.5 => ['label' => 'Sangat Baik', 'kode' => 'A'],
            $total >= 3 => ['label' => 'Baik', 'kode' => 'B'],
            $total >= 2 => ['label' => 'Cukup', 'kode' => 'C'],
            $total > 0 => ['label' => 'Kurang', 'kode' => 'D'],
            default => ['label' => '-', 'kode' => '-'],
        };
    }

    /**
     * Predikat berdasarkan nilai total.
     */
    public function getPredikatAttribute(): string
    {
        return self::hitungPredikat($this->nilai_total)['label'];
    }

    /**
     * Penilaian masih bisa diubah bila belum disetujui.
     */
    public function isEditable(): bool
    {
        return $this->status !== self::STATUS_APPROVED;
    }

    /**
     * Filter laporan/form penilaian.
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(! empty($filters['pegawai_id']), fn (Builder $q) => $q->where($q->qualifyColumn('pegawai_id'), $filters['pegawai_id']))
            ->when(! empty($filters['periode_id']), fn (Builder $q) => $q->where($q->qualifyColumn('periode_id'), $filters['periode_id']))
            ->when(! empty($filters['pejabat_penilai_id']), fn (Builder $q) => $q->where($q->qualifyColumn('pejabat_penilai_id'), $filters['pejabat_penilai_id']))
            ->when(! empty($filters['status']), fn (Builder $q) => $q->where($q->qualifyColumn('status'), $filters['status']));
    }
}