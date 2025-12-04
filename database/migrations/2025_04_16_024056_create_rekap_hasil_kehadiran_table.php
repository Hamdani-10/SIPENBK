<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekapHasilKehadiranTable extends Migration
{
    public function up()
    {
        Schema::create('rekap_hasil_kehadiran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jadwal');  // Foreign key referring to `id` in jadwal table
            $table->unsignedBigInteger('id_guru_bk');  // Foreign key referring to `id` in guru_bk table (corrected)
            $table->text('hasil_bk')->nullable();
            $table->boolean('kehadiran_siswa')->default(false);
            $table->boolean('kehadiran_ortu')->default(false);
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_jadwal')->references('id')->on('jadwal')->onDelete('cascade');  // Correcting reference to `id` in jadwal
            $table->foreign('id_guru_bk')->references('id')->on('guru_bk')->onDelete('cascade');  // Correct reference to `id` in guru_bk
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekap_hasil_kehadiran');
    }
}
