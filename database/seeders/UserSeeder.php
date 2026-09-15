<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default users
        \App\Models\User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'username' => 'staf',
            'password' => bcrypt('staf123'),
            'role' => 'staf',
        ]);

        \App\Models\User::create([
            'username' => 'kanit',
            'password' => bcrypt('kanit123'),
            'role' => 'kanit',
        ]);

        \App\Models\User::create([
            'username' => 'kabid',
            'password' => bcrypt('kabid123'),
            'role' => 'kabid',
        ]);
    }
}
