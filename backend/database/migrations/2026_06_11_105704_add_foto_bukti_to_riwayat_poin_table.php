<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('riwayat_poin', function (Blueprint $table) {
            $table->string('foto_bukti')->nullable()->after('ket');
        });
    }
    public function down()
    {
        Schema::table('riwayat_poin', function (Blueprint $table) {
            $table->dropColumn('foto_bukti');
        });
    }
};
