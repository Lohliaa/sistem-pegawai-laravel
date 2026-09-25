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
        'default' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI (25%)'],
            ['type' => 'item', 'key' => 'hasil_supervisi', 'uraian' => 'Hasil Supervisi'],
            ['type' => 'item', 'key' => 'kegiatan_coaching', 'uraian' => 'Mengikuti kegiatan coaching'],
            ['type' => 'item', 'key' => 'hasil_ukg', 'uraian' => 'Hasil UKG/Uji Kompetensi'],

            ['type' => 'section', 'label' => 'II. KOMITMEN (35%)'],

            ['type' => 'sub', 'label' => 'A. KEDISIPLINANAN'],
            ['type' => 'item', 'key' => 'kehadiran_sekolah', 'uraian' => 'Kehadiran di Sekolah'],
            ['type' => 'item', 'key' => 'hadir_tepat_waktu', 'uraian' => 'Hadir Tepat Waktu'],

            ['type' => 'sub', 'label' => 'B. KEISLAMAN'],
            ['type' => 'item', 'key' => 'shalat_awal_waktu', 'uraian' => 'Shalat Awal Waktu (bagi ustadzah) /Berjamaah (bagi ustadz)'],
            ['type' => 'item', 'key' => 'tilawah_harian', 'uraian' => 'Tilawah minimal 1 juz per hari'],
            ['type' => 'item', 'key' => 'hafalan_1_juz', 'uraian' => 'Hafalan minimal 1 juz'],
            ['type' => 'item', 'key' => 'bpi_kehadiran', 'uraian' => 'Kehadiran Pembinaan Keislaman (BPI)', 'sub' => [
                ['uraian' => 'Selalu hadir'],
                ['uraian' => 'Mengikuti seluruh rangkaian (baramij) BPI'],
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
        'guru-alquran' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI (25%)'],
            ['type' => 'item', 'key' => 'hasil_supervisi', 'uraian' => 'Hasil Supervisi'],
            ['type' => 'item', 'key' => 'kegiatan_coaching', 'uraian' => 'Mengikuti kegiatan coaching'],
            ['type' => 'item', 'key' => 'hasil_ukg', 'uraian' => 'Hasil UKG/Uji Kompetensi'],

            ['type' => 'section', 'label' => 'II. KOMITMEN (35%)'],

            ['type' => 'sub', 'label' => 'A. KEDISIPLINAN'],
            ['type' => 'item', 'key' => 'kehadiran_sekolah', 'uraian' => 'Kehadiran di Sekolah'],
            ['type' => 'item', 'key' => 'hadir_tepat_waktu', 'uraian' => 'Hadir Tepat Waktu'],

            ['type' => 'sub', 'label' => 'B. KEISLAMAN'],
            ['type' => 'item', 'key' => 'shalat_awal_waktu', 'uraian' => 'Shalat Awal Waktu (bagi ustadzah) / Berjamaah (bagi ustadz)'],
            ['type' => 'item', 'key' => 'tilawah_harian', 'uraian' => 'Tilawah minimal 1 juz per hari'],
            ['type' => 'item', 'key' => 'hafalan_5_juz', 'uraian' => 'Hafalan minimal 5 juz'],
            ['type' => 'item', 'key' => 'bpi_kehadiran', 'uraian' => 'Kehadiran Pembinaan Keislaman (BPI)', 'sub' => [
                ['uraian' => 'Selalu hadir'],
                ['uraian' => 'Mengikuti seluruh rangkaian kegiatan BPI'],
                ['uraian' => 'Berpartisipasi aktif (diskusi, dsb)'],
                ['uraian' => 'Mengikuti kegiatan pendukung BPI (JI/Mukhoyyam, PPS, dsb)'],
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
        'guru-non-alquran' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI (25%)'],
            ['type' => 'item', 'key' => 'hasil_supervisi', 'uraian' => 'Hasil Supervisi'],
            ['type' => 'item', 'key' => 'kegiatan_coaching', 'uraian' => 'Mengikuti kegiatan coaching'],
            ['type' => 'item', 'key' => 'hasil_ukg', 'uraian' => 'Hasil UKG/Uji Kompetensi'],

            ['type' => 'section', 'label' => 'II. KOMITMEN (35%)'],

            ['type' => 'sub', 'label' => 'A. KEDISIPLINANAN (30%)'],
            ['type' => 'item', 'key' => 'kehadiran_sekolah', 'uraian' => 'Kehadiran di Sekolah'],
            ['type' => 'item', 'key' => 'hadir_tepat_waktu', 'uraian' => 'Hadir Tepat Waktu'],

            ['type' => 'sub', 'label' => 'B. KEISLAMAN (40%)'],
            ['type' => 'item', 'key' => 'shalat_awal_waktu', 'uraian' => 'Shalat Awal Waktu (bagi ustadzah) /Berjamaah di masjid (bagi ustadz)'],
            ['type' => 'item', 'key' => 'tilawah_harian', 'uraian' => 'Tilawah minimal 1 juz per hari'],
            ['type' => 'item', 'key' => 'hafalan_1_juz', 'uraian' => 'Hafalan minimal 1 juz'],
            ['type' => 'item', 'key' => 'bpi_kehadiran', 'uraian' => 'Kehadiran Pembinaan Keislaman (BPI)', 'sub' => [
                ['uraian' => 'Selalu hadir'],
                ['uraian' => 'Mengikuti seluruh rangkaian (baramij) BPI'],
                ['uraian' => 'Berpartisipasi aktif (diskusi, dsb)'],
                ['uraian' => 'Mengikuti kegiatan pendukung BPI (JI/Mukhoyyam, dsb)'],
            ]],

            ['type' => 'sub', 'label' => 'C. PENGEMBANGAN DIRI (30%)'],
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
        'leader' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI (25%)'],
            ['type' => 'item', 'key' => 'hasil_supervisi', 'uraian' => 'Hasil Supervisi Manajerial'],
            ['type' => 'item', 'key' => 'audit_mutu_internal', 'uraian' => 'Hasil Audit Mutu Internal'],

            ['type' => 'section', 'label' => 'II. KOMITMEN (35%)'],

            ['type' => 'sub', 'label' => 'A. KEDISIPLINAN'],
            ['type' => 'item', 'key' => 'kehadiran_sekolah', 'uraian' => 'Kehadiran di Sekolah'],
            ['type' => 'item', 'key' => 'hadir_tepat_waktu', 'uraian' => 'Hadir Tepat Waktu'],

            ['type' => 'sub', 'label' => 'B. KEISLAMAN'],
            ['type' => 'item', 'key' => 'shalat_awal_waktu', 'uraian' => 'Shalat Awal Waktu (bagi ustadzah) /Berjamaah (bagi ustadz)'],
            ['type' => 'item', 'key' => 'tilawah_harian', 'uraian' => 'Tilawah minimal 1 juz per hari'],
            ['type' => 'item', 'key' => 'hafalan_2_juz', 'uraian' => 'Hafalan minimal 2 juz'],
            ['type' => 'item', 'key' => 'bpi_kehadiran', 'uraian' => 'Kehadiran Pembinaan Keislaman (BPI)', 'sub' => [
                ['uraian' => 'Selalu hadir'],
                ['uraian' => 'Mengikuti seluruh rangkaian (baramij) BPI'],
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
            ['type' => 'item', 'key' => 'melaksanakan_coaching', 'uraian' => 'Melaksanakan coaching'],

            ['type' => 'section', 'label' => 'III. KINERJA (40%)'],
            ['type' => 'item', 'key' => 'okr_individu', 'uraian' => 'Capaian OKR Individu', 'sub' => [
                ['uraian' => 'Melaksanakan Jobdes'],
                ['uraian' => 'Pendampingan akhlak siswa'],
                ['uraian' => 'Pendampingan sholat siswa'],
                ['uraian' => 'Pendampingan wudhu'],
            ]],
            ['type' => 'item', 'key' => 'kerja_harian', 'uraian' => 'Capaian Kerja Harian'],
            ['type' => 'item', 'key' => 'okr_unit_bidang', 'uraian' => 'Capaian OKR unit/bidang'],
        ],
        'musyrifah' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI (25%) - sebagai tugas tambahan'],
            ['type' => 'item', 'key' => 'supervisi_pengasuhan_asrama', 'uraian' => 'Hasil Supervisi Pengasuhan Asrama'],

            ['type' => 'section', 'label' => 'II. KOMITMEN (35%)'],

            ['type' => 'sub', 'label' => 'A. KEDISIPLINANAN'],
            ['type' => 'item', 'key' => 'kehadiran_asrama', 'uraian' => 'Kehadiran di Asrama'],

            ['type' => 'sub', 'label' => 'B. KEISLAMAN'],
            ['type' => 'item', 'key' => 'shalat_awal_waktu', 'uraian' => 'Shalat Awal Waktu (bagi ustadzah) /Berjamaah (bagi ustadz)'],
            ['type' => 'item', 'key' => 'tilawah_harian', 'uraian' => 'Tilawah minimal 1 juz per hari'],
            ['type' => 'item', 'key' => 'hafalan_5_juz', 'uraian' => 'Hafalan minimal 5 juz'],
            ['type' => 'item', 'key' => 'bpi_kehadiran', 'uraian' => 'Kehadiran Pembinaan Keislaman (BPI)', 'sub' => [
                ['uraian' => 'Selalu hadir'],
                ['uraian' => 'Mengikuti seluruh rangkaian (baramij) BPI'],
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
                ['uraian' => 'Pendampingan akhlak santri'],
                ['uraian' => 'Pendampingan sholat santri'],
            ]],
            ['type' => 'item', 'key' => 'kerja_harian', 'uraian' => 'Capaian Kerja Harian'],
        ],

        'koordinator-jenjang' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI (25%)'],
            ['type' => 'item', 'key' => 'hasil_supervisi_manajerial', 'uraian' => 'Hasil Supervisi Manajerial'],
            ['type' => 'item', 'key' => 'kegiatan_coaching', 'uraian' => 'Mengikuti kegiatan coaching'],
            ['type' => 'item', 'key' => 'hasil_ukg', 'uraian' => 'Hasil UKG/Uji Kompetensi'],

            ['type' => 'section', 'label' => 'II. KOMITMEN (35%)'],

            ['type' => 'sub', 'label' => 'A. KEDISIPLINANAN'],
            ['type' => 'item', 'key' => 'kehadiran_sekolah', 'uraian' => 'Kehadiran di Sekolah'],
            ['type' => 'item', 'key' => 'hadir_tepat_waktu', 'uraian' => 'Hadir Tepat Waktu'],

            ['type' => 'sub', 'label' => 'B. KEISLAMAN'],
            ['type' => 'item', 'key' => 'shalat_awal_waktu', 'uraian' => 'Shalat Awal Waktu (bagi ustadzah) /Berjamaah (bagi ustadz)'],
            ['type' => 'item', 'key' => 'tilawah_harian', 'uraian' => 'Tilawah minimal 1 juz per hari'],
            ['type' => 'item', 'key' => 'hafalan_2_juz', 'uraian' => 'Hafalan minimal 2 juz'],
            ['type' => 'item', 'key' => 'bpi_kehadiran', 'uraian' => 'Kehadiran Pembinaan Keislaman (BPI)', 'sub' => [
                ['uraian' => 'Selalu hadir'],
                ['uraian' => 'Mengikuti seluruh rangkaian (baramij) BPI'],
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

        'koordinator-alquran' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI (25%)'],
            ['type' => 'item', 'key' => 'hasil_supervisi_manajerial', 'uraian' => '1. Hasil Supervisi Manajerial'],
            ['type' => 'item', 'key' => 'hasil_ukg', 'uraian' => '2. Hasil UKG/Uji Kompetensi'],

            ['type' => 'section', 'label' => 'II. KOMITMEN (35%)'],

            ['type' => 'sub', 'label' => 'A. KEDISIPLINANAN'],
            ['type' => 'item', 'key' => 'kehadiran_sekolah', 'uraian' => '1. Kehadiran di Sekolah'],
            ['type' => 'item', 'key' => 'hadir_tepat_waktu', 'uraian' => '2. Hadir Tepat Waktu'],

            ['type' => 'sub', 'label' => 'B. KEISLAMAN'],
            ['type' => 'item', 'key' => 'shalat_awal_waktu', 'uraian' => '1. Shalat Awal Waktu (bagi ustadzah) /Berjamaah (bagi ustadz)'],
            ['type' => 'item', 'key' => 'tilawah_harian', 'uraian' => '2. Tilawah minimal 1/2 juz per hari'],
            ['type' => 'item', 'key' => 'hafalan_5_juz', 'uraian' => '3. Hafalan minimal 5 juz'],
            ['type' => 'item', 'key' => 'bpi_kehadiran', 'uraian' => '4. Kehadiran Pembinaan Keislaman (BPI)', 'sub' => [
                ['uraian' => 'a. Selalu hadir'],
                ['uraian' => 'b. Mengikuti seluruh rangkaian (baramij) BPI'],
                ['uraian' => 'c. Berpartisipasi aktif (diskusi, dsb)'],
                ['uraian' => 'd. Mengikuti kegiatan pendukung BPI (JI/Mukhoyyam, dsb)'],
            ]],

            ['type' => 'sub', 'label' => 'C. PENGEMBANGAN DIRI'],
            ['type' => 'item', 'key' => 'bpi_implementasi', 'uraian' => '1. Implementasi Materi BPI', 'sub' => [
                ['uraian' => 'a. Perilaku (verbal non-verbal)'],
                ['uraian' => 'b. Tata cara berpakaian'],
            ]],
            ['type' => 'item', 'key' => 'membaca_buku', 'uraian' => '2. Membaca buku'],
            ['type' => 'item', 'key' => 'pelatihan', 'uraian' => '3. Mengikuti pelatihan/pembekalan (diselenggarakan lembaga maupun ikut mandiri)'],
            ['type' => 'item', 'key' => 'melaksanakan_coaching', 'uraian' => '4. Melaksanakan coaching'],

            ['type' => 'section', 'label' => 'III. KINERJA (40%)'],
            ['type' => 'item', 'key' => 'okr_individu', 'uraian' => '1. Capaian OKR Individu', 'sub' => [
                ['uraian' => 'a. Melaksanakan Jobdes'],
                ['uraian' => 'b. Pendampingan akhlak siswa'],
                ['uraian' => 'c. Pendampingan sholat siswa'],
                ['uraian' => 'd. Pendampingan wudhu'],
            ]],
            ['type' => 'item', 'key' => 'kerja_harian', 'uraian' => '2. Capaian Kerja Harian'],
        ],


        'wali-kelas-reguler' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI (25%)'],
            ['type' => 'item', 'key' => 'hasil_supervisi', 'uraian' => 'Hasil Supervisi'],
            ['type' => 'item', 'key' => 'kegiatan_coaching', 'uraian' => 'Mengikuti kegiatan coaching'],
            ['type' => 'item', 'key' => 'hasil_ukg', 'uraian' => 'Hasil UKG/Uji Kompetensi'],

            ['type' => 'section', 'label' => 'II. KOMITMEN (35%)'],

            ['type' => 'sub', 'label' => 'A. KEDISIPLINANAN'],
            ['type' => 'item', 'key' => 'kehadiran_sekolah', 'uraian' => 'Kehadiran di Sekolah'],
            ['type' => 'item', 'key' => 'hadir_tepat_waktu', 'uraian' => 'Hadir Tepat Waktu'],

            ['type' => 'sub', 'label' => 'B. KEISLAMAN'],
            ['type' => 'item', 'key' => 'shalat_awal_waktu', 'uraian' => 'Shalat Awal Waktu (bagi ustadzah) /Berjamaah (bagi ustadz)'],
            ['type' => 'item', 'key' => 'tilawah_harian', 'uraian' => 'Tilawah minimal 1 juz per hari'],
            ['type' => 'item', 'key' => 'hafalan_1_juz', 'uraian' => 'Hafalan minimal 1 juz'],
            ['type' => 'item', 'key' => 'bpi_kehadiran', 'uraian' => 'Kehadiran Pembinaan Keislaman (BPI)', 'sub' => [
                ['uraian' => 'Selalu hadir'],
                ['uraian' => 'Mengikuti seluruh rangkaian (baramij) BPI'],
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
        'cs' => [
            ['type' => 'section', 'label' => 'I. KOMPETENSI (25%)'],
            ['type' => 'item', 'key' => 'hasil_supervisi_kinerja', 'uraian' => 'Hasil Supervisi Kinerja'],

            ['type' => 'section', 'label' => 'II. KOMITMEN (35%)'],

            ['type' => 'sub', 'label' => 'A. KEDISIPLINANAN'],
            ['type' => 'item', 'key' => 'kehadiran_sekolah', 'uraian' => 'Kehadiran di Sekolah'],
            ['type' => 'item', 'key' => 'hadir_tepat_waktu', 'uraian' => 'Hadir Tepat Waktu'],

            ['type' => 'sub', 'label' => 'B. KEISLAMAN'],
            ['type' => 'item', 'key' => 'shalat_awal_waktu', 'uraian' => 'Shalat Awal Waktu (bagi ustadzah) /Berjamaah (bagi ustadz)'],
            ['type' => 'item', 'key' => 'tilawah_harian', 'uraian' => 'Tilawah minimal 1/2 juz per hari'],
            ['type' => 'item', 'key' => 'hafalan_setengah_juz', 'uraian' => 'Hafalan minimal 1/2 juz'],
            ['type' => 'item', 'key' => 'bpi_kehadiran', 'uraian' => 'Kehadiran Pembinaan Keislaman (BPI)', 'sub' => [
                ['uraian' => 'Selalu hadir'],
                ['uraian' => 'Mengikuti seluruh rangkaian (baramij) BPI'],
                ['uraian' => 'Berpartisipasi aktif (diskusi, dsb)'],
                ['uraian' => 'Mengikuti kegiatan pendukung BPI (JI/Mukhoyyam, dsb)'],
            ]],

            ['type' => 'sub', 'label' => 'C. PENGEMBANGAN DIRI'],
            ['type' => 'item', 'key' => 'bpi_implementasi', 'uraian' => 'Implementasi Materi BPI', 'sub' => [
                ['uraian' => 'Perilaku (verbal non-verbal)'],
                ['uraian' => 'Tata cara berpakaian'],
            ]],
            ['type' => 'item', 'key' => 'membaca_buku', 'uraian' => 'Membaca buku'],

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

    /**
     * Get weighted score components for Kompetensi aspect based on category.
     * 
     * @param string $kategori
     * @return array<string, float>
     */
    public static function getKompetensiWeights(string $kategori = 'pegawai'): array
    {
        return match ($kategori) {
            'koordinator-alquran' => [
                'hasil_supervisi_manajerial' => 0.50,
                'hasil_ukg' => 0.50,
            ],
            'leader' => [
                'hasil_supervisi' => 0.50,
                'audit_mutu_internal' => 0.50,
            ],
            'musyrifah' => [
                'supervisi_pengasuhan_asrama' => 1.00,
            ],
            'cs' => [
                'hasil_supervisi_kinerja' => 1.00,
            ],
            'koordinator-jenjang' => [
                'hasil_supervisi_manajerial' => 0.30,
                'kegiatan_coaching' => 0.40,
                'hasil_ukg' => 0.30,
            ],
            default => [
                'hasil_supervisi' => 0.30,
                'kegiatan_coaching' => 0.40,
                'hasil_ukg' => 0.30,
            ],
        };
    }

    /**
     * Get sub-aspect categories and their weights within an aspect.
     * 
     * @param string $aspect 'komitmen'
     * @return array<string, float>
     */
    public static function getSubAspectWeights(string $aspect): array
    {
        return match ($aspect) {
            'komitmen' => [
                'keislaman' => 0.40,
                'pengembangan_diri' => 0.30,
                'kedisiplinan' => 0.30,
            ],
            'kinerja' => [
                'okr_individu' => 0.65,
                'kerja_harian' => 0.35,
            ],
            default => [],
        };
    }

    /**
     * Hitung ringkasan nilai seluruh aspek berdasarkan rincian penilaian dan kategori.
     *
     * @param array<string, mixed> $detailPenilaian
     * @param string $kategori
     * @return array{total_kompetensi: float, total_komitmen: float, total_kinerja: float, total_seluruh_aspek: float, nilai_keseluruhan: float, nilai_keislaman: float, nilai_pengembangan_diri: float, nilai_kedisiplinan: float}
     */
    public static function hitungRingkasanNilai(array $detailPenilaian, string $kategori = 'pegawai'): array
    {
        $kompetensiItems = [];
        $komitmenSubItems = ['keislaman' => [], 'pengembangan_diri' => [], 'kedisiplinan' => []];
        $kinerjaSubItems = ['okr_individu' => [], 'kerja_harian' => []];

        $currentSection = '';
        $currentSubSection = '';

        foreach (self::getItems($kategori) as $row) {
            $type = $row['type'] ?? '';
            if ($type === 'section') {
                $labelUpper = strtoupper($row['label'] ?? '');
                if (stripos($labelUpper, 'KOMPETENSI') !== false) $currentSection = 'kompetensi';
                elseif (stripos($labelUpper, 'KOMITMEN') !== false) $currentSection = 'komitmen';
                elseif (stripos($labelUpper, 'KINERJA') !== false) $currentSection = 'kinerja';
                continue;
            }

            if ($type === 'sub') {
                $labelUpper = strtoupper($row['label'] ?? '');
                if (stripos($labelUpper, 'KEISLAMAN') !== false) $currentSubSection = 'keislaman';
                elseif (stripos($labelUpper, 'PENGEMBANGAN DIRI') !== false) $currentSubSection = 'pengembangan_diri';
                elseif (stripos($labelUpper, 'KEDISIPLINAN') !== false) $currentSubSection = 'kedisiplinan';
                continue;
            }

            if ($type !== 'item') continue;

            $key = $row['key'];
            $val = $detailPenilaian[$key]['nilai'] ?? null;

            if ($val !== null && $val !== '') {
                $val = (float) $val;
                if ($currentSection === 'kompetensi') {
                    $kompetensiItems[$key] = $val;
                } elseif ($currentSection === 'komitmen' && isset($komitmenSubItems[$currentSubSection])) {
                    $komitmenSubItems[$currentSubSection][] = $val;
                } elseif ($currentSection === 'kinerja' && isset($kinerjaSubItems[$key])) {
                    $kinerjaSubItems[$key][] = $val;
                }
            }
        }

        // 1. KOMPETENSI (25%)
        $kWeights = self::getKompetensiWeights($kategori);
        $totalKompetensiRaw = 0;
        $totalWeightK = 0;
        foreach ($kWeights as $k => $w) {
            if (isset($kompetensiItems[$k])) {
                $totalKompetensiRaw += $kompetensiItems[$k] * $w;
                $totalWeightK += $w;
            }
        }
        $totalKompetensi = $totalWeightK > 0 ? 0.25 * ($totalKompetensiRaw / $totalWeightK) : 0;

        // 2. KOMITMEN (35%)
        // Subaspek: Keislaman (40%), Pengembangan Diri (30%), Kedisiplinan (30%)
        // Hitung JUMLAH nilai seluruh uraian (bukan rata-rata)
        $sumKeislaman = count($komitmenSubItems['keislaman']) > 0 ? array_sum($komitmenSubItems['keislaman']) : 0;
        $sumPengembanganDiri = count($komitmenSubItems['pengembangan_diri']) > 0 ? array_sum($komitmenSubItems['pengembangan_diri']) : 0;
        $sumKedisiplinan = count($komitmenSubItems['kedisiplinan']) > 0 ? array_sum($komitmenSubItems['kedisiplinan']) : 0;

        $nilaiKeislaman = $sumKeislaman * 0.40;
        $nilaiPengembanganDiri = $sumPengembanganDiri * 0.30;
        $nilaiKedisiplinan = $sumKedisiplinan * 0.30;

        $nilaiKomitmen = $nilaiKeislaman + $nilaiPengembanganDiri + $nilaiKedisiplinan;
        $totalKomitmen = 0.35 * $nilaiKomitmen;

        // 3. KINERJA (40%)
        // Subaspek: OKR Individu (65%), Kerja Harian (35%)
        $kinerjaWeights = self::getSubAspectWeights('kinerja');
        $okrVal = count($kinerjaSubItems['okr_individu']) > 0 ? array_sum($kinerjaSubItems['okr_individu']) / count($kinerjaSubItems['okr_individu']) : 0;
        $kerjaVal = count($kinerjaSubItems['kerja_harian']) > 0 ? array_sum($kinerjaSubItems['kerja_harian']) / count($kinerjaSubItems['kerja_harian']) : 0;
        $totalKinerja = 0.40 * (($okrVal * ($kinerjaWeights['okr_individu'] ?? 0.65)) + ($kerjaVal * ($kinerjaWeights['kerja_harian'] ?? 0.35)));

        // 4. TOTAL NILAI SELURUH ASPEK (0-4)
        $totalSeluruhAspek = $totalKompetensi + $totalKomitmen + $totalKinerja;

        // 5. NILAI KESELURUHAN (0-100)
        // Nilai Keseluruhan = ((Total Kompetensi + Total Komitmen + Total Kinerja) / Total Seluruh Nilai Aspek) * 100
        $nilaiKeseluruhan = $totalSeluruhAspek > 0 ? ($totalSeluruhAspek / $totalSeluruhAspek) * 100 : 0;

        return [
            'total_kompetensi' => round($totalKompetensi, 2),
            'total_komitmen' => round($totalKomitmen, 2),
            'total_kinerja' => round($totalKinerja, 2),
            'total_seluruh_aspek' => round($totalSeluruhAspek, 2),
            'nilai_keseluruhan' => round($nilaiKeseluruhan, 2),
            'nilai_keislaman' => round($nilaiKeislaman, 2),
            'nilai_pengembangan_diri' => round($nilaiPengembanganDiri, 2),
            'nilai_kedisiplinan' => round($nilaiKedisiplinan, 2),
        ];
    }


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
            })
            ->when(auth()->check() && auth()->user()->role === 'staf', function (Builder $q) {
                $q->whereHas('pegawai', function ($pegawaiQ) {
                    $pegawaiQ->where('user_id', auth()->id());
                });
            })
            ->when(auth()->check() && auth()->user()->role !== 'admin' && PejabatPenilai::where('pegawai_id', auth()->user()->pegawai_id)->exists(), function (Builder $q) {
                $pejabat = PejabatPenilai::where('pegawai_id', auth()->user()->pegawai_id)->first();
                $q->where($q->qualifyColumn('pejabat_penilai_id'), $pejabat->id);
            });
    }
}
