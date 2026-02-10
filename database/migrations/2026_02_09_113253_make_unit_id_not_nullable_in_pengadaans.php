<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUnitIdNotNullableInPengadaans extends Migration
{
    public function up(): void
    {
        Schema::table('pengadaans', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengadaans', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->change();
        });
    }
}
