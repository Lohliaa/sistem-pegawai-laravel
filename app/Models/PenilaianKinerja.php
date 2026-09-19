<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PenilaianKinerja extends Model
{
    protected $table = 'penilaian_kinerja';



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

    /**
     * Struktur rincian item penilaian berdasarkan kategori.
     *
     * @var array<string, array<int, array<string, mixed>>>
     */
    public const ITEMS_BY_CATEGORY = [
        'guru-alquran' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI AL-QUR\'AN (40%)'],
            ['type' => 'item', 'key' => 'tahsin', 'uraian' => 'Kualitas Tahsin/Fashohah'],
            ['type' => 'item', 'key' => 'tahfidz', 'uraian' => 'Capaian & Kualitas Tahfidz'],
            ['type' => 'item', 'key' => 'tajwid', 'uraian' => 'Pemahaman Ilmu Tajwid'],

            ['type' => 'section', 'label' => 'II. KOMITMEN & KEISLAMAN (30%)'],
            ['type' => 'item', 'key' => 'kehadiran', 'uraian' => 'Kedisiplinan & Kehadiran Halaqah'],
            ['type' => 'item', 'key' => 'tilawah', 'uraian' => 'Tilawah Harian & Shalat Berjamaah'],
            ['type' => 'item', 'key' => 'bpi', 'uraian' => 'Partisipasi Pembinaan (BPI)'],

            ['type' => 'section', 'label' => 'III. METODOLOGI & KINERJA (30%)'],
            ['type' => 'item', 'key' => 'metode', 'uraian' => 'Penguasaan Metode Pengajaran Al-Qur\'an'],
            ['type' => 'item', 'key' => 'administrasi', 'uraian' => 'Ketertiban Administrasi & Mutabaah Siswa'],
        ],
        'default' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI (25%)'],
            ['type' => 'item', 'key' => 'coaching', 'uraian' => 'Mengikuti kegiatan Coaching'],
            ['type' => 'item', 'key' => 'supervisi_kinerja', 'uraian' => 'Hasil Supervisi Kinerja'],
            ['type' => 'item', 'key' => 'kompetensi_ukg', 'uraian' => 'Hasil UKG/Uji Kompetensi'],

            ['type' => 'section', 'label' => 'II. KOMITMEN (35%)'],

            ['type' => 'sub', 'label' => 'A. KEDISIPLINAN'],
            ['type' => 'item', 'key' => 'kehadiran_sekolah', 'uraian' => 'Kehadiran di Sekolah'],
            ['type' => 'item', 'key' => 'hadir_tepat_waktu', 'uraian' => 'Hadir Tepat Waktu'],

            ['type' => 'sub', 'label' => 'B. KEISLAMAN'],
            ['type' => 'item', 'key' => 'shalat_awal_waktu', 'uraian' => 'Shalat Awal Waktu (bagi ustadzah) / Shalat berjamaah (bagi ustadz)'],
            ['type' => 'item', 'key' => 'tilawah_harian', 'uraian' => 'Tilawah minimal 1 juz per hari'],
            ['type' => 'item', 'key' => 'hafalan_1_juz', 'uraian' => 'Hafalan minimal 1 juz'],
            ['type' => 'item', 'key' => 'bpi_kehadiran', 'uraian' => 'Kehadiran Pembinaan Keislaman (BPI)', 'sub' => [
                ['uraian' => 'Selalu hadir'],
                ['uraian' => 'Mengikuti seluruh rangkaian kegiatan BPI'],
                ['uraian' => 'Berpartisipasi aktif (diskusi, dsb)'],
                ['uraian' => 'Mengikuti kegiatan pendukung BPI (JI/Mukhoyyam, dsb)'],
            ]],

            ['type' => 'sub', 'label' => 'C. PENGEMBANGAN DIRI'],
            ['type' => 'item', 'key' => 'bpi_implementasi', 'uraian' => 'Implementasi Materi BPI', 'sub' => [
                ['uraian' => 'Perilaku (verbal non-verbal)'],
                ['uraian' => 'Tata cara berpakaian'],
            ]],
            ['type' => 'item', 'key' => 'membaca_buku', 'uraian' => 'Membaca buku'],
            ['type' => 'item', 'key' => 'pelatihan', 'uraian' => 'Mengikuti pelatihan/pembekalan (diselenggarakan lembaga maupun ikut mandiri)'],

            ['type' => 'section', 'label' => 'III. KINERJA (40%)'],
            ['type' => 'item', 'key' => 'okr_individu', 'uraian' => 'Capaian OKR Individu', 'sub' => [
                ['uraian' => 'Melaksanakan Jobdes'],
                ['uraian' => 'Pendampingan akhlak siswa'],
                ['uraian' => 'Pendampingan sholat siswa'],
                ['uraian' => 'Pendampingan wudhu'],
            ]],
            ['type' => 'item', 'key' => 'kerja_harian', 'uraian' => 'Capaian Kerja Harian'],
        ],
    ];

    /**
     * Struktur rincian item penilaian (isi kolom uraian pada form penilaian).
     *
     * type: section = judul bagian utama, sub = judul sub bagian, item = baris nilai.
     *
     * @var array<int, array<string, mixed>>
     */
    public const ITEM = []; // Kept for backward compatibility, will be replaced by getItemsByCategory


    protected $fillable = [
        'pegawai_id',
        'periode_id',
        'pejabat_penilai_id',
        'status_kepegawaian_id',
        'nilai_orientasi_pelayanan',
        'nilai_integritas',
        'nilai_komitmen',
        'nilai_disiplin',
        'nilai_kerjasama',
        'nilai_kepemimpinan',
        'nilai_total',
        'jumlah_total',
        'detail_penilaian',
        'catatan',
        'status',
        'kategori',
    ];

    protected $casts = [
        'nilai_orientasi_pelayanan' => 'integer',
        'nilai_integritas' => 'integer',
        'nilai_komitmen' => 'integer',
        'nilai_disiplin' => 'integer',
        'nilai_kerjasama' => 'integer',
        'nilai_kepemimpinan' => 'integer',
        'nilai_total' => 'decimal:2',
        'detail_penilaian' => 'array',
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

    public function statusKepegawaian()
    {
        return $this->belongsTo(StatusKepegawaian::class, 'status_kepegawaian_id');
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
     * Ambil item penilaian berdasarkan kategori.
     */
    public static function getItems(string $kategori = 'pegawai'): array
    {
        return self::ITEMS_BY_CATEGORY[$kategori] ?? self::ITEMS_BY_CATEGORY['default'];
    }

    /**
     * Kunci seluruh item penilaian berdasarkan kategori.
     */
    public static function itemKeys(string $kategori = 'pegawai'): array
    {
        return collect(self::getItems($kategori))
            ->where('type', 'item')
            ->pluck('key')
            ->values()
            ->all();
    }

    /**
     * Ambil definisi item penilaian berdasarkan kunci dan kategori.
     */
    public static function itemByKey(string $key, string $kategori = 'pegawai'): ?array
    {
        foreach (self::getItems($kategori) as $item) {
            if (($item['type'] ?? '') === 'item' && ($item['key'] ?? '') === $key) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Rincian nilai tersimpan digabung dengan definisi uraian item.
     */
    public function detailItems(?string $kategori = null): array
    {
        $kategori = $kategori ?? $this->kategori ?? 'pegawai';
        $stored = is_array($this->detail_penilaian) ? $this->detail_penilaian : [];

        return collect(self::getItems($kategori))
            ->where('type', 'item')
            ->map(function (array $item) use ($stored) {
                $value = $stored[$item['key']] ?? [];

                $entry = array_merge($item, [
                    'nilai' => $value['nilai'] ?? null,
                    'catatan' => $value['catatan'] ?? null,
                ]);

                if (! empty($item['sub'])) {
                    $subEntries = [];
                    foreach ($item['sub'] as $i => $subDef) {
                        $subValue = $value['sub'][$i] ?? [];
                        $subEntries[] = [
                            'index' => $i,
                            'uraian' => $subDef['uraian'],
                            'nilai' => $subValue['nilai'] ?? null,
                            'catatan' => $subValue['catatan'] ?? null,
                        ];
                    }
                    $entry['sub'] = $subEntries;
                }
                return $entry;
            })
            ->values()
            ->all();
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
     * Filter laporan/form penilaian.
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(! empty($filters['pegawai_id']), fn(Builder $q) => $q->where($q->qualifyColumn('pegawai_id'), $filters['pegawai_id']))
            ->when(! empty($filters['periode_id']), fn(Builder $q) => $q->where($q->qualifyColumn('periode_id'), $filters['periode_id']))
            ->when(! empty($filters['pejabat_penilai_id']), fn(Builder $q) => $q->where($q->qualifyColumn('pejabat_penilai_id'), $filters['pejabat_penilai_id']))
            ->when(! empty($filters['status']), fn(Builder $q) => $q->where($q->qualifyColumn('status'), $filters['status']))
            ->when(! empty($filters['kategori']), function (Builder $q) use ($filters) {
                if ($filters['kategori'] === 'pegawai') {
                    $q->where(function ($subQ) {
                        $subQ->where('kategori', 'pegawai')
                             ->orWhereNull('kategori');
                    });
                } else {
                    $q->where('kategori', $filters['kategori']);
                }
            });
    }
}
