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
            $table->unsignedBigInteger('status_kepegawaian_id')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_kinerja', function (Blueprint $table) {
            $table->dropColumn('status_kepegawaian_id');
        });
    }
};
