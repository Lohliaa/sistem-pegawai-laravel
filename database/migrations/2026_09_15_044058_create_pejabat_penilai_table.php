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
        Schema::create('pejabat_penilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai')->onDelete('set null');
            $table->string('nama', 100);
            $table->string('jabatan', 50);
            $table->string('keterangan')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('unit', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat_penilai');
    }
};
