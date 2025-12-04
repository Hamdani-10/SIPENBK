<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panduan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi_panduan');
            $table->string('gambar')->nullable(); // path gambar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panduan');
    }
};
