{{-- resources/views/unit/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin Unit - SIAPABAJA</title>

  {{-- Font Nunito --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

  {{-- Bootstrap Icons (untuk icon cepat) --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <link rel="stylesheet" href="{{ asset('css/Unitdashboard.css') }}">
</head>

<body class="dash-body">
@php
  // dummy frontend (nanti backend tinggal ganti)
  $unitName = "Fakultas Teknik";
  $stats = [
    ["label"=>"Total Arsip", "value"=>7, "accent"=>"navy", "icon"=>"bi-file-earmark-text"],
    ["label"=>"Arsip Publik", "value"=>5, "accent"=>"green", "icon"=>"bi-eye"],
    ["label"=>"Arsip Private", "value"=>2, "accent"=>"gray", "icon"=>"bi-eye-slash"],
  ];

  $rows = [
    ["tahun"=>"2024","unit"=>$unitName,"pekerjaan"=>"Pengadaan Laboratorium Komputer Terpadu | RUP-2026-001-FT","jenis"=>"Pengadaan Langsung","arsip"=>"Publik","status"=>"Perencanaan"],
    ["tahun"=>"2024","unit"=>$unitName,"pekerjaan"=>"Pengadaan Laboratorium Komputer Terpadu | RUP-2026-001-FT","jenis"=>"Tender","arsip"=>"Privat","status"=>"Pemilihan"],
    ["tahun"=>"2024","unit"=>$unitName,"pekerjaan"=>"Pengadaan Laboratorium Komputer Terpadu | RUP-2026-001-FT","jenis"=>"E-Katalog","arsip"=>"Publik","status"=>"Pelaksanaan"],
    ["tahun"=>"2024","unit"=>$unitName,"pekerjaan"=>"Pengadaan Laboratorium Komputer Terpadu | RUP-2026-001-FT","jenis"=>"Pengadaan Langsung","arsip"=>"Privat","status"=>"Selesai"],
  ];
@endphp

<div class="dash-wrap">
  {{-- SIDEBAR --}}
  <aside class="dash-sidebar">
    <div class="dash-brand">
      <div class="dash-logo">
        <img src="{{ asset('image/Logo_Unsoed.png') }}" alt="Logo Unsoed">
      </div>

      <div class="dash-text">
        <div class="dash-app">SIAPABAJA</div>
        <div class="dash-role">Admin (Unit)</div>
      </div>
    </div>

    <div class="dash-unitbox">
      <div class="dash-unit-label">Unit Kerja :</div>
      <div class="dash-unit-name">{{ $unitName }}</div>
    </div>

    <nav class="dash-nav">
      <a class="dash-link active" href="#">
        <span class="ic"><i class="bi bi-grid-fill"></i></span>
        Dashboard
      </a>

      <a class="dash-link" href="#">
        <span class="ic"><i class="bi bi-plus-square"></i></span>
        Tambah Pengadaan
      </a>
    </nav>
  </aside>

  {{-- MAIN --}}
  <main class="dash-main">
    <header class="dash-header">
      <h1>Dashboard Admin Unit</h1>
      <p>Kelola arsip pengadaan barang dan jasa {{ $unitName }}</p>
    </header>

    {{-- STAT CARDS --}}
    <section class="dash-stats">
      @foreach($stats as $s)
        <div class="stat-card">
          <div class="stat-bar {{ $s['accent'] }}"></div>

          <div class="stat-top">
            <div class="stat-label">{{ $s['label'] }}</div>
            <div class="stat-ic"><i class="bi {{ $s['icon'] }}"></i></div>
          </div>

          <div class="stat-val {{ $s['accent'] === 'green' ? 'green' : '' }}">{{ $s['value'] }}</div>
        </div>
      @endforeach
    </section>

    {{-- FILTER --}}
    <section class="dash-filter">
      <div class="filter-row">
        <div class="filter-search">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Cari pekerjaan / RUP / tahun..." />
        </div>

        <div class="filter-select">
          <select>
            <option>Semua Status</option>
            <option>Publik</option>
            <option>Private</option>
          </select>
          <i class="bi bi-chevron-down"></i>
        </div>
      </div>
    </section>

    {{-- TABLE --}}
    <section class="dash-table">
      <div class="table-head">
        <div>Tahun</div>
        <div>Unit Kerja</div>
        <div>Nama Pekerjaan</div>
        <div>Jenis Pengadaan</div>
        <div>Status Arsip</div>
        <div>Status Pekerjaan</div>
        <div style="text-align:right;">Aksi</div>
      </div>

      @foreach($rows as $r)
        <div class="table-row">
          <div class="muted">{{ $r['tahun'] }}</div>
          <div class="job">{{ $r['unit'] }}</div>

          <div class="job">
            {{ $r['pekerjaan'] }}
            <small>Detail pekerjaan pengadaan</small>
          </div>

          <div>
            <span class="pill blue">{{ $r['jenis'] }}</span>
          </div>

          <div>
            @if($r['arsip'] === 'Publik')
              <span class="eye green"><i class="bi bi-eye"></i> Publik</span>
            @else
              <span class="eye gray"><i class="bi bi-eye-slash"></i> Privat</span>
            @endif
          </div>

          <div>
            <span class="pill yellow">{{ $r['status'] }}</span>
          </div>

          <div class="actions">
            <button class="act info" title="Detail"><i class="bi bi-info-circle"></i></button>
            <button class="act edit" title="Edit"><i class="bi bi-pencil"></i></button>
            <button class="act del" title="Hapus"><i class="bi bi-trash"></i></button>
          </div>
        </div>
      @endforeach
    </section>
  </main>
</div>

</body>
</html>
