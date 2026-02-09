<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengadaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengadaan', 255);
            $table->string('id_rup', 100)->nullable();
            $table->string('unit_kerja', 255);
            $table->year('tahun_anggaran');
            $table->string('metode_pengadaan', 100);
            $table->string('status')->default('Perencanaan');
            $table->decimal('pagu_anggaran', 15, 2);
            $table->decimal('hps', 15, 2)->nullable();
            $table->decimal('nilai_kontrak', 15, 2)->nullable();
            $table->string('rekanan', 255)->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('deskripsi')->nullable();

            // Kolom untuk file dokumen (simpan path)
            $table->string('dokumen_rup')->nullable();
            $table->string('dokumen_hps')->nullable();
            $table->string('dokumen_kontrak')->nullable();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengadaans');
    }
};