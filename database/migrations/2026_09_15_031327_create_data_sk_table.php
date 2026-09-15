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
        Schema::create('data_sk', function (Blueprint $table) {
            $table->id();
            $table->string('no_sk')->nullable();
            $table->string('status_kepegawaian')->nullable();
            $table->string('nama')->nullable();
            $table->string('gelar')->nullable();
            $table->text('alamat')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('jabatan')->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->string('gaji_pokok')->nullable();
            $table->string('tunjangan_jabatan')->nullable();
            $table->string('tunjangan_transport')->nullable();
            $table->string('tunjangan_kinerja')->nullable();
            $table->string('tunjangan_fungsional')->nullable();
            $table->string('thp')->nullable();
            $table->text('terbilang')->nullable();
            $table->string('saksi1')->nullable();
            $table->string('saksi2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sk');
    }
};
