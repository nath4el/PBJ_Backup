{{-- resources/views/LihatDetail.blade.php --}}
@extends('layouts.landing') {{-- pastikan layout ini memanggil landing.css dan bootstrap-icons --}}

@section('title', 'Detail Arsip - SIAPABAJA')

@section('content')
@php
  // dummy data frontend (nanti backend tinggal ganti)
  $data = [
    'judul' => 'Penyediaan Jasa Keamanan (SATPAM) Universitas Jenderal Soedirman',
    'unit'  => 'Fakultas Teknik',
    'tahun' => '2026',
    'id_rup'=> '2026',
    'status'=> 'Selesai',
    'rekanan' => 'PT Jadi Kaya Bersama',
    'jenis' => 'Tender',
    'pagu'  => 'Rp 500.000.000',
    'hps'   => 'Rp 480.000.000',
    'kontrak'=> 'Rp 475.000.000',
  ];

  // 12 item biar tampil 4 baris x 3 kolom seperti gambar
  $dokumen = array_fill(0, 12, ['nama' => 'Dokumen RUP', 'link' => '#']);
@endphp

<section class="detail-page">
  <div class="container">

    <a class="detail-back" href="{{ url('/#arsip') }}">
      <i class="bi bi-chevron-left"></i> Kembali ke Beranda
    </a>

    <div class="detail-card">
      <h2 class="detail-title">{{ $data['judul'] }}</h2>

      {{-- INFO GRID (2 baris, 3 kolom) --}}
      <div class="detail-grid">
        <div class="detail-item">
          <div class="di-ic"><i class="bi bi-building"></i></div>
          <div>
            <div class="di-k">Unit Kerja</div>
            <div class="di-v">{{ $data['unit'] }}</div>
          </div>
        </div>

        <div class="detail-item">
          <div class="di-ic"><i class="bi bi-calendar3"></i></div>
          <div>
            <div class="di-k">Tahun Anggaran</div>
            <div class="di-v">{{ $data['tahun'] }}</div>
          </div>
        </div>

        <div class="detail-item">
          <div class="di-ic"><i class="bi bi-hash"></i></div>
          <div>
            <div class="di-k">ID RUP</div>
            <div class="di-v">{{ $data['id_rup'] }}</div>
          </div>
        </div>

        <div class="detail-item">
          <div class="di-ic"><i class="bi bi-clipboard-check"></i></div>
          <div>
            <div class="di-k">Status Pekerjaan</div>
            <div class="di-v">{{ $data['status'] }}</div>
          </div>
        </div>

        <div class="detail-item">
          <div class="di-ic"><i class="bi bi-person"></i></div>
          <div>
            <div class="di-k">Nama Rekanan</div>
            <div class="di-v">{{ $data['rekanan'] }}</div>
          </div>
        </div>

        <div class="detail-item">
          <div class="di-ic"><i class="bi bi-file-earmark-text"></i></div>
          <div>
            <div class="di-k">Jenis Pengadaan</div>
            <div class="di-v">{{ $data['jenis'] }}</div>
          </div>
        </div>
      </div>

      <div class="detail-sep"></div>

      {{-- ANGGARAN --}}
      <div class="detail-section-title">Informasi Anggaran</div>
      <div class="budget-grid">
        <div class="budget-box">
          <div class="b-k">Pagu Anggaran</div>
          <div class="b-v">{{ $data['pagu'] }}</div>
        </div>
        <div class="budget-box">
          <div class="b-k">HPs</div>
          <div class="b-v">{{ $data['hps'] }}</div>
        </div>
        <div class="budget-box">
          <div class="b-k">Nilai Kontrak</div>
          <div class="b-v">{{ $data['kontrak'] }}</div>
        </div>
      </div>

      <div class="detail-sep"></div>

      {{-- DOKUMEN --}}
      <div class="detail-section-title">Dokumen Pengadaan</div>

      <div class="docs-grid">
        @foreach($dokumen as $doc)
          <div class="doc-row">
            <div class="doc-left">
              <span class="doc-ic"><i class="bi bi-file-earmark"></i></span>
              <span class="doc-name">{{ $doc['nama'] }}</span>
            </div>

            <a class="doc-btn" href="{{ $doc['link'] }}" onclick="return false;">
              <i class="bi bi-download"></i> Unduh
            </a>
          </div>
        @endforeach
      </div>

    </div>
  </div>
</section>
@endsection
