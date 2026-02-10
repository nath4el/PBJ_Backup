<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengadaansTable extends Migration
{
    public function up(): void
    {
        Schema::create('pengadaans', function (Blueprint $table) {
            $table->id();

            // A. Informasi Umum
            $table->smallInteger('tahun');
            $table->string('unit_kerja');
            $table->string('nama_pekerjaan')->nullable();
            $table->string('id_rup')->nullable();
            $table->string('jenis_pengadaan');
            $table->string('status_pekerjaan');

            // B. Status Akses Arsip
            $table->string('status_arsip');

            // C. Informasi Anggaran
            $table->bigInteger('pagu_anggaran')->nullable();
            $table->bigInteger('hps')->nullable();
            $table->bigInteger('nilai_kontrak')->nullable();
            $table->string('nama_rekanan')->nullable();

            // D. Dokumen Pengadaan (jsonb untuk PostgreSQL)
            $table->jsonb('dokumen_kak')->nullable();
            $table->jsonb('dokumen_hps')->nullable();
            $table->jsonb('dokumen_spesifikasi_teknis')->nullable();
            $table->jsonb('dokumen_rancangan_kontrak')->nullable();
            $table->jsonb('dokumen_lembar_data_kualifikasi')->nullable();
            $table->jsonb('dokumen_lembar_data_pemilihan')->nullable();
            $table->jsonb('dokumen_daftar_kuantitas_harga')->nullable();
            $table->jsonb('dokumen_jadwal_lokasi_pekerjaan')->nullable();
            $table->jsonb('dokumen_gambar_rancangan_pekerjaan')->nullable();
            $table->jsonb('dokumen_amdal')->nullable();
            $table->jsonb('dokumen_penawaran')->nullable();
            $table->jsonb('surat_penawaran')->nullable();
            $table->jsonb('dokumen_kemenkumham')->nullable();
            $table->jsonb('ba_pemberian_penjelasan')->nullable();
            $table->jsonb('ba_pengumuman_negosiasi')->nullable();
            $table->jsonb('ba_sanggah_banding')->nullable();
            $table->jsonb('ba_penetapan')->nullable();
            $table->jsonb('laporan_hasil_pemilihan')->nullable();
            $table->jsonb('dokumen_sppbj')->nullable();
            $table->jsonb('surat_perjanjian_kemitraan')->nullable();
            $table->jsonb('surat_perjanjian_swakelola')->nullable();
            $table->jsonb('surat_penugasan_tim_swakelola')->nullable();
            $table->jsonb('dokumen_mou')->nullable();
            $table->jsonb('dokumen_kontrak')->nullable();
            $table->jsonb('ringkasan_kontrak')->nullable();
            $table->jsonb('jaminan_pelaksanaan')->nullable();
            $table->jsonb('jaminan_uang_muka')->nullable();
            $table->jsonb('jaminan_pemeliharaan')->nullable();
            $table->jsonb('surat_tagihan')->nullable();
            $table->jsonb('surat_pesanan_epurchasing')->nullable();
            $table->jsonb('dokumen_spmk')->nullable();
            $table->jsonb('dokumen_sppd')->nullable();
            $table->jsonb('laporan_pelaksanaan_pekerjaan')->nullable();
            $table->jsonb('laporan_penyelesaian_pekerjaan')->nullable();
            $table->jsonb('bap')->nullable();
            $table->jsonb('bast_sementara')->nullable();
            $table->jsonb('bast_akhir')->nullable();
            $table->jsonb('dokumen_pendukung_lainya')->nullable();

            // E. Dokumen Tidak Dipersyaratkan
            $table->jsonb('dokumen_tidak_dipersyaratkan')->nullable();

            // audit
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengadaans');
    }
}
