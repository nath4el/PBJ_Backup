{{-- resources/views/PPK/ArsipPBJ.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Arsip PBJ - SIAPABAJA</title>

  {{-- Font Nunito --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  {{-- CSS dashboard unit --}}
  <link rel="stylesheet" href="{{ asset('css/Unit.css') }}">
</head>

{{-- ✅ Tambah 1 class khusus page ini biar CSS inline bisa di-scope dan gak konflik dengan Unit.css --}}
<body class="dash-body page-arsip">
@php
  $unitName = "Fakultas Teknik";

  // =========================
  // DATA SOURCE:
  // - Jika controller sudah kirim $arsips (paginator), pakai itu
  // - Jika belum, fallback ke dummy lama (biar halaman tidak pecah)
  // =========================
  if (isset($arsips) && $arsips instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
    // map data dari controller (judul/metode/status) ke schema lama ($rows)
    $rows = collect($arsips->items())->map(function($item) use ($unitName){
      // pastikan array
      $r = is_array($item) ? $item : (array) $item;

      return [
        "id" => $r["id"] ?? null,
        "tahun" => (string)($r["tahun"] ?? ""),
        "unit" => $r["unit"] ?? $unitName,
        "pekerjaan" => $r["pekerjaan"] ?? ($r["judul"] ?? "-"),
        "jenis_pbj" => $r["jenis_pbj"] ?? "Pengadaan Pekerjaan Konstruksi",
        "metode_pbj" => $r["metode_pbj"] ?? ($r["metode"] ?? "-"),
        "nilai_kontrak" => $r["nilai_kontrak"] ?? ($r["kontrak"] ?? ($r["nilai"] ?? "-")),
        "status_arsip" => $r["status_arsip"] ?? "Publik",
        "status_pekerjaan" => $r["status_pekerjaan"] ?? ($r["status"] ?? "-"),

        // ✅ tambahan field untuk sinkron detail (aman, kalau belum ada tetap fallback nanti)
        "idrup" => $r["idrup"] ?? ($r["id_rup"] ?? null),
        "rekanan" => $r["rekanan"] ?? ($r["nama_rekanan"] ?? null),
        "jenis" => $r["jenis"] ?? ($r["jenis_pengadaan"] ?? null),
        "pagu" => $r["pagu"] ?? ($r["pagu_anggaran"] ?? null),
        "hps" => $r["hps"] ?? null,
      ];
    })->values()->all();
  } else {
    // dummy data tabel arsip (nanti backend tinggal ganti)
    $rows = [
      [
        "id" => 1,
        "tahun"=>"2024",
        "unit"=>"Fakultas Teknik",
        "pekerjaan"=>"Pengadaan Laboratorium Komputer Terpadu | RUP-2026-001-FT",
        "jenis_pbj"=>"Pengadaan Pekerjaan Konstruksi", // (tetap ada di data, tapi kolomnya dihapus)
        "metode_pbj"=>"Pengadaan Langsung",           // (tetap ada di data, tapi kolom preview dihapus)
        "nilai_kontrak"=>"Rp. 100.866.549.000,00",
        "status_arsip"=>"Publik",
        "status_pekerjaan"=>"Perencanaan",

        // ✅ optional dummy untuk detail (biar kalau mau sync tinggal pakai)
        "idrup" => "2026",
        "rekanan" => "PT Jadi Kaya Bersama",
        "jenis" => "Tender",
        "pagu" => "Rp 500.000.000",
        "hps" => "Rp 480.000.000",
      ],
      [
        "id" => 2,
        "tahun"=>"2024",
        "unit"=>"Fakultas Ekonomi dan Bisnis",
        "pekerjaan"=>"Pengadaan Laboratorium Komputer Terpadu | RUP-2026-002-FEB",
        "jenis_pbj"=>"Pengadaan Pekerjaan Konstruksi",
        "metode_pbj"=>"Pengadaan Langsung",
        "nilai_kontrak"=>"Rp. 100.866.549.000,00",
        "status_arsip"=>"Privat",
        "status_pekerjaan"=>"Pemilihan",

        "idrup" => "2026",
        "rekanan" => "PT Jadi Kaya Bersama",
        "jenis" => "Tender",
        "pagu" => "Rp 500.000.000",
        "hps" => "Rp 480.000.000",
      ],
      [
        "id" => 3,
        "tahun"=>"2024",
        "unit"=>"Lembaga Penjaminan Mutu dan Pengembangan Pembelajaran (LPMPP)",
        "pekerjaan"=>"Pengadaan Laboratorium Komputer Terpadu | RUP-2026-003-FAPET",
        "jenis_pbj"=>"Pengadaan Pekerjaan Konstruksi",
        "metode_pbj"=>"Pengadaan Langsung",
        "nilai_kontrak"=>"Rp. 100.866.549.000,00",
        "status_arsip"=>"Publik",
        "status_pekerjaan"=>"Pelaksanaan",

        "idrup" => "2026",
        "rekanan" => "PT Jadi Kaya Bersama",
        "jenis" => "Tender",
        "pagu" => "Rp 500.000.000",
        "hps" => "Rp 480.000.000",
      ],
      [
        "id" => 4,
        "tahun"=>"2024",
        "unit"=>"Fakultas Hukum",
        "pekerjaan"=>"Pengadaan Laboratorium Komputer Terpadu | RUP-2026-004-FH",
        "jenis_pbj"=>"Pengadaan Pekerjaan Konstruksi",
        "metode_pbj"=>"Pengadaan Langsung",
        "nilai_kontrak"=>"Rp. 100.866.549.000,00",
        "status_arsip"=>"Privat",
        "status_pekerjaan"=>"Selesai",

        "idrup" => "2026",
        "rekanan" => "PT Jadi Kaya Bersama",
        "jenis" => "Tender",
        "pagu" => "Rp 500.000.000",
        "hps" => "Rp 480.000.000",
      ],
    ];
  }

  $years = array_values(array_unique(array_map(fn($x) => $x['tahun'], $rows)));
  rsort($years);

  // ✅ UPDATE: unit options untuk filter (dummy / current page)
  $unitOptions = array_values(array_unique(array_map(fn($x) => $x['unit'], $rows)));
  sort($unitOptions);
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
        <div class="dash-role">ADMIN (PPK)</div>
      </div>
    </div>

    <nav class="dash-nav">
      <a class="dash-link" href="{{ url('/ppk/dashboard') }}">
        <span class="ic"><i class="bi bi-grid-fill"></i></span>
        Dashboard
      </a>

      <a class="dash-link active" href="{{ url('/ppk/arsip') }}">
        <span class="ic"><i class="bi bi-archive"></i></span>
        Arsip PBJ
      </a>

      <a class="dash-link" href="{{ url('/ppk/pengadaan/tambah') }}">
        <span class="ic"><i class="bi bi-plus-square"></i></span>
        Tambah Pengadaan
      </a>
    </nav>

    {{-- tombol bawah --}}
    <div class="dash-side-actions">
      <a class="dash-side-btn" href="{{ url('/ppk/dashboard') }}">
        <i class="bi bi-house-door"></i>
        Kembali
      </a>

      <a class="dash-side-btn" href="{{ url('/logout') }}">
        <i class="bi bi-box-arrow-right"></i>
        Keluar
      </a>
    </div>
  </aside>

  {{-- MAIN --}}
  <main class="dash-main">
    <header class="dash-header ap-header">
      <div class="ap-header-left">
        <h1>Arsip PBJ</h1>
        <p>Kelola arsip pengadaan barang dan jasa Universitas Jenderal Soedirman</p>
      </div>

      {{-- ✅ NEW: Tombol Cetak Arsip (kanan atas) --}}
      <div class="ap-header-right">
        <button type="button" id="apPrintBtn" class="ap-print-btn" title="Cetak Arsip">
          <i class="bi bi-printer"></i>
          Export Arsip
        </button>
      </div>
    </header>

    {{-- FILTER BAR --}}
    <section class="dash-filter ap-filter">
      <div class="ap-filter-row">
        <div class="ap-search">
          <i class="bi bi-search"></i>
          <input id="apSearchInput" type="text" placeholder="Cari..." />
        </div>

        {{-- ✅ UPDATE: FILTER UNIT (di kanan search) --}}
        <div class="ap-select">
          <select id="apUnitFilter">
            <option value="Semua">Semua Unit</option>
            @foreach($unitOptions as $u)
              <option value="{{ $u }}">{{ $u }}</option>
            @endforeach
          </select>
          <i class="bi bi-chevron-down"></i>
        </div>

        <div class="ap-select">
          <select id="apStatusFilter">
            <option value="Semua">Semua Status</option>
            <option value="Publik">Publik</option>
            <option value="Privat">Privat</option>
          </select>
          <i class="bi bi-chevron-down"></i>
        </div>

        <div class="ap-select">
          <select id="apYearFilter">
            <option value="Semua">Semua Tahun</option>
            @foreach($years as $y)
              <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
          </select>
          <i class="bi bi-chevron-down"></i>
        </div>

        <div class="ap-tools">
          <button type="button" id="apRefreshBtn" class="ap-icbtn" title="Refresh">
            <i class="bi bi-arrow-clockwise"></i>
          </button>

          <a href="#" id="apEditLink" class="ap-icbtn is-disabled" title="Edit" aria-disabled="true">
            <i class="bi bi-pencil"></i>
          </a>

          <button type="button" id="apDeleteBtn" class="ap-icbtn is-disabled" title="Hapus" aria-disabled="true">
            <i class="bi bi-trash3"></i>
          </button>
        </div>
      </div>
    </section>

    {{-- TABLE ARSIP --}}
    <section class="dash-table ap-table">
      <div class="ap-head">
        <div class="ap-check">
          <input id="apSelectAll" type="checkbox" aria-label="Pilih semua" />
        </div>

        {{-- Tahun = center --}}
        <div class="ap-col-center">Tahun</div>

        {{-- Unit Kerja = kiri --}}
        <div class="ap-col-left">Unit Kerja</div>

        {{-- Nama Pekerjaan = kiri --}}
        <div class="ap-col-left">Nama Pekerjaan</div>

        {{-- ✅ Metode PBJ dihapus dari preview --}}

        {{-- Nilai Kontrak = center --}}
        <div class="ap-col-center">Nilai Kontrak</div>

        {{-- Status Arsip = center --}}
        <div class="ap-col-center">Status Arsip</div>

        {{-- Status Pekerjaan = center --}}
        <div class="ap-col-center">Status Pekerjaan</div>

        {{-- Aksi = center --}}
        <div class="ap-col-center" style="text-align:center;">Aksi</div>
      </div>

      @foreach($rows as $r)
        @php
          $sp = strtolower(trim($r['status_pekerjaan']));
          $spClass = match ($sp) {
            'perencanaan' => 'ap-sp ap-sp-plan',
            'pemilihan'   => 'ap-sp ap-sp-select',
            'pelaksanaan' => 'ap-sp ap-sp-do',
            'selesai'     => 'ap-sp ap-sp-done',
            default       => 'ap-sp',
          };
        @endphp

        <div class="ap-row"
             data-status="{{ $r['status_arsip'] }}"
             data-year="{{ $r['tahun'] }}"
             data-unit="{{ $r['unit'] }}"
             data-search="{{ strtolower($r['tahun'].' '.$r['unit'].' '.$r['pekerjaan'].' '.$r['metode_pbj'].' '.$r['nilai_kontrak'].' '.$r['status_pekerjaan']) }}">

          <div class="ap-check">
            <input class="ap-row-check" type="checkbox" value="{{ $r['id'] }}" aria-label="Pilih baris" />
          </div>

          {{-- Tahun = center --}}
          <div class="ap-year ap-col-center">{{ $r['tahun'] }}</div>

          {{-- Unit Kerja = kiri --}}
          <div class="ap-unit ap-col-left">{{ $r['unit'] }}</div>

          {{-- Nama Pekerjaan = kiri --}}
          <div class="ap-job ap-col-left">
            {{ $r['pekerjaan'] }}
          </div>

          {{-- ✅ Metode PBJ dihapus dari preview --}}

          {{-- Nilai Kontrak = center --}}
          <div class="ap-col-center">
            <span class="ap-money">{{ $r['nilai_kontrak'] }}</span>
          </div>

          {{-- Status Arsip = center --}}
          <div class="ap-arsip ap-col-center ap-arsip-center">
            @if($r['status_arsip'] === 'Publik')
              <span class="ap-eye ap-eye-pub"><i class="bi bi-eye"></i> Publik</span>
            @else
              <span class="ap-eye ap-eye-pri"><i class="bi bi-eye-slash"></i> Privat</span>
            @endif
          </div>

          {{-- Status Pekerjaan = center --}}
          <div class="ap-col-center">
            <span class="{{ $spClass }}">{{ $r['status_pekerjaan'] }}</span>
          </div>

          {{-- Aksi = center --}}
          <div class="ap-aksi ap-col-center">
            <a href="#"
              class="ap-detail js-open-detail"
              data-title="{{ $r['pekerjaan'] }}"
              data-unit="{{ $r['unit'] }}"
              data-tahun="{{ $r['tahun'] }}"
              data-idrup="{{ $r['idrup'] ?? '2026' }}"
              data-status="{{ $r['status_pekerjaan'] }}"
              data-rekanan="{{ $r['rekanan'] ?? 'PT Jadi Kaya Bersama' }}"
              data-jenis="{{ $r['jenis'] ?? 'Tender' }}"
              data-pagu="{{ $r['pagu'] ?? 'Rp 500.000.000' }}"
              data-hps="{{ $r['hps'] ?? 'Rp 480.000.000' }}"
              data-kontrak="{{ $r['nilai_kontrak'] }}"
            >Detail</a>
          </div>
        </div>
      @endforeach

      {{-- ✅ ADD: PAGINATION (di bawah tabel) --}}
      @if(isset($arsips) && $arsips instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <div class="ap-pagination-wrap">
          <div class="ap-page-info">
            Halaman {{ $arsips->currentPage() }} dari {{ $arsips->lastPage() }}
            • Menampilkan {{ $arsips->count() }} dari {{ $arsips->total() }} data
          </div>

          <div class="ap-pagination">
            {{-- Prev --}}
            <a class="ap-page-btn {{ $arsips->onFirstPage() ? 'is-disabled' : '' }}"
               href="{{ $arsips->onFirstPage() ? '#' : $arsips->previousPageUrl() }}"
               aria-disabled="{{ $arsips->onFirstPage() ? 'true' : 'false' }}">
              <i class="bi bi-chevron-left"></i>
            </a>

            @php
              $current = $arsips->currentPage();
              $last = $arsips->lastPage();
              $start = max(1, $current - 2);
              $end   = min($last, $current + 2);
            @endphp

            @if($start > 1)
              <a class="ap-page-btn" href="{{ $arsips->url(1) }}">1</a>
              @if($start > 2)
                <span class="ap-page-btn is-ellipsis" aria-hidden="true">…</span>
              @endif
            @endif

            @for($i = $start; $i <= $end; $i++)
              <a class="ap-page-btn {{ $i === $current ? 'is-active' : '' }}"
                 href="{{ $arsips->url($i) }}">
                {{ $i }}
              </a>
            @endfor

            @if($end < $last)
              @if($end < $last - 1)
                <span class="ap-page-btn is-ellipsis" aria-hidden="true">…</span>
              @endif
              <a class="ap-page-btn" href="{{ $arsips->url($last) }}">{{ $last }}</a>
            @endif

            {{-- Next --}}
            <a class="ap-page-btn {{ $arsips->hasMorePages() ? '' : 'is-disabled' }}"
               href="{{ $arsips->hasMorePages() ? $arsips->nextPageUrl() : '#' }}"
               aria-disabled="{{ $arsips->hasMorePages() ? 'false' : 'true' }}">
              <i class="bi bi-chevron-right"></i>
            </a>
          </div>
        </div>
      @endif
      {{-- ✅ END PAGINATION --}}

    </section>
  </main>
</div>

<!-- ====== MODAL DETAIL (POPUP) ====== -->
<div class="dt-modal" id="dtModal" aria-hidden="true">
  <div class="dt-backdrop" data-close="true"></div>

  <div class="dt-panel" role="dialog" aria-modal="true" aria-labelledby="dtTitle">
    <div class="dt-card">
      <button type="button" class="dt-close-inside" id="dtCloseBtn" aria-label="Tutup">
        <i class="bi bi-x-lg"></i>
      </button>

      <div class="dt-title" id="dtTitle">-</div>

      {{-- (modal content kamu tetap) --}}
      <div class="dt-info-grid">
        <div class="dt-info">
          <div class="dt-ic"><i class="bi bi-envelope"></i></div>
          <div class="dt-info-txt">
            <div class="dt-label">Unit Kerja</div>
            <div class="dt-val" id="dtUnit">-</div>
          </div>
        </div>

        <div class="dt-info">
          <div class="dt-ic"><i class="bi bi-calendar-event"></i></div>
          <div class="dt-info-txt">
            <div class="dt-label">Tahun Anggaran</div>
            <div class="dt-val" id="dtTahun">-</div>
          </div>
        </div>

        <div class="dt-info">
          <div class="dt-ic"><i class="bi bi-person-badge"></i></div>
          <div class="dt-info-txt">
            <div class="dt-label">ID RUP</div>
            <div class="dt-val" id="dtIdRup">-</div>
          </div>
        </div>

        <div class="dt-info">
          <div class="dt-ic"><i class="bi bi-folder2"></i></div>
          <div class="dt-info-txt">
            <div class="dt-label">Status Pekerjaan</div>
            <div class="dt-val" id="dtStatus">-</div>
          </div>
        </div>

        <div class="dt-info">
          <div class="dt-ic"><i class="bi bi-person"></i></div>
          <div class="dt-info-txt">
            <div class="dt-label">Nama Rekanan</div>
            <div class="dt-val" id="dtRekanan">-</div>
          </div>
        </div>

        <div class="dt-info">
          <div class="dt-ic"><i class="bi bi-box"></i></div>
          <div class="dt-info-txt">
            <div class="dt-label">Jenis Pengadaan</div>
            <div class="dt-val" id="dtJenis">-</div>
          </div>
        </div>
      </div>

      <div class="dt-divider"></div>

      <div class="dt-section-title">Informasi Anggaran</div>

      <div class="dt-budget-grid">
        <div class="dt-budget">
          <div class="dt-label">Pagu Anggaran</div>
          <div class="dt-money" id="dtPagu">-</div>
        </div>

        <div class="dt-budget">
          <div class="dt-label">HPs</div>
          <div class="dt-money" id="dtHps">-</div>
        </div>

        <div class="dt-budget">
          <div class="dt-label">Nilai Kontrak</div>
          <div class="dt-money" id="dtKontrak">-</div>
        </div>
      </div>

      <div class="dt-divider"></div>

      <div class="dt-section-title">Dokumen Pengadaan</div>

      <div class="dt-doc-grid">
        <div class="dt-doc-col">
          @for($i=0;$i<4;$i++)
            <div class="dt-doc-item">
              <div class="dt-doc-left">
                <i class="bi bi-file-earmark-text"></i>
                <span>Dokumen RUP</span>
              </div>
              <a href="#" class="dt-download"><i class="bi bi-download"></i> Unduh</a>
            </div>
          @endfor
        </div>

        <div class="dt-doc-col">
          @for($i=0;$i<4;$i++)
            <div class="dt-doc-item">
              <div class="dt-doc-left">
                <i class="bi bi-file-earmark-text"></i>
                <span>Dokumen RUP</span>
              </div>
              <a href="#" class="dt-download"><i class="bi bi-download"></i> Unduh</a>
            </div>
          @endfor
        </div>

        <div class="dt-doc-col">
          @for($i=0;$i<4;$i++)
            <div class="dt-doc-item">
              <div class="dt-doc-left">
                <i class="bi bi-file-earmark-text"></i>
                <span>Dokumen RUP</span>
              </div>
              <a href="#" class="dt-download"><i class="bi bi-download"></i> Unduh</a>
            </div>
          @endfor
        </div>
      </div>

    </div>
  </div>
</div>

<style>
  /* =========================================================
     SCOPED CSS (ANTI KONFLIK dengan Unit.css)
     - Semua rules di-scope ke .page-arsip
  ========================================================= */

  /* === BASE FONT khusus halaman ini === */
  body.page-arsip.dash-body{
    font-size: 18px;
    line-height: 1.6;
  }

  /* ====== token khusus halaman ini (jangan pakai :root global) ====== */
  .page-arsip{
    --ap-field-h: 46px;
    --ap-field-r: 12px;
    --ap-field-px: 12px;

    /* Status Pekerjaan pill */
    --ap-sp-h: 34px;
    --ap-sp-w: 124px;
    --ap-sp-r: 8px;

    /* garis pemisah row */
    --ap-row-divider: 2px;

    /* ✅ Unsoed Yellow */
    --unsoed-yellow: #f6c100;
    --unsoed-yellow-dark: #d9aa00;
  }

  /* ✅ HEADER: bikin kanan-kiri (judul + tombol cetak) */
  .page-arsip .ap-header{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
  }
  .page-arsip .ap-header-left{ min-width: 0; }
  .page-arsip .ap-header-right{
    flex: 0 0 auto;
    display:flex;
    align-items:center;
    justify-content:flex-end;
  }

  /* ✅ Tombol Cetak Arsip (kuning Unsoed) */
  .page-arsip .ap-print-btn{
    height: 42px;
    padding: 0 14px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.08);
    background: var(--unsoed-yellow);
    color: #1b1b1b;
    font-size: 15px;
    font-weight: 400;
    display:inline-flex;
    align-items:center;
    gap:8px;
    cursor:pointer;
    box-shadow: 0 6px 16px rgba(0,0,0,.10);
    transition: .15s ease;
    user-select:none;
    white-space: nowrap;
    letter-spacing: 0.8px;
  }
  .page-arsip .ap-print-btn:hover{
    background: var(--unsoed-yellow-dark);
    transform: translateY(-1px);
  }
  .page-arsip .ap-print-btn:active{
    transform: translateY(0);
  }
  .page-arsip .ap-print-btn i{
    font-size: 16px;
    line-height: 1;
    display:block;
  }

  /* ===== FILTER BAR ===== */
  .page-arsip .ap-filter-row{
    display:flex;
    gap:12px;
    align-items:center;
    flex-wrap:nowrap; /* ✅ supaya muat 1 baris */
  }

  /* ✅ Search dipersempit biar muat + tetap rapi */
  .page-arsip .ap-search{
    position: relative;
    flex: 1 1 auto;
    min-width: 220px; /* lebih kecil dari sebelumnya */
    height: var(--ap-field-h);
    display:flex;
    align-items:center;
  }
  .page-arsip .ap-search i{
    position:absolute;
    left: var(--ap-field-px);
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    opacity: .75;
    pointer-events:none;
  }
  .page-arsip .ap-search input{
    width:100%;
    height: 100%;
    font-size: 16px;
    padding: 0 calc(var(--ap-field-px) + 6px) 0 44px;
    border-radius: var(--ap-field-r);
    box-sizing: border-box;
  }

  .page-arsip .ap-select{
    position: relative;
    flex: 0 0 200px; /* ✅ dipersempit dikit */
    min-width: 200px;
    height: var(--ap-field-h);
    display:flex;
    align-items:center;
  }
  .page-arsip .ap-select select{
    width:100%;
    height: 100%;
    font-size: 16px;
    padding: 0 42px 0 var(--ap-field-px);
    border-radius: var(--ap-field-r);
    box-sizing: border-box;
  }
  .page-arsip .ap-select i{
    position:absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events:none;
  }

  .page-arsip .ap-tools{
    display:flex;
    gap:10px;
    align-items:center;
    flex: 0 0 auto;
  }
  .page-arsip .ap-icbtn{
    width: 40px;
    height: 40px;
    padding: 0;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    line-height: 1;
  }
  .page-arsip .ap-icbtn i{
    font-size: 18px;
    line-height: 1;
    display:block;
  }

  /* =========================
     TABLE: kolom sudah disesuaikan (Metode PBJ dihapus)
     Urutan:
     1 checkbox
     2 Tahun
     3 Unit Kerja
     4 Nama Pekerjaan
     5 Nilai Kontrak
     6 Status Arsip
     7 Status Pekerjaan
     8 Aksi
  ========================= */
  .page-arsip .ap-head,
  .page-arsip .ap-row{
    display:grid;
    grid-template-columns:
      44px   /* checkbox */
      86px   /* tahun */
      1.25fr /* unit kerja */
      2.45fr /* nama pekerjaan */
      1.55fr /* nilai kontrak */
      1.10fr /* status arsip */
      1.25fr /* status pekerjaan */
      90px;  /* aksi */
    column-gap: 18px;
    padding-left: 18px;
    padding-right: 18px;
    font-size: 16px;
    align-items:center;
  }

  /* default: kiri */
  .page-arsip .ap-head > div,
  .page-arsip .ap-row > div{
    text-align:left;
    justify-self:start;
    min-width: 0;
  }

  /* helper kiri */
  .page-arsip .ap-col-left{
    text-align:left !important;
    justify-self:start !important;
  }

  /* helper center */
  .page-arsip .ap-col-center{
    text-align:center !important;
    justify-self:center !important;
  }

  /* status arsip center */
  .page-arsip .ap-arsip-center{
    display:flex;
    justify-content:center;
    align-items:center;
  }

  /* Nama pekerjaan wrap enak */
  .page-arsip .ap-job{
    line-height: 1.35;
    overflow-wrap: anywhere;
  }

  /* Nilai Kontrak: center + tidak pecah */
  .page-arsip .ap-money{
    display:inline-block;
    color: var(--navy2);
    font-weight: 400;
    white-space: nowrap;
    line-height: 1.2;
  }

  /* garis pemisah antar arsip */
  .page-arsip .ap-row{
    border-top: var(--ap-row-divider) solid #eef3f6;
  }

  /* =========================
     STATUS PEKERJAAN
  ========================= */
  .page-arsip .ap-sp{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    height: var(--ap-sp-h);
    width: var(--ap-sp-w);
    padding: 0 14px;
    border-radius: var(--ap-sp-r);
    font-size: 15px;
    white-space: nowrap;
    text-align:center;
  }

  .page-arsip .ap-sp-plan{   background:#FDF0A8; }
  .page-arsip .ap-sp-select{ background:#E8C9FF; }
  .page-arsip .ap-sp-do{     background:#F8B8B8; }
  .page-arsip .ap-sp-done{   background:#BFE9BF; }

  .page-arsip .ap-eye,
  .page-arsip .ap-detail{
    font-size: 15.5px;
  }

  /* MODAL: teks modal */
  .page-arsip .dt-title{ font-size: 20px; }
  .page-arsip .dt-label{ font-size: 15px; }
  .page-arsip .dt-val{ font-size: 16px; }
  .page-arsip .dt-section-title{ font-size: 18px; }
  .page-arsip .dt-money{ font-size: 18px; }
  .page-arsip .dt-doc-item,
  .page-arsip .dt-download{ font-size: 15.5px; }

  .page-arsip .ap-icbtn.is-disabled{
    opacity: .45;
    cursor: not-allowed;
    pointer-events: auto;
  }

  /* ===== FIX: tombol silang (X) center di kotak ===== */
  .page-arsip .dt-close-inside{
    display:grid;
    place-items:center;
    padding:0;
  }
  .page-arsip .dt-close-inside i{
    display:block;
    line-height: 1;
    font-size: 18px;
    transform: translateY(0);
  }

  /* =========================
     ✅ PAGINATION (SCOPED)
  ========================= */
  .page-arsip .ap-pagination-wrap{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding: 14px 18px 16px;
    border-top: 2px solid #eef3f6;
  }

  .page-arsip .ap-page-info{
    font-size: 13.5px;
    color:#64748b;
    white-space: nowrap;
  }

  .page-arsip .ap-pagination{
    display:flex;
    align-items:center;
    gap:6px;
    flex-wrap:wrap;
    justify-content:flex-end;
  }

  .page-arsip .ap-page-btn{
    min-width: 36px;
    height: 34px;
    padding: 0 10px;
    border-radius: 10px;
    border: 1px solid #e6eef2;
    background:#fff;
    color:#0f172a;
    font-size: 13px;
    font-weight: 600;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    transition: .15s ease;
    user-select:none;
  }

  .page-arsip .ap-page-btn:hover{
    border-color:#cfe2ea;
    background:#f8fbfd;
  }

  .page-arsip .ap-page-btn.is-active{
    border-color: transparent;
    background: var(--navy2);
    color:#fff;
  }

  .page-arsip .ap-page-btn.is-disabled{
    opacity: .55;
    pointer-events:none;
    background:#f8fafc;
  }

  .page-arsip .ap-page-btn.is-ellipsis{
    pointer-events:none;
    background: transparent;
    border-color: transparent;
    min-width: 24px;
    padding: 0 4px;
  }

  /* ✅ RESPONSIVE: kalau layar sempit, boleh turun baris biar gak rusak */
  @media (max-width: 1100px){
    .page-arsip .ap-filter-row{ flex-wrap: wrap; }
    .page-arsip .ap-search{ flex: 1 1 320px; min-width: 260px; }
    .page-arsip .ap-select{ flex: 1 1 220px; min-width: 220px; }
    .page-arsip .ap-pagination-wrap{
      flex-direction: column;
      align-items: flex-start;
    }
    .page-arsip .ap-pagination{
      justify-content:flex-start;
    }

    /* ✅ tombol cetak biar gak nabrak (turun di bawah header) */
    .page-arsip .ap-header{
      flex-direction: column;
      align-items: flex-start;
    }
    .page-arsip .ap-header-right{
      width:100%;
      justify-content:flex-end;
    }
  }

  /* =========================
     ✅ PRINT STYLE
     - sidebar/filter/tombol/tools/modal disembunyikan saat print
  ========================= */
  @media print{
    /* sembunyikan elemen yang tidak perlu */
    .dash-sidebar,
    .ap-filter,
    .ap-tools,
    .ap-aksi,
    .ap-head .ap-check,
    .ap-row .ap-check,
    .ap-header-right,
    .dt-modal,
    .ap-pagination-wrap{
      display:none !important;
    }

    /* main jadi full */
    .dash-main{
      width: 100% !important;
    }
    .dash-wrap{
      display:block !important;
    }

    /* rapihin spacing untuk kertas */
    body{
      background:#fff !important;
    }
    .dash-table{
      box-shadow:none !important;
    }

    /* grid kolom saat print: hilangkan checkbox & aksi */
    .page-arsip .ap-head,
    .page-arsip .ap-row{
      grid-template-columns:
        86px
        1.25fr
        2.45fr
        1.55fr
        1.10fr
        1.25fr;
      padding-left: 0 !important;
      padding-right: 0 !important;
      column-gap: 14px;
    }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const selectAll = document.getElementById('apSelectAll');
  const unitEl    = document.getElementById('apUnitFilter');   // ✅ NEW
  const filterEl  = document.getElementById('apStatusFilter');
  const yearEl    = document.getElementById('apYearFilter');
  const searchEl  = document.getElementById('apSearchInput');

  const refreshBtn = document.getElementById('apRefreshBtn');
  const deleteBtn  = document.getElementById('apDeleteBtn');

  // ✅ NEW: Print button
  const printBtn = document.getElementById('apPrintBtn');

  const getRows = () => Array.from(document.querySelectorAll('.ap-row'));
  const getVisibleRows = () => getRows().filter(r => r.style.display !== 'none');

  function getCheckedIds(){
    return Array.from(document.querySelectorAll('.ap-row-check:checked'))
      .map(cb => cb.value)
      .filter(Boolean);
  }

  function setBtnDisabled(btn, disabled){
    if(!btn) return;
    btn.classList.toggle('is-disabled', !!disabled);
    btn.setAttribute('aria-disabled', disabled ? 'true' : 'false');
  }

  function syncSelectAllState(){
    if(!selectAll) return;

    const visible = getVisibleRows();
    const checks = visible.map(r => r.querySelector('.ap-row-check')).filter(Boolean);

    if (checks.length === 0) {
      selectAll.checked = false;
      selectAll.indeterminate = false;
      return;
    }

    const checkedCount = checks.filter(c => c.checked).length;
    selectAll.checked = checkedCount === checks.length;
    selectAll.indeterminate = checkedCount > 0 && checkedCount < checks.length;
  }

  function updateEditState(){
    const editLink = document.getElementById('apEditLink');
    if(!editLink) return;

    const ids = getCheckedIds();
    const active = (ids.length === 1);

    editLink.setAttribute('aria-disabled', active ? 'false' : 'true');
    editLink.classList.toggle('is-disabled', !active);
    editLink.href = active ? `/ppk/arsip/${ids[0]}/edit` : '#';
  }

  function updateDeleteState(){
    const ids = getCheckedIds();
    setBtnDisabled(deleteBtn, ids.length === 0);
  }

  function applyFilters(){
    const unitVal   = unitEl ? unitEl.value : 'Semua';
    const statusVal = filterEl ? filterEl.value : 'Semua';
    const yearVal   = yearEl ? yearEl.value : 'Semua';
    const q = (searchEl ? searchEl.value : '').trim().toLowerCase();

    getRows().forEach(row => {
      const u = row.getAttribute('data-unit');
      const s = row.getAttribute('data-status');
      const y = row.getAttribute('data-year');
      const hay = (row.getAttribute('data-search') || '').toLowerCase();

      const unitOk   = (unitVal === 'Semua')   || (u === unitVal);
      const statusOk = (statusVal === 'Semua') || (s === statusVal);
      const yearOk   = (yearVal === 'Semua')   || (y === yearVal);
      const searchOk = (q === '') || hay.includes(q);

      row.style.display = (unitOk && statusOk && yearOk && searchOk) ? '' : 'none';
    });

    syncSelectAllState();
    updateEditState();
    updateDeleteState();
  }

  if (unitEl)  unitEl.addEventListener('change', applyFilters); // ✅ NEW
  if (filterEl) filterEl.addEventListener('change', applyFilters);
  if (yearEl)   yearEl.addEventListener('change', applyFilters);
  if (searchEl) searchEl.addEventListener('input', applyFilters);

  if(refreshBtn){
    refreshBtn.addEventListener('click', function(){
      window.location.reload();
    });
  }

  const editLink = document.getElementById('apEditLink');
  if(editLink){
    editLink.addEventListener('click', function(e){
      e.preventDefault();

      const ids = getCheckedIds();
      if(ids.length !== 1){
        alert(ids.length === 0 ? 'Pilih 1 arsip untuk diedit.' : 'Hanya boleh pilih 1 arsip untuk edit.');
        return;
      }

      window.location.href = `/ppk/arsip/${ids[0]}/edit`;
    });
  }

  if(deleteBtn){
    deleteBtn.addEventListener('click', function(){
      const ids = getCheckedIds();
      if(ids.length === 0){
        alert('Pilih minimal 1 arsip (atau pilih semua) untuk dihapus.');
        return;
      }

      const ok = confirm(`Hapus ${ids.length} arsip terpilih?`);
      if(!ok) return;

      ids.forEach(id => {
        const cb = document.querySelector(`.ap-row-check[value="${id}"]`);
        const row = cb ? cb.closest('.ap-row') : null;
        if(row) row.remove();
      });

      if(selectAll){
        selectAll.checked = false;
        selectAll.indeterminate = false;
      }

      applyFilters();
      alert('Arsip terpilih berhasil dihapus (dummy frontend).');
    });
  }

  document.addEventListener('change', function(e){
    if(e.target && e.target.classList && e.target.classList.contains('ap-row-check')){
      syncSelectAllState();
      updateEditState();
      updateDeleteState();
    }

    if(e.target && e.target.id === 'apSelectAll'){
      const visible = getVisibleRows();
      visible.forEach(row => {
        const cb = row.querySelector('.ap-row-check');
        if (cb) cb.checked = selectAll.checked;
      });
      selectAll.indeterminate = false;
      updateEditState();
      updateDeleteState();
    }
  });

  applyFilters();
  syncSelectAllState();
  updateEditState();
  updateDeleteState();

  // ✅ NEW: Cetak Arsip
  if(printBtn){
    printBtn.addEventListener('click', function(){
      // pastikan modal ketutup kalau lagi kebuka
      const modal = document.getElementById('dtModal');
      if(modal && modal.classList.contains('is-open')){
        modal.classList.remove('is-open');
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        modal.setAttribute('aria-hidden', 'true');
      }
      window.print();
    });
  }

  // MODAL DETAIL (tetap)
  const modal = document.getElementById('dtModal');
  const closeBtn = document.getElementById('dtCloseBtn');

  const elTitle   = document.getElementById('dtTitle');
  const elUnit    = document.getElementById('dtUnit');
  const elTahun   = document.getElementById('dtTahun');
  const elIdRup   = document.getElementById('dtIdRup');
  const elStatus  = document.getElementById('dtStatus');
  const elRekanan = document.getElementById('dtRekanan');
  const elJenis   = document.getElementById('dtJenis');
  const elPagu    = document.getElementById('dtPagu');
  const elHps     = document.getElementById('dtHps');
  const elKontrak = document.getElementById('dtKontrak');

  function openModal(payload){
    if(!modal) return;

    if(elTitle)   elTitle.textContent   = payload.title || '-';
    if(elUnit)    elUnit.textContent    = payload.unit || '-';
    if(elTahun)   elTahun.textContent   = payload.tahun || '-';
    if(elIdRup)   elIdRup.textContent   = payload.idrup || '-';
    if(elStatus)  elStatus.textContent  = payload.status || '-';
    if(elRekanan) elRekanan.textContent = payload.rekanan || '-';
    if(elJenis)   elJenis.textContent   = payload.jenis || '-';
    if(elPagu)    elPagu.textContent    = payload.pagu || '-';
    if(elHps)     elHps.textContent     = payload.hps || '-';
    if(elKontrak) elKontrak.textContent = payload.kontrak || '-';

    modal.classList.add('is-open');
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
    modal.setAttribute('aria-hidden', 'false');
  }

  function closeModal(){
    if(!modal) return;
    modal.classList.remove('is-open');
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    modal.setAttribute('aria-hidden', 'true');
  }

  document.addEventListener('click', function(e){
    const link = e.target.closest('.js-open-detail');
    if(!link) return;

    e.preventDefault();
    openModal({
      title: link.dataset.title,
      unit: link.dataset.unit,
      tahun: link.dataset.tahun,
      idrup: link.dataset.idrup,
      status: link.dataset.status,
      rekanan: link.dataset.rekanan,
      jenis: link.dataset.jenis,
      pagu: link.dataset.pagu,
      hps: link.dataset.hps,
      kontrak: link.dataset.kontrak
    });
  });

  if(closeBtn) closeBtn.addEventListener('click', closeModal);

  if(modal){
    modal.addEventListener('click', function(e){
      const t = e.target;
      if(t && t.getAttribute && t.getAttribute('data-close') === 'true'){
        closeModal();
      }
    });
  }

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape' && modal && modal.classList.contains('is-open')){
      closeModal();
    }
  });
});
</script>

</body>
</html>
