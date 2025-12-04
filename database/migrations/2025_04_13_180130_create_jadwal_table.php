<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalTable extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();

            // Relasi ke guru_bk dan siswa
            $table->foreignId('id_guru_bk')->constrained('guru_bk', 'id')->onDelete('cascade');
            $table->foreignId('id_siswa')->constrained('siswa', 'id')->onDelete('cascade');

            // Snapshot data agar tetap konsisten
            $table->string('nama_siswa', 50);
            $table->string('no_whatsapp_guru_bk', 15);
            $table->string('no_whatsapp_siswa', 15);
            $table->string('no_whatsapp_ortu', 15)->nullable(); // Nullable karena tidak selalu ada

            // Info jadwal
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('kategori', 50); // contoh: "konseling pribadi", "panggilan orangtua", dll

            // Informasi tambahan
            $table->enum('dibuat_oleh', ['admin', 'guru', 'siswa']);
            $table->enum('tujuan', ['siswa', 'orangtua'])->nullable();
            $table->enum('status', ['tersedia', 'bentrok', 'selesai'])->default('tersedia');
            $table->boolean('notifikasi')->default(false);

            // Opsional catatan
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
}

