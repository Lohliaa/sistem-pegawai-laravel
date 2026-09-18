<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->string('foto')->nullable();
            $table->string('nik')->nullable();
            $table->string('nomor_kk')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();

            $table->string('jenis_tenaga')->nullable();
            $table->unsignedBigInteger('status_kepegawaian_id')->nullable();
            $table->string('golongan_ruang')->nullable();
            $table->string('atasan_langsung')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('masa_kerja')->nullable();
            $table->string('status_aktif')->default('Aktif');

            $table->string('pendidikan_terakhir')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('institusi')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->text('sertifikasi')->nullable();
            $table->text('pelatihan')->nullable();

            $table->string('dokumen_ktp')->nullable();
            $table->string('dokumen_kk')->nullable();
            $table->string('dokumen_ijazah')->nullable();
            $table->string('dokumen_sk')->nullable();
            $table->string('dokumen_mou')->nullable();
            $table->string('dokumen_sk_jabatan')->nullable();
            $table->string('dokumen_skck')->nullable();
            $table->string('dokumen_sertifikat')->nullable();

            $table->text('data_bpi')->nullable();
            $table->text('data_presensi')->nullable();
            $table->text('data_cuti')->nullable();
            $table->text('riwayat_jabatan')->nullable();
            $table->text('riwayat_mutasi')->nullable();
            $table->text('riwayat_status_kepegawaian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn([
                'foto', 'nik', 'nomor_kk', 'status_pernikahan', 'no_hp', 'email',
                'jenis_tenaga', 'status_kepegawaian_id', 'golongan_ruang', 'atasan_langsung',
                'nomor_sk', 'tanggal_sk', 'masa_kerja', 'status_aktif',
                'pendidikan_terakhir', 'jurusan', 'institusi', 'tahun_lulus', 'sertifikasi', 'pelatihan',
                'dokumen_ktp', 'dokumen_kk', 'dokumen_ijazah', 'dokumen_sk', 'dokumen_mou',
                'dokumen_sk_jabatan', 'dokumen_skck', 'dokumen_sertifikat',
                'data_bpi', 'data_presensi', 'data_cuti',
                'riwayat_jabatan', 'riwayat_mutasi', 'riwayat_status_kepegawaian'
            ]);
        });
    }
};
