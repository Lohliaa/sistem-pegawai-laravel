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
        Schema::create('pejabat_yayasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai')->nullOnDelete();
            $table->string('nama');
            $table->string('jabatan')->default('Ketua Yayasan Permata Mojokerto');
            $table->string('status')->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat_yayasan');
    }
};
