<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('status_kepegawaian', function (Blueprint $table) {
            $table->id();
            $table->string('nama_status', 50)->unique();
            $table->timestamps();
        });

        // Insert data default
        DB::table('status_kepegawaian')->insert([
            ['nama_status' => 'Mitra', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Honorer', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Magang', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'CPT', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'CGT', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'GT', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'PT', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_kepegawaian');
    }
};
