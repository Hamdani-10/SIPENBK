<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('guru_bk', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('no_whatsapp_guru_bk');
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('no_whatsapp_siswa');
        });
    }

    public function down()
    {
        Schema::table('guru_bk', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }

};
