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
        Schema::dropIfExists('data_sk');
        Schema::create('data_sk', function (Blueprint $table) {
            $table->id();
            $table->string('no_sk')->nullable();
            $table->string('no_tambahan')->nullable();
            $table->string('nama')->nullable();
            $table->string('gelar')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nipy')->nullable();
            $table->string('gol_ruang')->nullable();
            $table->string('status_kepegawaian')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('tmt')->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->integer('berlaku')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->date('tanggal_ditetapkan')->nullable();
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
