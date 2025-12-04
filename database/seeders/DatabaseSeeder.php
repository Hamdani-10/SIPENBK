<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder-seeder yang sudah dibuat
        $this->call([
            UserSeeder::class,
            GuruBkSeeder::class,
            SiswaSeeder::class,
            JadwalSeeder::class,
        ]);
    }
}
