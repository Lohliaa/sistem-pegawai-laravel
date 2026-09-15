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
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_pengajuan', 50);
            $table->string('nama', 100);
            $table->date('tanggal_lahir');
            $table->date('tanggal_tmt');
            $table->string('unit', 50);
            $table->text('keterangan')->nullable();
            $table->string('status', 50)->default('pending');
            
            // Data Pengaju
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Data Persetujuan Kanit
            $table->foreignId('approved_by_kanit')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_date_kanit')->nullable();
            $table->text('catatan_kanit')->nullable();
            $table->foreignId('rejected_by_kanit')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_date_kanit')->nullable();
            $table->text('rejected_reason')->nullable();
            
            // Data Persetujuan Kabid
            $table->foreignId('approved_by_kabid')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_date_kabid')->nullable();
            $table->text('catatan_kabid')->nullable();
            
            // Data Pemrosesan Admin
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_date')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('completed_date')->nullable();
            $table->text('catatan_admin')->nullable();
            
            $table->timestamps();
            
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
