{{-- resources/views/unit/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin Unit - SIAPABAJA</title>

  {{-- Font Nunito (HANYA 400 & 600 biar tidak ada bold) --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet">

  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <link rel="stylesheet" href="{{ asset('css/Unit.css') }}">

  {{-- Chart.js (untuk donut & bar) --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>

<body class="dash-body">
@php
  // dummy frontend (nanti backend tinggal ganti)
  $unitName = "Fakultas Teknik";

  // TOP SUMMARY (sesuai gambar)
  $summary = [
    ["label"=>"Total Arsip", "value"=>7, "accent"=>"navy", "icon"=>"bi-file-earmark-text"],
    ["label"=>"Arsip Publik", "value"=>5, "accent"=>"yellow", "icon"=>"bi-eye"],
    ["label"=>"Arsip Private", "value"=>2, "accent"=>"gray", "icon"=>"bi-eye-slash"],
    ["label"=>"Total Paket Pengadaan", "value"=>7, "accent"=>"navy", "icon"=>"bi-file-earmark-text", "sub"=>"Paket Pengadaan Barang dan Jasa"],
    ["label"=>"Total Nilai Pengadaan", "value"=>"Rp 1.200.000.000", "accent"=>"yellow", "icon"=>"bi-buildings", "sub"=>"Nilai Kontrak Pengadaan"],
  ];

  // opsi filter (dummy)
  $tahunOptions = [2022, 2023, 2024, 2025, 2026];
  $unitOptions  = ["Semua Unit", "Fakultas Teknik", "Fakultas Hukum", "Fakultas Ekonomi dan Bisnis"];

  // dummy data chart (nanti backend)
  $statusLabels = ["Perencanaan","Pemilihan","Pelaksanaan","Selesai"];
  $statusValues = [25, 15, 20, 30];

  // ✅ UPDATE: bar jadi 6 batang seperti gambar
  $barLabels = [
    "Pengadaan\nLangsung",
    "Penunjukan\nLangsung",
    "E-Purchasing /\nE-Catalog",
    "Tender\nTerbatas",
    "Tender\nTerbuka",
    "Swakelola"
  ];
  $barValues = [35, 90, 65, 50, 75, 25];
@endphp

<div class="dash-wrap">
  {{-- SIDEBAR (konsisten dengan tambah pengadaan) --}}
  <aside class="dash-sidebar">
    <div class="dash-brand">
      <div class="dash-logo">
        <img src="{{ asset('image/Logo_Unsoed.png') }}" alt="Logo Unsoed">
      </div>

      <div class="dash-text">
        <div class="dash-app">SIAPABAJA</div>
        <div class="dash-role">PIC (Unit)</div>
      </div>
    </div>

    <div class="dash-unitbox">
      <div class="dash-unit-label">Unit Kerja :</div>
      <div class="dash-unit-name">{{ $unitName }}</div>
    </div>

    <nav class="dash-nav">
      <a class="dash-link active" href="{{ url('/unit/dashboard') }}">
        <span class="ic"><i class="bi bi-grid-fill"></i></span>
        Dashboard
      </a>

      <a class="dash-link" href="{{ url('/unit/arsip') }}">
        <span class="ic"><i class="bi bi-archive"></i></span>
        Arsip PBJ
      </a>

      <a class="dash-link" href="{{ url('/unit/pengadaan/tambah') }}">
        <span class="ic"><i class="bi bi-plus-square"></i></span>
        Tambah Pengadaan
      </a>

       <a class="dash-link" href="{{ route('unit.kelola.akun') }}">
    <span class="ic"><i class="bi bi-person-gear"></i></span>
    Kelola Akun
  </a>
    </nav>

    {{-- Footer buttons (DISAMAKAN DENGAN ARSIP PBJ) --}}
    <div class="dash-side-actions">
      <a class="dash-side-btn" href="{{ url('/unit/dashboard') }}">
        <i class="bi bi-house-door"></i> Kembali
      </a>
      <a class="dash-side-btn" href="{{ url('/logout') }}">
        <i class="bi bi-box-arrow-right"></i> Keluar
      </a>
    </div>
  </aside>

  {{-- MAIN --}}
  <main class="dash-main">
    <header class="dash-header">
      {{-- ✅ semi-bold hanya judul page --}}
      <h1>Dashboard PIC Unit</h1>
      <p>Kelola arsip pengadaan barang dan jasa {{ $unitName }}</p>
    </header>

    {{-- SUMMARY CARDS (layout sesuai gambar: 3 atas, lalu 2 bawah) --}}
    <section class="u-sum">
      {{-- row 1 (3 card) --}}
      <div class="u-sum-row u-sum-row--3">
        {{-- 1 --}}
        <div class="u-card">
          <div class="u-bar u-bar--navy"></div>
          <div class="u-top">
            <div>
              <div class="u-label">{{ $summary[0]['label'] }}</div>
              {{-- ✅ count --}}
              <div class="u-value u-value--navy js-count" data-count="{{ (int) $summary[0]['value'] }}">0</div>
            </div>
            <div class="u-ic"><i class="bi {{ $summary[0]['icon'] }}"></i></div>
          </div>
        </div>

        {{-- 2 --}}
        <div class="u-card">
          <div class="u-bar u-bar--yellow"></div>
          <div class="u-top">
            <div>
              <div class="u-label">{{ $summary[1]['label'] }}</div>
              {{-- ✅ count --}}
              <div class="u-value u-value--yellow js-count" data-count="{{ (int) $summary[1]['value'] }}">0</div>
            </div>
            <div class="u-ic u-ic--yellow"><i class="bi {{ $summary[1]['icon'] }}"></i></div>
          </div>
        </div>

        {{-- 3 --}}
        <div class="u-card">
          <div class="u-bar u-bar--gray"></div>
          <div class="u-top">
            <div>
              <div class="u-label">{{ $summary[2]['label'] }}</div>
              {{-- ✅ count --}}
              <div class="u-value u-value--gray js-count" data-count="{{ (int) $summary[2]['value'] }}">0</div>
            </div>
            <div class="u-ic u-ic--gray"><i class="bi {{ $summary[2]['icon'] }}"></i></div>
          </div>
        </div>
      </div>

      {{-- row 2 (2 card) --}}
      <div class="u-sum-row u-sum-row--2">
        {{-- 4 --}}
        <div class="u-card">
          <div class="u-bar u-bar--navy"></div>
          <div class="u-top">
            <div>
              <div class="u-label">{{ $summary[3]['label'] }}</div>
              {{-- ✅ ID supaya nilai bisa berubah saat filter tahun --}}
              {{-- ✅ count --}}
              <div class="u-value u-value--navy js-count" id="valPaket" data-count="{{ (int) $summary[3]['value'] }}">0</div>
              <div class="u-sub">{{ $summary[3]['sub'] }}</div>
            </div>
            <div class="u-ic"><i class="bi {{ $summary[3]['icon'] }}"></i></div>
          </div>

          {{-- ✅ FILTER TAHUN (bawah kanan) --}}
          <div class="u-card-filter">
            <div class="u-mini-select">
              <select id="fTahunPaket">
                @foreach($tahunOptions as $t)
                  <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
              </select>
              <i class="bi bi-chevron-down"></i>
            </div>
          </div>
        </div>

        {{-- 5 --}}
        <div class="u-card">
          <div class="u-bar u-bar--yellow"></div>
          <div class="u-top">
            <div>
              <div class="u-label">{{ $summary[4]['label'] }}</div>
              {{-- ✅ ID supaya nilai bisa berubah saat filter tahun --}}
              {{-- ✅ count (rupiah) --}}
              <div class="u-money js-count" id="valNilai" data-count="{{ (int) preg_replace('/[^0-9]/','', $summary[4]['value']) }}">Rp 0</div>
              <div class="u-sub">{{ $summary[4]['sub'] }}</div>
            </div>
            <div class="u-ic u-ic--yellow"><i class="bi {{ $summary[4]['icon'] }}"></i></div>
          </div>

          {{-- ✅ FILTER TAHUN (bawah kanan) --}}
          <div class="u-card-filter">
            <div class="u-mini-select">
              <select id="fTahunNilai">
                @foreach($tahunOptions as $t)
                  <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
              </select>
              <i class="bi bi-chevron-down"></i>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- STATISTIKA (2 card chart) --}}
    <section class="u-charts">
      {{-- Chart 1: Donut --}}
      <div class="u-chart-card">
        {{-- ✅ subjudul harus normal + tombol detail --}}
        <div class="u-chart-head">
          <div class="u-chart-title">Status Arsip</div>

          {{-- ✅ tombol detail (lingkaran kecil) --}}
          <button class="u-info-btn" type="button" data-pop="popDonut" aria-label="Lihat detail Status Arsip">
            <i class="bi bi-info"></i>
          </button>

          {{-- ✅ popup detail (dekat tombol) --}}
          <div id="popDonut" class="u-popover" role="dialog" aria-hidden="true">
            <div class="u-popover-title">Detail Status Arsip</div>
            <div class="u-popover-meta">
              <span id="metaDonut">—</span>
            </div>
            <div class="u-popover-list" id="listDonut"></div>
            <div class="u-popover-foot">Klik di luar untuk menutup</div>
          </div>
        </div>

        <div class="u-chart-divider"></div>

        {{-- ✅ HANYA FILTER TAHUN + lebar full --}}
        <div class="u-chart-filters u-chart-filters--one">
          <div class="u-select u-select--full">
            <select id="fTahun1">
              <option value="">Tahun</option>
              @foreach($tahunOptions as $t)
                <option value="{{ $t }}">{{ $t }}</option>
              @endforeach
            </select>
            <i class="bi bi-chevron-down"></i>
          </div>
        </div>

        <div class="u-canvas-wrap">
          <canvas id="donutStatus"></canvas>
        </div>
      </div>

      {{-- Chart 2: Bar --}}
      <div class="u-chart-card">
        <div class="u-chart-head">
          <div class="u-chart-title">Metode Pengadaan</div>

          {{-- ✅ tombol detail (lingkaran kecil) --}}
          <button class="u-info-btn" type="button" data-pop="popBar" aria-label="Lihat detail Metode Pengadaan">
            <i class="bi bi-info"></i>
          </button>

          {{-- ✅ popup detail (dekat tombol) --}}
          <div id="popBar" class="u-popover" role="dialog" aria-hidden="true">
            <div class="u-popover-title">Detail Metode Pengadaan</div>
            <div class="u-popover-meta">
              <span id="metaBar">—</span>
            </div>
            <div class="u-popover-list" id="listBar"></div>
            <div class="u-popover-foot">Klik di luar untuk menutup</div>
          </div>
        </div>

        <div class="u-chart-divider"></div>

        {{-- ✅ HANYA FILTER TAHUN + lebar full --}}
        <div class="u-chart-filters u-chart-filters--one">
          <div class="u-select u-select--full">
            <select id="fTahun2">
              <option value="">Tahun</option>
              @foreach($tahunOptions as $t)
                <option value="{{ $t }}">{{ $t }}</option>
              @endforeach
            </select>
            <i class="bi bi-chevron-down"></i>
          </div>
        </div>

        <div class="u-canvas-wrap">
          <canvas id="barStatus"></canvas>
        </div>
      </div>
    </section>
  </main>
</div>

<style>
  /* =============================
     DASHBOARD OVERRIDE (NO BOLD)
     Aturan:
     - h1 (judul page) semi-bold
     - lainnya normal
  ============================= */

  .dash-body{
    font-size: 18px;
    line-height: 1.6;
    font-weight: 400;
  }

  /* ✅ FIT 1 LAYAR: tidak bisa scroll page */
  html, body{
    height: 100%;
    overflow: hidden;
  }
  .dash-wrap{
    height: 100vh;
    overflow: hidden;
  }
  .dash-main{
    height: 100vh;
    overflow: hidden;
  }

  /* pastikan judul page saja yang semi-bold */
  .dash-header h1{ font-weight: 600 !important; }
  .dash-header p{ font-weight: 400 !important; }

  /* matikan bold dari style lama */
  .u-label,
  .u-value,
  .u-money,
  .u-sub,
  .u-chart-title,
  .u-select select{
    font-weight: 400 !important;
  }

  /* ✅ tambahan: mini filter tahun di card (bawah kanan) */
  .u-card{ position: relative; }
  .u-card-filter{
    position: absolute;
    right: 12px;
    bottom: 10px;
  }

  .u-mini-select{ position: relative; }
  .u-mini-select select{
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px 32px 8px 12px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 400 !important;
    background: #fff;
    outline: none;
    appearance: none;
    cursor: pointer;
  }
  .u-mini-select i{
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    opacity: .6;
    pointer-events: none;
    font-size: 12px;
  }

  /* Sidebar tetap (tidak ikut scroll) */
  .dash-sidebar{
    position: sticky;
    top: 0;
    height: 100vh;
    overflow: hidden;
    display:flex;
    flex-direction:column;
  }

  /* Summary layout */
  .u-sum{ display:grid; gap: 16px; }
  .u-sum-row{ display:grid; gap: 16px; }
  .u-sum-row--3{ grid-template-columns: repeat(3, minmax(0, 1fr)); }
  .u-sum-row--2{ grid-template-columns: repeat(2, minmax(0, 1fr)); }

  .u-card{
    background:#fff;
    border: 1px solid #e6eef2;
    border-radius: 14px;
    box-shadow: 0 10px 20px rgba(2,8,23,.04);
    overflow:hidden;
    position:relative;
    padding: 16px 16px 14px;
    min-height: 86px;
  }
  .u-bar{
    position:absolute;
    left:0; top:0; bottom:0;
    width: 4px;
    border-radius: 14px 0 0 14px;
  }
  .u-bar--navy{ background: #184f61; }
  .u-bar--yellow{ background: #f6c100; }
  .u-bar--gray{ background: #0f172a; opacity:.75; }

  .u-top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap: 14px;
  }

  .u-label{
    font-size: 16px;
    color: #64748b;
    margin-bottom: 6px;
  }

  .u-value{
    font-size: 34px;
    line-height: 1;
  }
  .u-value--navy{ color: #184f61; }
  .u-value--yellow{ color: #f6c100; }
  .u-value--gray{ color: #0f172a; opacity:.85; }

  .u-money{
    font-size: 34px;
    line-height: 1.05;
    color: #c98800;
  }

  .u-sub{
    margin-top: 8px;
    font-size: 14px;
    color: #94a3b8;
  }

  .u-ic{
    width: 40px; height: 40px;
    display:grid; place-items:center;
    border-radius: 10px;
    background: #f1f5f9;
    color: #184f61;
    font-size: 20px;
    flex: 0 0 auto;
  }
  .u-ic--yellow{ color:#c98800; background:#fff6cc; }
  .u-ic--gray{ color:#0f172a; background:#eef2f7; }

  /* Charts */
  .u-charts{
    margin-top: 18px;
    display:grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
  }
  .u-chart-card{
    background:#fff;
    border: 1px solid #e6eef2;
    border-radius: 18px;
    padding: 14px 16px 16px;
    box-shadow: 0 10px 20px rgba(2,8,23,.04);
    position: relative;
  }

  /* ✅ header chart + tombol info */
  .u-chart-head{
    position: relative;
    display:flex;
    align-items:center;
    justify-content:center;
    min-height: 28px;
  }

  /* subjudul normal */
  .u-chart-title{
    text-align:center;
    font-size: 20px;
    color:#0f172a;
    margin-top: 2px;
  }

  /* ✅ tombol detail: putih + frame tebal (sama seperti PPK) */
  .u-info-btn{
    position:absolute;
    right: 0;
    top: 0;
    width: 26px;
    height: 26px;
    border-radius: 999px;
    border: 2px solid #184f61;
    background:#fff;
    display:grid;
    place-items:center;
    cursor:pointer;
    line-height: 1;
    padding: 0;
    color:#184f61;
    box-shadow: 0 10px 20px rgba(2,8,23,.06);
  }
  .u-info-btn i{
    font-size: 14px;
    opacity: .9;
    pointer-events:none;
    -webkit-text-stroke: .4px #184f61;
  }
  .u-info-btn:hover{
    border-color:#143f4d;
    transform: translateY(-.5px);
  }

  /* ✅ popover detail (dekat tombol) */
  .u-popover{
    position:absolute;
    right: 0;
    top: 30px;
    width: 380px;
    background:#fff;
    border: 1px solid #e6eef2;
    border-radius: 12px;
    box-shadow: 0 18px 30px rgba(2,8,23,.12);
    padding: 10px 10px 8px;
    z-index: 50;
    display:none;
  }
  .u-popover.is-open{ display:block; }
  .u-popover::before{
    content:"";
    position:absolute;
    right: 12px;
    top: -6px;
    width: 10px;
    height: 10px;
    background:#fff;
    border-left: 1px solid #e6eef2;
    border-top: 1px solid #e6eef2;
    transform: rotate(45deg);
  }
  .u-popover-title{
    font-size: 14px;
    color:#0f172a;
    font-weight: 400 !important;
    margin-bottom: 4px;
  }
  .u-popover-meta{
    font-size: 12px;
    color:#64748b;
    margin-bottom: 8px;
  }
  .u-popover-list{
    display:grid;
    gap: 6px;
    max-height: 160px;
    overflow:auto;
    padding-right: 2px;
  }
  .u-popover-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 10px;
    border: 1px solid #eef2f7;
    border-radius: 10px;
    padding: 8px 8px;
  }
  .u-popover-left{
    display:flex;
    align-items:center;
    gap: 8px;
    min-width: 0;
  }
  .u-dot{
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background:#184f61;
    flex: 0 0 auto;
  }
  .u-popover-name{
    font-size: 13px;
    color:#0f172a;
    white-space: nowrap;
    overflow:hidden;
    text-overflow: ellipsis;
  }
  .u-popover-val{
    font-size: 13px;
    color:#0f172a;
    flex: 0 0 auto;
  }
  .u-popover-foot{
    margin-top: 8px;
    font-size: 11px;
    color:#94a3b8;
    text-align:right;
  }

  .u-chart-divider{
    height: 1px;
    background:#e6eef2;
    margin: 10px 0 12px;
  }

  .u-chart-filters{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 12px;
  }

  /* ✅ UPDATE: hanya 1 filter (tahun) dan full width */
  .u-chart-filters--one{
    grid-template-columns: 1fr !important;
  }
  .u-select--full{
    width: 100%;
  }
  .u-select--full select{
    width: 100%;
  }

  .u-select{ position:relative; }
  .u-select select{
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 38px 10px 12px;
    font-size: 16px;
    outline:none;
    background:#fff;
    appearance:none;
  }
  .u-select i{
    position:absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    opacity:.6;
    pointer-events:none;
  }
  .u-canvas-wrap{
    height: 260px;
    display:flex;
    align-items:center;
    justify-content:center;
  }
  .u-canvas-wrap canvas{
    max-height: 260px !important;
  }

  /* ✅ supaya muat 1 layar (tanpa scroll) */
  .u-sum{ gap: 14px; }
  .u-sum-row{ gap: 14px; }
  .u-card{ padding: 14px 14px 12px; }
  .u-label{ margin-bottom: 4px; }
  .u-value, .u-money{ font-size: 32px; }
  .u-sub{ margin-top: 6px; }
  .u-charts{ margin-top: 14px; gap: 14px; }
  .u-chart-card{ padding: 12px 14px 14px; }
  .u-chart-divider{ margin: 8px 0 10px; }
  .u-chart-filters{ margin-bottom: 10px; }
  .u-canvas-wrap{ height: 230px; }
  .u-canvas-wrap canvas{ max-height: 230px !important; }

  @media(max-width:1100px){
    .u-sum-row--3{ grid-template-columns: 1fr; }
    .u-sum-row--2{ grid-template-columns: 1fr; }
    .u-charts{ grid-template-columns: 1fr; }
    .u-money, .u-value{ font-size: 28px; }

    /* biar mini filter tetap enak di mobile */
    .u-card-filter{
      right: 10px;
      bottom: 10px;
    }

    /* di layar kecil: tetap no-scroll page, tapi konten akan mengecil */
    .u-canvas-wrap{ height: 220px; }
    .u-canvas-wrap canvas{ max-height: 220px !important; }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function(){

    // =========================
    // ✅ COUNT-UP ANIMATION (BARU)
    // =========================
    const CountFX = (() => {
      const DEFAULT_DURATION = 1200; // cepat
      const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

      const parseNumber = (s) => {
        const raw = String(s ?? '').replace(/[^\d.-]/g,'');
        const n = Number(raw);
        return Number.isFinite(n) ? n : 0;
      };

      const formatID = (n) => Math.round(n).toLocaleString('id-ID');

      const formatRupiah = (n) => {
        const x = Math.max(0, Math.round(Number(n || 0)));
        const parts = x.toString().split('');
        let out = '';
        for(let i=0;i<parts.length;i++){
          const idx = parts.length - i;
          out += parts[i];
          if(idx > 1 && idx % 3 === 1) out += '.';
        }
        return 'Rp ' + out;
      };

      const getTarget = (el) => {
        const dc = el.getAttribute('data-count');
        if(dc !== null && dc !== '') return parseNumber(dc);
        return parseNumber(el.textContent);
      };

      const isMoney = (el) => {
        return el.classList.contains('u-money') || /rp/i.test(String(el.textContent)) || /rp/i.test(String(el.getAttribute('data-format') || ''));
      };

      const setText = (el, val) => {
        el.textContent = isMoney(el) ? formatRupiah(val) : formatID(val);
      };

      const animate = (el, to, duration = DEFAULT_DURATION) => {
        if(el.dataset.counted === '1') return;
        el.dataset.counted = '1';

        const from = 0;
        const start = performance.now();

        setText(el, 0);

        const tick = (now) => {
          const t = Math.min(1, (now - start) / duration);
          const p = easeOutCubic(t);
          const cur = from + (to - from) * p;
          setText(el, cur);
          if(t < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
      };

      const playOnceWhenVisible = (els) => {
        const list = Array.from(els || []);
        if(!list.length) return;

        if(!('IntersectionObserver' in window)){
          list.forEach(el => animate(el, getTarget(el)));
          return;
        }

        const io = new IntersectionObserver((entries) => {
          entries.forEach(ent => {
            if(ent.isIntersecting){
              const el = ent.target;
              animate(el, getTarget(el));
              io.unobserve(el);
            }
          });
        }, { threshold: 0.25 });

        list.forEach(el => io.observe(el));
      };

      // rerun ketika nilai berubah karena filter
      const rerunTo = (el, nextValue, duration = DEFAULT_DURATION) => {
        const to = Number(nextValue || 0);
        const from = parseNumber(el.textContent);

        const start = performance.now();
        const tick = (now) => {
          const t = Math.min(1, (now - start) / duration);
          const p = easeOutCubic(t);
          const cur = from + (to - from) * p;
          el.textContent = formatID(cur);
          if(t < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
      };

      const rerunMoneyTo = (el, nextValue, duration = DEFAULT_DURATION) => {
        const to = Number(nextValue || 0);
        const from = parseNumber(el.textContent);

        const start = performance.now();
        const tick = (now) => {
          const t = Math.min(1, (now - start) / duration);
          const p = easeOutCubic(t);
          const cur = from + (to - from) * p;
          el.textContent = formatRupiah(cur);
          if(t < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
      };

      return { playOnceWhenVisible, rerunTo, rerunMoneyTo };
    })();

    // jalankan animasi untuk semua angka summary (sekali)
    CountFX.playOnceWhenVisible(document.querySelectorAll('.js-count'));

    // ✅ warna donut sesuai gambar (teal gelap, hitam, kuning, coklat muda)
    const donutColors = ['#0B4A5E', '#111827', '#F6C100', '#D6A357'];

    // helper: biar label bar tidak miring + jadi atas-bawah kalau 2 kata
    const splitLabel = (value) => {
      if (Array.isArray(value)) return value;
      const s = String(value ?? '');
      // utamakan \n yang sudah ada di data
      if (s.includes('\n')) return s.split('\n');
      // kalau belum ada \n, pecah 2 kata jadi 2 baris
      const parts = s.trim().split(/\s+/);
      if (parts.length === 2) return [parts[0], parts[1]];
      return s;
    };

    // =========================
    // ✅ POPOVER + DETAIL BUILDER
    // =========================
    const closeAllPopovers = () => {
      document.querySelectorAll('.u-popover.is-open').forEach(p => {
        p.classList.remove('is-open');
        p.setAttribute('aria-hidden', 'true');
      });
    };

    // klik di luar = tutup
    document.addEventListener('click', function(e){
      const isInside = e.target.closest('.u-chart-head');
      if(!isInside) closeAllPopovers();
    });

    // toggle popover by button
    document.querySelectorAll('.u-info-btn').forEach(btn => {
      btn.addEventListener('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        const id = btn.getAttribute('data-pop');
        const pop = document.getElementById(id);
        if(!pop) return;

        const isOpen = pop.classList.contains('is-open');
        closeAllPopovers();
        if(!isOpen){
          pop.classList.add('is-open');
          pop.setAttribute('aria-hidden', 'false');
        }
      });
    });

    const fmtInt = (n) => {
      const x = Number(n || 0);
      return (isFinite(x) ? Math.round(x) : 0).toString();
    };

    const buildDetail = (chart, opts = {}) => {
      if(!chart) return { meta:'—', rows: [] };
      const labels = chart.data.labels || [];
      const data = (chart.data.datasets && chart.data.datasets[0] && chart.data.datasets[0].data) ? chart.data.datasets[0].data : [];
      const colors = (chart.data.datasets && chart.data.datasets[0] && chart.data.datasets[0].backgroundColor) ? chart.data.datasets[0].backgroundColor : [];
      const total = data.reduce((a,b) => a + (Number(b)||0), 0) || 0;

      const metaParts = [];
      if(opts.tahun !== undefined && opts.tahun !== null) metaParts.push(`Tahun: ${opts.tahun || 'Semua'}`);
      metaParts.push(`Total: ${fmtInt(total)}`);

      const rows = labels.map((name, i) => {
        const val = Number(data[i] || 0);
        const pct = total > 0 ? Math.round((val/total)*100) : 0;
        return {
          name: String(name),
          val: `${fmtInt(val)} (${pct}%)`,
          color: Array.isArray(colors) ? (colors[i] || '#184f61') : (colors || '#184f61')
        };
      });

      // sort by value desc biar informatif
      rows.sort((a,b) => {
        const av = parseInt((a.val || '0').replace(/[^0-9]/g,''), 10) || 0;
        const bv = parseInt((b.val || '0').replace(/[^0-9]/g,''), 10) || 0;
        return bv - av;
      });

      return { meta: metaParts.join(' • '), rows };
    };

    const renderDetailTo = (detail, metaEl, listEl) => {
      if(metaEl) metaEl.textContent = detail.meta || '—';
      if(!listEl) return;

      listEl.innerHTML = '';
      (detail.rows || []).forEach(r => {
        const row = document.createElement('div');
        row.className = 'u-popover-row';

        const left = document.createElement('div');
        left.className = 'u-popover-left';

        const dot = document.createElement('span');
        dot.className = 'u-dot';
        dot.style.background = r.color || '#184f61';

        const name = document.createElement('div');
        name.className = 'u-popover-name';
        name.textContent = r.name;

        const val = document.createElement('div');
        val.className = 'u-popover-val';
        val.textContent = r.val;

        left.appendChild(dot);
        left.appendChild(name);
        row.appendChild(left);
        row.appendChild(val);
        listEl.appendChild(row);
      });
    };

    // =========================
    // ✅ DUMMY "BERUBAH SESUAI FILTER"
    // (nanti backend tinggal ganti fetch)
    // =========================
    const hashKey = (s) => {
      const str = String(s || '');
      let h = 0;
      for(let i=0;i<str.length;i++){
        h = ((h<<5) - h) + str.charCodeAt(i);
        h |= 0;
      }
      return Math.abs(h);
    };

    const makeScaled = (baseArr, keyStr) => {
      const k = hashKey(keyStr);
      const mul = 0.75 + ((k % 51) / 100); // 0.75 - 1.25
      return baseArr.map((v, i) => {
        const wave = 0.92 + (((k + i*17) % 21) / 100); // 0.92 - 1.12
        const out = Math.max(0, Math.round(Number(v || 0) * mul * wave));
        return out;
      });
    };

    // ✅ helper untuk summary per tahun (paket & nilai)
    const parseRupiah = (txt) => {
      const s = String(txt || '').replace(/[^0-9]/g,'');
      const n = parseInt(s || '0', 10);
      return isFinite(n) ? n : 0;
    };

    const formatRupiah = (n) => {
      const x = Math.max(0, Math.round(Number(n || 0)));
      const parts = x.toString().split('');
      let out = '';
      for(let i=0;i<parts.length;i++){
        const idx = parts.length - i;
        out += parts[i];
        if(idx > 1 && idx % 3 === 1) out += '.';
      }
      return 'Rp ' + out;
    };

    const makeScaledSingle = (baseNumber, keyStr) => {
      const k = hashKey(keyStr);
      const mul = 0.75 + ((k % 51) / 100);     // 0.75 - 1.25
      const wave = 0.90 + (((k + 17) % 23) / 100); // 0.90 - 1.12
      return Math.max(0, Math.round(Number(baseNumber || 0) * mul * wave));
    };

    // =========================
    // CHART INSTANCES
    // =========================
    let donutChart = null;
    let barChart = null;

    // Donut
    const donutCtx = document.getElementById('donutStatus');
    if(donutCtx){
      donutChart = new Chart(donutCtx, {
        type: 'doughnut',
        data: {
          labels: @json($statusLabels),
          datasets: [{
            data: @json($statusValues),
            backgroundColor: donutColors,
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
           animation: {
    duration: 1800,
    easing: 'easeOutQuart'
  },
          layout: { padding: { right: 70 } },
          plugins: {
            legend: {
              position: 'right',
              labels: {
                boxWidth: 10,
                boxHeight: 10,
                padding: 12,
                font: { family: 'Nunito', weight: '400', size: 14 }
              }
            },
            tooltip: { enabled: true }
          },
          cutout: '55%'
        }
      });
    }

    // Bar (6 batang seperti gambar)
    const barCtx = document.getElementById('barStatus');
    if(barCtx){
      barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: @json($barLabels),
          datasets: [{
            label: '2020',
            data: @json($barValues),
            backgroundColor: '#F6C100',
            borderWidth: 0,
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
           animation: {
    duration: 1800,
    easing: 'easeOutQuart'
  },
          plugins: {
            legend: {
              position: 'bottom',
              labels: { font: { family: 'Nunito', weight: '400', size: 14 } }
            },
            tooltip: { enabled: true }
          },
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
              ticks: {
                stepSize: 20,
                precision: 0,
                font: { family: 'Nunito', weight: '400', size: 14 }
              }
            },
            x: {
              ticks: {
                maxRotation: 0,
                minRotation: 0,
                autoSkip: false,
                padding: 6,
                font: { family: 'Nunito', weight: '400', size: 11 },
                callback: function(value){
                  const raw = this.getLabelForValue(value);
                  return splitLabel(raw);
                }
              },
              grid: { display: false }
            }
          }
        }
      });
    }

    // =========================
    // ✅ DETAIL POPUP CONTENT (REALTIME)
    // =========================
    const metaDonut = document.getElementById('metaDonut');
    const listDonut = document.getElementById('listDonut');
    const metaBar   = document.getElementById('metaBar');
    const listBar   = document.getElementById('listBar');

    const refreshDonutDetail = () => {
      const tahun = (document.getElementById('fTahun1')?.value || '');
      const detail = buildDetail(donutChart, { tahun });
      renderDetailTo(detail, metaDonut, listDonut);
    };

    const refreshBarDetail = () => {
      const tahun = (document.getElementById('fTahun2')?.value || '');
      const detail = buildDetail(barChart, { tahun });
      renderDetailTo(detail, metaBar, listBar);
    };

    // first render
    refreshDonutDetail();
    refreshBarDetail();

    // =========================
    // ✅ FILTER LISTENERS: update data + update popup
    // =========================
    const fTahun1 = document.getElementById('fTahun1');
    const fTahun2 = document.getElementById('fTahun2');

    const applyDonutFilter = () => {
      if(!donutChart) return;
      const tahun = (fTahun1?.value || '');
      const key = `${tahun}`;

      const base = @json($statusValues);
      donutChart.data.datasets[0].data = makeScaled(base, key);
      donutChart.update();

      refreshDonutDetail();
    };

    const applyBarFilter = () => {
      if(!barChart) return;
      const tahun = (fTahun2?.value || '');
      const key = `${tahun}`;

      const base = @json($barValues);
      const next = makeScaled(base, key).map(v => Math.min(100, v));
      barChart.data.datasets[0].data = next;
      barChart.data.datasets[0].label = tahun ? String(tahun) : 'Semua';
      barChart.update();

      refreshBarDetail();
    };

    if(fTahun1) fTahun1.addEventListener('change', applyDonutFilter);
    if(fTahun2) fTahun2.addEventListener('change', applyBarFilter);

    // =========================
    // ✅ FILTER TAHUN SUMMARY (PAKET & NILAI) - BEKERJA
    // =========================
    const fPaket = document.getElementById('fTahunPaket');
    const fNilai = document.getElementById('fTahunNilai');
    const elPaket = document.getElementById('valPaket');
    const elNilai = document.getElementById('valNilai');

    const basePaket = Number(@json($summary[3]['value'])) || 0;
    const baseNilai = parseRupiah(@json($summary[4]['value']));

    const applyPaketByYear = () => {
      if(!fPaket || !elPaket) return;
      const tahun = String(fPaket.value || '');
      const next = makeScaledSingle(basePaket, `paket__${tahun}`);

      // ✅ update target + animasi
      elPaket.setAttribute('data-count', String(next));
      CountFX.rerunTo(elPaket, next);

      // (logic lama)
      // elPaket.textContent = fmtInt(next);
    };

    const applyNilaiByYear = () => {
      if(!fNilai || !elNilai) return;
      const tahun = String(fNilai.value || '');
      const next = makeScaledSingle(baseNilai, `nilai__${tahun}`);

      // ✅ update target + animasi rupiah
      elNilai.setAttribute('data-count', String(next));
      CountFX.rerunMoneyTo(elNilai, next);

      // (logic lama)
      // elNilai.textContent = formatRupiah(next);
    };

    if(fPaket) fPaket.addEventListener('change', applyPaketByYear);
    if(fNilai) fNilai.addEventListener('change', applyNilaiByYear);

    // render awal sesuai pilihan default (pakai animasi juga)
    applyPaketByYear();
    applyNilaiByYear();

    // =========================
    // ✅ Saat popup dibuka, pastikan konten selalu terbaru
    // =========================
    const popDonut = document.getElementById('popDonut');
    const popBar   = document.getElementById('popBar');

    const observer = new MutationObserver(() => {
      if(popDonut && popDonut.classList.contains('is-open')) refreshDonutDetail();
      if(popBar && popBar.classList.contains('is-open')) refreshBarDetail();
    });
    if(popDonut) observer.observe(popDonut, { attributes:true, attributeFilter:['class'] });
    if(popBar) observer.observe(popBar, { attributes:true, attributeFilter:['class'] });
  });
</script>

</body>
</html>