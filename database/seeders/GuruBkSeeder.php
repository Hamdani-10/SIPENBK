<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\GuruBk;
use Illuminate\Support\Facades\DB;

class GuruBkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $guruUser = User::where('role', 'guru')->first();

    // Cek apakah sudah ada guru dengan user_id ini
    if (!DB::table('guru_bk')->where('user_id', $guruUser->id)->exists()) {
        GuruBk::create([
            'user_id' => $guruUser->id,
            'nama_guru_bk' => $guruUser->name, 
            'jabatan' => 'Guru BK',
            'no_whatsapp_guru_bk' => '-',
        ]);
    }
}
}
