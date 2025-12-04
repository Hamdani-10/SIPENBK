<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guru_bk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // penting: unique biar 1 user hanya 1 guru bk
            $table->string('nama_guru_bk', 25);
            $table->string('jabatan', 20);
            $table->string('no_whatsapp_guru_bk', 15);
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_bk');
    }
};
