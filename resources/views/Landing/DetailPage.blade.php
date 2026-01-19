@extends('layouts.app')

@section('title', 'Detail Arsip - SIAPABAJA')

@section('content')
@php
  // Dummy data (frontend-only)
  $arsipList = [
    1 => [
      'judul' => 'Penyediaan Jasa Keamanan (SATPAM) Universitas Jenderal Soedirman',
      'unit_kerja' => 'Fakultas Teknik',
      'tahun_anggaran' => '2026',
      'id_rup' => 'RUP-2-26-001-FT',
      'status' => 'Selesai',
      'rekanan' => 'PT Jadi Kaya Bersama',
      'jenis' => 'Tender',
      'pagu' => 'Rp 500.000.000',
      'hps'  => 'Rp 480.000.000',
      'nilai_kontrak' => 'Rp 475.000.000',
      'dokumen' => [
        ['nama' => 'Dokumen RUP', 'file' => '#'],
        ['nama' => 'Dokumen Kontrak', 'file' => '#'],
        ['nama' => 'BA Evaluasi', 'file' => '#'],
        ['nama' => 'Dokumen RUP', 'file' => '#'],
        ['nama' => 'Dokumen Kontrak', 'file' => '#'],
        ['nama' => 'BA Evaluasi', 'file' => '#'],
        ['nama' => 'Dokumen RUP', 'file' => '#'],
        ['nama' => 'Dokumen Kontrak', 'file' => '#'],
        ['nama' => 'BA Evaluasi', 'file' => '#'],
      ],
    ],
    2 => [
      'judul' => 'Pengadaan Laptop Operasional Universitas Jenderal Soedirman',
      'unit_kerja' => 'Fakultas Ekonomi dan Bisnis',
      'tahun_anggaran' => '2026',
      'id_rup' => 'RUP-2-26-002-FEB',
      'status' => 'Berjalan',
      'rekanan' => 'PT Teknologi Maju Bersama',
      'jenis' => 'E-Purchasing',
      'pagu' => 'Rp 300.000.000',
      'hps'  => 'Rp 290.000.000',
      'nilai_kontrak' => 'Rp 285.000.000',
      'dokumen' => array_fill(0, 9, ['nama' => 'Dokumen RUP', 'file' => '#']),
    ],
    3 => [
      'judul' => 'Pengadaan ATK & Bahan Habis Pakai Tahun 2026',
      'unit_kerja' => 'Biro Umum',
      'tahun_anggaran' => '2026',
      'id_rup' => 'RUP-2-26-003-UMUM',
      'status' => 'Selesai',
      'rekanan' => 'CV Sumber Makmur',
      'jenis' => 'Tender Cepat',
      'pagu' => 'Rp 120.000.000',
      'hps'  => 'Rp 115.000.000',
      'nilai_kontrak' => 'Rp 112.000.000',
      'dokumen' => array_fill(0, 9, ['nama' => 'Dokumen RUP', 'file' => '#']),
    ],
    4 => [
      'judul' => 'Pemeliharaan Jaringan & Internet Kampus',
      'unit_kerja' => 'UPT TIK',
      'tahun_anggaran' => '2026',
      'id_rup' => 'RUP-2-26-004-TIK',
      'status' => 'Berjalan',
      'rekanan' => 'PT Net Cepat Nusantara',
      'jenis' => 'Tender',
      'pagu' => 'Rp 900.000.000',
      'hps'  => 'Rp 880.000.000',
      'nilai_kontrak' => 'Rp 875.000.000',
      'dokumen' => array_fill(0, 9, ['nama' => 'Dokumen RUP', 'file' => '#']),
    ],
    5 => [
      'judul' => 'Pemeliharaan Jaringan & Internet Kampus',
      'unit_kerja' => 'UPT TIK',
      'tahun_anggaran' => '2026',
      'id_rup' => 'RUP-2-26-004-TIK',
      'status' => 'Berjalan',
      'rekanan' => 'PT Net Cepat Nusantara',
      'jenis' => 'Tender',
      'pagu' => 'Rp 900.000.000',
      'hps'  => 'Rp 880.000.000',
      'nilai_kontrak' => 'Rp 875.000.000',
      'dokumen' => array_fill(0, 9, ['nama' => 'Dokumen RUP', 'file' => '#']),
    ],
  ];

  $arsip = $arsipList[(int)$id] ?? null;
@endphp

<section class="detail-page">
  <div class="container">

    <a class="back-link" href="{{ url('/#arsip') }}">‹ Kembali ke Beranda</a>

    @if(!$arsip)
      <div class="detail-card">
        <h2 class="detail-title">Data arsip tidak ditemukan</h2>
        <p style="color: var(--muted); margin:0;">
          Arsip dengan ID <b>{{ $id }}</b> tidak tersedia (dummy hanya 1–5).
        </p>
      </div>
    @else
      <div class="detail-card">
        <h2 class="detail-title">{{ $arsip['judul'] }}</h2>

        <div class="detail-grid">
          <div class="info-box">
            <div class="info-row">
              <div class="ic">🏢</div>
              <div>
                <div class="label">Unit Kerja</div>
                <div class="value">{{ $arsip['unit_kerja'] }}</div>
              </div>
            </div>
          </div>

          <div class="info-box">
            <div class="info-row">
              <div class="ic">📅</div>
              <div>
                <div class="label">Tahun Anggaran</div>
                <div class="value">{{ $arsip['tahun_anggaran'] }}</div>
              </div>
            </div>
          </div>

          <div class="info-box">
            <div class="info-row">
              <div class="ic">🆔</div>
              <div>
                <div class="label">ID RUP</div>
                <div class="value">{{ $arsip['id_rup'] }}</div>
              </div>
            </div>
          </div>

          <div class="info-box">
            <div class="info-row">
              <div class="ic">✅</div>
              <div>
                <div class="label">Status Pekerjaan</div>
                <div class="value">{{ $arsip['status'] }}</div>
              </div>
            </div>
          </div>

          <div class="info-box">
            <div class="info-row">
              <div class="ic">👤</div>
              <div>
                <div class="label">Nama Rekanan</div>
                <div class="value">{{ $arsip['rekanan'] }}</div>
              </div>
            </div>
          </div>

          <div class="info-box">
            <div class="info-row">
              <div class="ic">📄</div>
              <div>
                <div class="label">Jenis Pengadaan</div>
                <div class="value">{{ $arsip['jenis'] }}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="sub-title">Informasi Anggaran</div>
        <div class="budget-grid">
          <div class="budget-box">
            <div class="label">Pagu Anggaran</div>
            <div class="money">{{ $arsip['pagu'] }}</div>
          </div>
          <div class="budget-box">
            <div class="label">HPS</div>
            <div class="money">{{ $arsip['hps'] }}</div>
          </div>
          <div class="budget-box">
            <div class="label">Nilai Kontrak</div>
            <div class="money">{{ $arsip['nilai_kontrak'] }}</div>
          </div>
        </div>

        <div class="sub-title" style="margin-top:20px;">Dokumen Pengadaan</div>

        <div class="docs-grid">
          @foreach($arsip['dokumen'] as $doc)
            <div class="doc-item">
              <div class="doc-left">
                <div class="doc-ic">📄</div>
                <div class="doc-name">{{ $doc['nama'] }}</div>
              </div>
              <a class="doc-btn" href="{{ $doc['file'] }}" onclick="return false;">⬇ Unduh</a>


            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>
</section>
@endsection
