<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => bcrypt('admin'),
            ]);
        }

        // Guru
        if (!User::where('email', 'guru@gmail.com')->exists()) {
            User::create([
                'name' => 'Pak Guru',
                'email' => 'guru@gmail.com',
                'role' => 'guru',
                'password' => bcrypt('guru'),
            ]);
        }

        // Siswa
        if (!User::where('email', 'siswa@gmail.com')->exists()) {
            User::create([
                'name' => 'Kaesang',
                'email' => 'siswa@gmail.com',
                'role' => 'siswa',
                'password' => bcrypt('siswa'),
            ]);
        }
    }
}
