<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pejabat;

class PejabatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pejabat::insert([
            ['nama' => 'Dr. H. Ahmad Fauzi, M.Pd.', 'jabatan' => 'Kepala Bidang Pendidikan'],
            ['nama' => 'Dra. Hj. Siti Aminah', 'jabatan' => 'Kepala Unit TKIT'],
            ['nama' => 'Budi Santoso, S.Kom.', 'jabatan' => 'Kepala Unit SDIT'],
            ['nama' => 'Dewi Lestari, M.Pd.', 'jabatan' => 'Kepala Unit SMPIT'],
        ]);
    }
}
