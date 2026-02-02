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
              <div class="u-value u-value--navy">{{ $summary[0]['value'] }}</div>
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
              <div class="u-value u-value--yellow">{{ $summary[1]['value'] }}</div>
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
              <div class="u-value u-value--gray">{{ $summary[2]['value'] }}</div>
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
              <div class="u-value u-value--navy">{{ $summary[3]['value'] }}</div>
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
              <div class="u-money">{{ $summary[4]['value'] }}</div>
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
        {{-- ✅ subjudul harus normal --}}
        <div class="u-chart-title">Status Arsip</div>
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
        <div class="u-chart-title">Metode Pengadaan</div>
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
  }

  /* subjudul normal */
  .u-chart-title{
    text-align:center;
    font-size: 20px;
    color:#0f172a;
    margin-top: 2px;
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

    // Donut
    const donutCtx = document.getElementById('donutStatus');
    if(donutCtx){
      new Chart(donutCtx, {
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

          // ✅ legend agak ke tengah (geser dari kanan)
          layout: {
            padding: {
              right: 70   // makin kecil = makin ke tengah
            }
          },

          plugins: {
            legend: {
              position: 'right',
              labels: {
                boxWidth: 10,
                boxHeight: 10,
                padding: 12, /* ✅ rapatin dikit */
                /* ✅ normal */
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
      new Chart(barCtx, {
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
                // ✅ jangan miring, pecah jadi atas-bawah (multiline) biar tidak tabrakan
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

    // (opsional) contoh listener kalau nanti mau dipakai untuk request backend
    const fPaket = document.getElementById('fTahunPaket');
    const fNilai = document.getElementById('fTahunNilai');
    if(fPaket){
      fPaket.addEventListener('change', function(){
        // nanti backend: fetch total paket berdasarkan tahun
        // console.log('filter paket tahun:', this.value);
      });
    }
    if(fNilai){
      fNilai.addEventListener('change', function(){
        // nanti backend: fetch total nilai berdasarkan tahun
        // console.log('filter nilai tahun:', this.value);
      });
    }
  });
</script>

</body>
</html>
