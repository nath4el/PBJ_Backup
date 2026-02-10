<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnitKerjaFromPengadaans extends Migration
{
    public function up(): void
    {
        Schema::table('pengadaans', function (Blueprint $table) {
            $table->dropColumn('unit_kerja');
        });
    }

    public function down(): void
    {
        Schema::table('pengadaans', function (Blueprint $table) {
            $table->string('unit_kerja')->nullable();
        });
    }
}
