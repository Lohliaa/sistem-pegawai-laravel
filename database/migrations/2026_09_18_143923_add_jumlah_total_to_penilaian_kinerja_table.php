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
        Schema::table('penilaian_kinerja', function (Blueprint $table) {
            $table->decimal('jumlah_total', 8, 2)->nullable()->after('nilai_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_kinerja', function (Blueprint $table) {
            $table->dropColumn('jumlah_total');
        });
    }
};
