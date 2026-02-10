<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitIdToPengadaansTable extends Migration
{
    public function up(): void
    {
        Schema::table('pengadaans', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('unit_kerja');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('restrict');
            $table->index('unit_id');
        });
    }

    public function down(): void
    {
        Schema::table('pengadaans', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
}
