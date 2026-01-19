@extends('layouts.dashboard')

@section('title', 'Dashboard PPK - SIAPABAJA')

@section('content')
<div class="dash-wrap">

 {{-- SIDEBAR --}}
<aside class="dash-sidebar">
  <div class="dash-brand">
    <div class="dash-logo">
      <img src="{{ asset('image/Logo_Unsoed.png') }}" alt="Logo Unsoed">
    </div>

    <div class="dash-text">
      <div class="dash-app">SIAPABAJA</div>
      <div class="dash-role">Super Admin (PPK)</div>
    </div>
  </div>

  <nav class="dash-nav">
    <a class="dash-link active" href="{{ url('/ppk/dashboard') }}">
      <span class="ic"><i class="bi bi-grid-fill"></i></span>
      Dashboard
    </a>

    <a class="dash-link" href="{{ url('/ppk/tambah-pengadaan') }}">
      <span class="ic"><i class="bi bi-plus-square"></i></span>
      Tambah Pengadaan
    </a>

    <a class="dash-link" href="{{ url('/ppk/manajemen-unit') }}">
      <span class="ic"><i class="bi bi-person-gear"></i></span>
      Manajemen Akun Unit
    </a>
  </nav>
</aside>



  {{-- MAIN --}}
  <main class="dash-main">
    <header class="dash-header">
      <h1>Dashboard Super Admin (PPK)</h1>
      <p>Kelola seluruh arsip pengadaan dari semua unit kerja</p>
      <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


    </header>

    {{-- STAT CARDS --}}
    <section class="dash-stats">
      <div class="stat-card">
        <div class="stat-top">
          <span>Total Arsip</span>
          <span class="stat-ic">📄</span>
        </div>
        <div class="stat-val">7</div>
        <div class="stat-bar" style="background:#0f172a;"></div>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <span>Arsip Publik</span>
          <span class="stat-ic">👁️</span>
        </div>
        <div class="stat-val green">5</div>
        <div class="stat-bar" style="background:#22c55e;"></div>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <span>Arsip Private</span>
         <span class="eye gray">
  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
       viewBox="0 0 24 24" fill="none"
       stroke="currentColor" stroke-width="2"
       stroke-linecap="round" stroke-linejoin="round">
    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.94"/>
    <path d="M1 1l22 22"/>
    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
    <path d="M14.12 14.12L9.88 9.88"/>
    <path d="M12 4c7 0 11 8 11 8a21.77 21.77 0 0 1-4.32 5.94"/>
  </svg>
  Private
</span>

        </div>
        <div class="stat-val">2</div>
        <div class="stat-bar" style="background:#0f172a;"></div>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <span>Total Unit Kerja</span>
          <span class="stat-ic">🏢</span>
        </div>
        <div class="stat-val">7</div>
        <div class="stat-bar" style="background:#f6c100;"></div>
      </div>
    </section>

    {{-- FILTER BAR --}}
    <section class="dash-filter">
      <div class="filter-row">
        <div class="filter-search">
          <span class="s-ic">🔎</span>
          <input type="text" placeholder="Cari Unit">
        </div>

        <select>
          <option>Semua Unit</option>
          <option>Fakultas Teknik</option>
          <option>Fakultas Ekonomi dan Bisnis</option>
          <option>Fakultas Pertanian</option>
          <option>Fakultas Kedokteran</option>
        </select>

        <select>
          <option>Semua Status</option>
          <option>Publik</option>
          <option>Private</option>
        </select>
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
        <div>Aksi</div>
      </div>

      <div class="table-row">
        <div>2024</div>
        <div>Fakultas Teknik</div>
        <div class="job">
          Pengadaan Laboratorium Komputer Terpadu
          <small>RUP-2024-001-FT</small>
        </div>
        <div><span class="pill purple">Tender</span></div>
        <div><span class="eye green">👁 Publik</span></div>
        <div><span class="pill blue">Selesai</span></div>
        <div class="actions">
          <button class="act info" title="Detail">i</button>
          <button class="act edit" title="Edit">✎</button>
          <button class="act del" title="Hapus">🗑</button>
        </div>
      </div>

      <div class="table-row">
        <div>2024</div>
        <div>Fakultas Ekonomi dan Bisnis</div>
        <div class="job">
          Pengadaan Sistem Informasi Akademik...
          <small>RUP-2024-002-FEB</small>
        </div>
        <div><span class="pill purple">Tender</span></div>
        <div><span class="eye green">👁 Publik</span></div>
        <div><span class="pill yellow">Pelaksanaan</span></div>
        <div class="actions">
          <button class="act info">i</button>
          <button class="act edit">✎</button>
          <button class="act del">🗑</button>
        </div>
      </div>

      <div class="table-row">
        <div>2024</div>
        <div>Fakultas Pertanian</div>
        <div class="job">
          Pengadaan Alat Laboratorium Tanah dan...
          <small>RUP-2024-003-PAPERTA</small>
        </div>
        <div><span class="pill pink">Pengadaan Langsung</span></div>
        <div><span class="eye green">👁 Publik</span></div>
        <div><span class="pill blue">Selesai</span></div>
        <div class="actions">
          <button class="act info">i</button>
          <button class="act edit">✎</button>
          <button class="act del">🗑</button>
        </div>
      </div>

      <div class="table-row">
        <div>2024</div>
        <div>Fakultas Kedokteran</div>
        <div class="job">
          Pengadaan Peralatan Medis RS Pendidikan
          <small>RUP-2024-004-FK</small>
        </div>
        <div><span class="pill purple">Tender</span></div>
        <div><span class="eye gray">
  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.94"/>
    <path d="M1 1l22 22"/>
    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
    <path d="M14.12 14.12L9.88 9.88"/>
    <path d="M12 4c7 0 11 8 11 8a21.77 21.77 0 0 1-4.32 5.94"/>
  </svg>
  Private
</span>
</div>
        <div><span class="pill orange">Pemilihan</span></div>
        <div class="actions">
          <button class="act info">i</button>
          <button class="act edit">✎</button>
          <button class="act del">🗑</button>
        </div>
      </div>
    </section>

    {{-- floating help --}}
    <button class="help-fab" type="button" title="Bantuan">?</button>
  </main>
</div>
@endsection
