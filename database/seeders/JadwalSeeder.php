<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\GuruBk;
use Illuminate\Support\Carbon;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $guru = GuruBk::first();
        $siswa = Siswa::first();

        if (!$guru || !$siswa) {
            $this->command->warn('Guru BK atau Siswa belum tersedia. Seeder Jadwal dilewati.');
            return;
        }

        Jadwal::create([
            'id_guru_bk' => $guru->id,
            'id_siswa' => $siswa->id,
            'nama_siswa' => $siswa->nama_siswa,
            'tanggal' => Carbon::now()->addDays(1)->toDateString(),
            'waktu' => '10:00:00',
            'kategori' => 'Konseling Akademik',
            'dibuat_oleh' => 'guru',
            'tujuan' => 'siswa',
            'status' => 'tersedia',
            'no_whatsapp_guru_bk' => $guru->no_whatsapp_guru_bk,
            'no_whatsapp_siswa' => $siswa->no_whatsapp_siswa,
            'no_whatsapp_ortu' => $siswa->no_whatsapp_ortu,
            'notifikasi' => false,
        ]);
    }
}
