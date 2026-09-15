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
        Schema::create('penilaian_kinerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periode_penilaian')->onDelete('cascade');
            $table->foreignId('pejabat_penilai_id')->constrained('pejabat_penilai')->onDelete('cascade');
            $table->integer('nilai_orientasi_pelayanan')->nullable();
            $table->integer('nilai_integritas')->nullable();
            $table->integer('nilai_komitmen')->nullable();
            $table->integer('nilai_disiplin')->nullable();
            $table->integer('nilai_kerjasama')->nullable();
            $table->integer('nilai_kepemimpinan')->nullable();
            $table->decimal('nilai_total', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_kinerja');
    }
};
