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
        Schema::table('pejabat_penilai', function (Blueprint $table) {
            if (Schema::hasColumn('pejabat_penilai', 'status_aktif')) {
                $table->dropColumn('status_aktif');
            }
            if (!Schema::hasColumn('pejabat_penilai', 'status')) {
                $table->string('status', 50)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pejabat_penilai', function (Blueprint $table) {
            if (Schema::hasColumn('pejabat_penilai', 'status')) {
                $table->dropColumn('status');
            }
            if (!Schema::hasColumn('pejabat_penilai', 'status_aktif')) {
                $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif');
            }
        });
    }
};
