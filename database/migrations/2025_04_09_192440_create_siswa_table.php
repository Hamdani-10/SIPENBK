<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id(); // pakai nama custom
            $table->string('nisn')->unique();
            
            // Di sini tambahan unique-nya:
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
        
            $table->string('nama_siswa', 25);
            $table->string('kelas', 20);
            $table->string('no_whatsapp_siswa', 15);
            $table->string('nama_ortu', 25);
            $table->string('no_whatsapp_ortu', 15);
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
