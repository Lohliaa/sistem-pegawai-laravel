<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    
    protected $fillable = [
        'user_id',
        'nama',
        'foto',
        'nik',
        'nomor_kk',
        'tempat',
        'tanggal_lahir',
        'gender',
        'status_pernikahan',
        'alamat',
        'no_hp',
        'email',
        'unit',
        'jabatan',
        'jenis_tenaga',
        'status_kepegawaian_id',
        'tanggal_tmt',
        'golongan_ruang',
        'atasan_langsung',
        'nomor_sk',
        'tanggal_sk',
        'masa_kerja',
        'status_aktif',
        'pendidikan_terakhir',
        'jurusan',
        'institusi',
        'tahun_lulus',
        'sertifikasi',
        'pelatihan',
        'dokumen_ktp',
        'dokumen_kk',
        'dokumen_ijazah',
        'dokumen_sk',
        'dokumen_mou',
        'dokumen_sk_jabatan',
        'dokumen_skck',
        'dokumen_sertifikat',
        'data_bpi',
        'data_presensi',
        'data_cuti',
        'riwayat_jabatan',
        'riwayat_mutasi',
        'riwayat_status_kepegawaian',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_tmt' => 'date',
        'tanggal_sk' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusKepegawaian()
    {
        return $this->belongsTo(StatusKepegawaian::class, 'status_kepegawaian_id');
    }

    public function pejabatPenilai()
    {
        return $this->hasOne(PejabatPenilai::class);
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianKinerja::class);
    }
}

