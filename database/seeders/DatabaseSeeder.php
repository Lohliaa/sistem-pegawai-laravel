<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create staf user
        User::create([
            'username' => 'staf',
            'password' => Hash::make('staf123'),
            'role' => 'staf',
        ]);

        // Create kanit user
        User::create([
            'username' => 'kanit',
            'password' => Hash::make('kanit123'),
            'role' => 'kanit',
        ]);

        // Create kabid user
        User::create([
            'username' => 'kabid',
            'password' => Hash::make('kabid123'),
            'role' => 'kabid',
        ]);
    }
}
