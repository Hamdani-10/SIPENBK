<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user dengan role siswa
        $siswaUser = User::where('role', 'siswa')->first();

        // Cek apakah siswa dengan user_id ini sudah ada
        if (!Siswa::where('user_id', $siswaUser->id)->exists()) {
            Siswa::create([
                'user_id' => $siswaUser->id,
                'nisn' => '-',
                'nama_siswa' => $siswaUser->name,
                'kelas' => '-',
                'no_whatsapp_siswa' => '-',
                'nama_ortu' => '-',
                'no_whatsapp_ortu' => '-',
            ]);
        }
    }
}
