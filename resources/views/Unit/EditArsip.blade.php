{{-- resources/views/unit/edit_arsip.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Arsip - SIAPABAJA</title>

  {{-- Font Nunito (HANYA 400 & 600 biar tidak ada bold) --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet">

  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  {{-- Pakai base dashboard yang sama --}}
  <link rel="stylesheet" href="{{ asset('css/Unit.css') }}">
</head>

<body class="dash-body">
@php
  // =========================
  // PATCH: BIAR TIDAK ERROR DI HALAMAN CREATE (tambah)
  // =========================
  $arsip = $arsip ?? null;

  $unitName = "Fakultas Teknik";

  $tahunOptions = [2022, 2023, 2024, 2025, 2026];
  $unitOptions  = ["Fakultas Teknik", "Fakultas Hukum", "Fakultas Ekonomi dan Bisnis"];
  $jenisPengadaanOptions = ["Tender", "E-Katalog", "Pengadaan Langsung", "Seleksi", "Penunjukan Langsung"];
  $statusPekerjaanOptions = ["Perencanaan", "Pemilihan", "Pelaksanaan", "Selesai"];

  // helper
  $baseName = function($path){
    if(!$path) return '';
    return basename($path);
  };
  $hasFile = function($path){
    return !empty($path);
  };

  // nilai status arsip
  $statusArsipVal = old('status_arsip', optional($arsip)->status_arsip);

  /**
   * ✅ FIX SESUAI MAUMU:
   * 37 SESI, masing-masing 1 dokumen (0/1)
   * (tanpa grup 0/10, 0/2, dst)
   * Key harus sama dengan controller & tambah_pengadaan.
   */
  $docSessions = [
    ['key'=>'dokumen_kak','label'=>'Kerangka Acuan Kerja atau KAK'],
    ['key'=>'dokumen_hps','label'=>'Harga Perkiraan Sendiri atau HPS'],
    ['key'=>'dokumen_spesifikasi_teknis','label'=>'Spesifikasi Teknis'],
    ['key'=>'dokumen_rancangan_kontrak','label'=>'Rancangan Kontrak'],
    ['key'=>'dokumen_lembar_data_kualifikasi','label'=>'Lembar Data Kualifikasi'],
    ['key'=>'dokumen_lembar_data_pemilihan','label'=>'Lembar Data Pemilihan'],
    ['key'=>'dokumen_daftar_kuantitas_harga','label'=>'Daftar Kuantitas dan Harga'],
    ['key'=>'dokumen_jadwal_lokasi_pekerjaan','label'=>'Jadwal dan Lokasi Pekerjaan'],
    ['key'=>'dokumen_gambar_rancangan_pekerjaan','label'=>'Gambar Rancangan Pekerjaan'],
    ['key'=>'dokumen_amdal','label'=>'Dokumen Analisis Mengenai Dampak Lingkungan (AMDAL)'],
    ['key'=>'dokumen_penawaran','label'=>'Dokumen Penawaran'],
    ['key'=>'surat_penawaran','label'=>'Surat Penawaran'],
    ['key'=>'dokumen_kemenkumham','label'=>'Sertifikat atau Lisensi Kemenkumham'],
    ['key'=>'ba_pemberian_penjelasan','label'=>'Berita Acara Pemberian Penjelasan'],
    ['key'=>'ba_pengumuman_negosiasi','label'=>'Berita Acara Pengumuman Negosiasi'],
    ['key'=>'ba_sanggah_banding','label'=>'Berita Acara Sanggah dan Sanggah Banding'],
    ['key'=>'ba_penetapan','label'=>'Berita Acara Penetapan'],
    ['key'=>'laporan_hasil_pemilihan','label'=>'Laporan Hasil Pemilihan Penyedia'],
    ['key'=>'dokumen_sppbj','label'=>'Surat Penunjukan Penyedia Barang Jasa atau SPPBJ'],
    ['key'=>'surat_perjanjian_kemitraan','label'=>'Surat Perjanjian Kemitraan'],
    ['key'=>'surat_perjanjian_swakelola','label'=>'Surat Perjanjian Swakelola'],
    ['key'=>'surat_penugasan_tim_swakelola','label'=>'Surat Penugasan Tim Swakelola'],
    ['key'=>'dokumen_mou','label'=>'Nota Kesepahaman atau MoU'],
    ['key'=>'dokumen_kontrak','label'=>'Dokumen Kontrak'],
    ['key'=>'ringkasan_kontrak','label'=>'Ringkasan Kontrak'],
    ['key'=>'jaminan_pelaksanaan','label'=>'Surat Jaminan Pelaksanaan'],
    ['key'=>'jaminan_uang_muka','label'=>'Surat Jaminan Uang Muka'],
    ['key'=>'jaminan_pemeliharaan','label'=>'Surat Jaminan Pemeliharaan'],
    ['key'=>'surat_tagihan','label'=>'Surat Tagihan'],
    ['key'=>'surat_pesanan_epurchasing','label'=>'Surat Pesanan Elektronik atau E-Purchasing'],
    ['key'=>'dokumen_spmk','label'=>'Surat Perintah Mulai Kerja atau SPMK'],
    ['key'=>'dokumen_sppd','label'=>'Surat Perintah Perjalanan Dinas atau SPPD'],
    ['key'=>'laporan_pelaksanaan_pekerjaan','label'=>'Laporan Pelaksanaan Pekerjaan'],
    ['key'=>'laporan_penyelesaian_pekerjaan','label'=>'Laporan Penyelesaian Pekerjaan'],
    ['key'=>'bap','label'=>'Berita Acara Pembayaran atau BAP'],
    ['key'=>'bast_sementara','label'=>'Berita Acara Serah Terima Sementara atau BAST Sementara'],
    ['key'=>'bast_akhir','label'=>'Berita Acara Serah Terima Final atau BAST Final'],
  ];
@endphp

<div class="dash-wrap">
  {{-- SIDEBAR (SAMA PERSIS DENGAN TAMBAH PENGADAAN) --}}
  <aside class="dash-sidebar">
    <div class="dash-brand">
      <div class="dash-logo">
        <img src="{{ asset('image/Logo_Unsoed.png') }}" alt="Logo Unsoed">
      </div>

      <div class="dash-text">
        {{-- ✅ semi-bold hanya judul web --}}
        <div class="dash-app">SIAPABAJA</div>
        <div class="dash-role">Admin (Unit)</div>
      </div>
    </div>

    <div class="dash-unitbox">
      <div class="dash-unit-label">Unit Kerja :</div>
      <div class="dash-unit-name">{{ $unitName }}</div>
    </div>

    <nav class="dash-nav">
      <a class="dash-link" href="{{ url('/unit/dashboard') }}">
        <span class="ic"><i class="bi bi-grid-fill"></i></span>
        Dashboard
      </a>

      <a class="dash-link active" href="{{ url('/unit/arsip') }}">
        <span class="ic"><i class="bi bi-archive"></i></span>
        Arsip PBJ
      </a>

      <a class="dash-link" href="{{ url('/unit/pengadaan/tambah') }}">
        <span class="ic"><i class="bi bi-plus-square"></i></span>
        Tambah Pengadaan
      </a>
    </nav>

    {{-- ===== TOMBOL KEMBALI & KELUAR (KONSISTEN DENGAN TAMBAH PENGADAAN) ===== --}}
    <div class="dash-side-actions">
      <a class="dash-side-btn" href="{{ url('/unit/arsip') }}">
        <i class="bi bi-arrow-left"></i>
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

    {{-- ✅ Tombol kembali di atas kiri page edit --}}
    <a href="{{ url('/unit/arsip') }}" class="tp-btn tp-btn-ghost tp-topback">
      <i class="bi bi-arrow-left"></i>
      Kembali
    </a>

    {{-- HEADER --}}
    <header class="dash-header">
      {{-- ✅ semi-bold hanya judul page --}}
      <h1>Edit Arsip Pengadaan Barang dan Jasa</h1>
      <p>Perbarui arsip PBJ</p>
    </header>

    {{-- FORM --}}
    <form action="{{ $arsip ? route('unit.arsip.update', $arsip->id) : route('unit.pengadaan.store') }}"
          method="POST" enctype="multipart/form-data" class="tp-form">
      @csrf
      @if($arsip)
        @method('PUT')
      @endif

      {{-- A. Informasi Umum --}}
      <section class="dash-table tp-cardbox" style="border-radius:14px; overflow:visible; margin-bottom:14px;">
        <div style="padding:18px 18px 16px;">
          <div class="tp-section">
            <div class="tp-section-title">
              <span class="tp-badge">A.</span>
              <span>Informasi Umum</span>
            </div>
            <div class="tp-divider"></div>

            <div class="tp-grid">
              <div class="tp-field">
                <label class="tp-label">Tahun</label>
                <div class="tp-control">
                  <select name="tahun" class="tp-select">
                    <option value="">Tahun</option>
                    @foreach($tahunOptions as $t)
                      <option value="{{ $t }}" @selected(old('tahun', optional($arsip)->tahun) == $t)>{{ $t }}</option>
                    @endforeach
                  </select>
                  <i class="bi bi-chevron-down tp-icon"></i>
                </div>
              </div>

              <div class="tp-field">
                <label class="tp-label">Unit Kerja</label>
                <div class="tp-control">
                  <select name="unit_kerja" class="tp-select">
                    <option value="">Fakultas</option>
                    @foreach($unitOptions as $u)
                      <option value="{{ $u }}" @selected(old('unit_kerja', optional($arsip)->unit_kerja) == $u)>{{ $u }}</option>
                    @endforeach
                  </select>
                  <i class="bi bi-chevron-down tp-icon"></i>
                </div>
              </div>

              <div class="tp-field tp-full">
                <label class="tp-label">Nama Pekerjaan</label>
                <input type="text" name="nama_pekerjaan" class="tp-input"
                       value="{{ old('nama_pekerjaan', optional($arsip)->nama_pekerjaan) }}"
                       placeholder="Nama Pekerjaan" />
              </div>

              <div class="tp-field">
                <label class="tp-label">ID RUP</label>
                <input type="text" name="id_rup" class="tp-input"
                       value="{{ old('id_rup', optional($arsip)->id_rup) }}"
                       placeholder="RUP-xxxx-xxxx-xxx-xx" />
              </div>

              <div class="tp-field">
                <label class="tp-label">Jenis Pengadaan</label>
                <div class="tp-control">
                  <select name="jenis_pengadaan" class="tp-select">
                    <option value="">Pilih Jenis Pengadaan</option>
                    @foreach($jenisPengadaanOptions as $jp)
                      <option value="{{ $jp }}" @selected(old('jenis_pengadaan', optional($arsip)->jenis_pengadaan) == $jp)>{{ $jp }}</option>
                    @endforeach
                  </select>
                  <i class="bi bi-chevron-down tp-icon"></i>
                </div>
              </div>

              <div class="tp-field tp-full">
                <label class="tp-label">Status Pekerjaan</label>
                <div class="tp-control">
                  <select name="status_pekerjaan" class="tp-select">
                    <option value="">Pilih Status Pekerjaan</option>
                    @foreach($statusPekerjaanOptions as $sp)
                      <option value="{{ $sp }}" @selected(old('status_pekerjaan', optional($arsip)->status_pekerjaan) == $sp)>{{ $sp }}</option>
                    @endforeach
                  </select>
                  <i class="bi bi-chevron-down tp-icon"></i>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      {{-- B. Status Akses Arsip --}}
      <section class="dash-table tp-cardbox" style="border-radius:14px; overflow:visible; margin-bottom:14px;">
        <div style="padding:18px 18px 16px;">
          <div class="tp-section">
            <div class="tp-section-title">
              <span class="tp-badge">B.</span>
              <span>Status Akses Arsip</span>
            </div>
            <div class="tp-divider"></div>

            <div class="tp-grid" style="grid-template-columns: 1fr;">
              <div class="tp-field">
                <label class="tp-label">Status Arsip</label>

                <div class="tp-radio-wrap">
                  <label class="tp-radio-card {{ $statusArsipVal=='Publik' ? 'active' : '' }}">
                    <input type="radio" name="status_arsip" value="Publik" {{ $statusArsipVal=='Publik' ? 'checked' : '' }}>
                    <span class="tp-radio-dot"></span>
                    <span class="tp-radio-text">Publik</span>
                  </label>

                  <label class="tp-radio-card {{ $statusArsipVal=='Privat' ? 'active' : '' }}">
                    <input type="radio" name="status_arsip" value="Privat" {{ $statusArsipVal=='Privat' ? 'checked' : '' }}>
                    <span class="tp-radio-dot"></span>
                    <span class="tp-radio-text">Privat</span>
                  </label>
                </div>

              </div>
            </div>

          </div>
        </div>
      </section>

      {{-- C. Informasi Anggaran --}}
      <section class="dash-table tp-cardbox" style="border-radius:14px; overflow:visible; margin-bottom:14px;">
        <div style="padding:18px 18px 16px;">
          <div class="tp-section">
            <div class="tp-section-title">
              <span class="tp-badge">C.</span>
              <span>Informasi Anggaran</span>
            </div>
            <div class="tp-divider"></div>

            <div class="tp-grid">
              <div class="tp-field">
                <label class="tp-label">Pagu Anggaran (Rp)</label>
                <input type="text" name="pagu_anggaran" class="tp-input"
                       value="{{ old('pagu_anggaran', optional($arsip)->pagu_anggaran) }}"
                       placeholder="Rp" />
              </div>

              <div class="tp-field">
                <label class="tp-label">HPS (Rp)</label>
                <input type="text" name="hps" class="tp-input"
                       value="{{ old('hps', optional($arsip)->hps) }}"
                       placeholder="Rp" />
              </div>

              <div class="tp-field">
                <label class="tp-label">Nilai Kontrak (Rp)</label>
                <input type="text" name="nilai_kontrak" class="tp-input"
                       value="{{ old('nilai_kontrak', optional($arsip)->nilai_kontrak) }}"
                       placeholder="Rp" />
              </div>

              <div class="tp-field">
                <label class="tp-label">Nama Rekanan</label>
                <input type="text" name="nama_rekanan" class="tp-input"
                       value="{{ old('nama_rekanan', optional($arsip)->nama_rekanan) }}"
                       placeholder="Nama Rekanan" />
              </div>
            </div>

          </div>
        </div>
      </section>

      {{-- D. Dokumen Pengadaan (✅ 37 SESI, masing-masing (0/1)) --}}
      <section class="dash-table tp-cardbox" style="border-radius:14px; overflow:visible; margin-bottom:14px;">
        <div style="padding:18px 18px 16px;">
          <div class="tp-section">
            <div class="tp-section-title">
              <span class="tp-badge">D.</span>
              <span>Dokumen Pengadaan</span>
            </div>
            <div class="tp-divider"></div>

            <div class="tp-help" style="margin:0 6px 14px;">
              Upload dokumen pengadaan sesuai dengan tahapan proses.
            </div>

            <div class="tp-acc">
              @foreach($docSessions as $idx => $s)
                @php
                  $key  = $s['key'];
                  $path = $arsip ? ($arsip->$key ?? null) : null;
                  $has  = $hasFile($path);
                  $filled = $has ? 1 : 0;
                @endphp

                <div class="tp-acc-item">
                  <button type="button" class="tp-acc-head" aria-expanded="false">
                    <span class="tp-acc-left">
                      <i class="bi bi-file-earmark-text"></i>
                      {{ $s['label'] }} ( <span class="js-item-filled">{{ $filled }}</span> / 1 )
                    </span>
                    <i class="bi bi-chevron-down tp-acc-ic"></i>
                  </button>

                  <div class="tp-acc-body">
                    <div class="tp-upload-row" data-has="{{ $has ? '1' : '0' }}">
                      <input type="hidden" name="{{ $key }}_delete" value="0" class="tp-del-flag">

                      {{-- SUDAH ADA FILE --}}
                      <div class="tp-file-exist" style="{{ $has ? '' : 'display:none;' }}">
                        <div class="tp-filebox">
                          <span class="tp-filebox-name">{{ $baseName($path) }}</span>
                          <button type="button" class="tp-filebox-x tp-existing-x" title="Hapus" aria-label="Hapus">×</button>
                        </div>
                      </div>

                      {{-- DROPZONE --}}
                      <label class="tp-dropzone" style="{{ $has ? 'display:none;' : '' }}">
                        <input type="file" name="{{ $key }}" class="tp-file-hidden" />
                        <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                        <div class="tp-drop-title">Upload Dokumen Anda</div>
                        <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                        <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                        <div class="tp-drop-btn">Pilih File</div>
                      </label>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

          </div>
        </div>
      </section>

      {{-- ACTIONS --}}
      <div class="tp-actions">
        <button type="reset" class="tp-btn tp-btn-danger-ghost">
          <i class="bi bi-x-circle"></i>
          Hapus Perubahan
        </button>

        <button type="submit" class="tp-btn tp-btn-primary">
          <i class="bi bi-check2-circle"></i>
          Simpan Perubahan
        </button>
      </div>
    </form>
  </main>
</div>

<style>
  /* =============================
     EDIT ARSIP (NO BOLD)
     Aturan:
     - Judul web (dash-app) & judul page (dash-header h1) = semi-bold (600)
     - Selain itu = normal (400)
  ============================= */

  .dash-body{
    font-size: 18px;
    line-height: 1.6;
    font-weight: 400;
  }

  /* ✅ semi-bold hanya judul web + judul page */
  .dash-app{ font-weight: 600 !important; }
  .dash-header h1{ font-weight: 600 !important; }

  /* ✅ semua teks lain normal */
  .dash-role,
  .dash-unit-label,
  .dash-unit-name,
  .dash-link,
  .dash-side-btn,
  .dash-header p,
  .tp-section-title,
  .tp-badge,
  .tp-label,
  .tp-input,
  .tp-select,
  .tp-actions .tp-btn,
  .tp-help,
  .tp-radio-card,
  .tp-radio-text,
  .tp-acc-head,
  .tp-upload-label,
  .tp-filebox-name,
  .tp-drop-title,
  .tp-drop-sub,
  .tp-drop-meta,
  .tp-drop-btn{
    font-weight: 400 !important;
  }

  /* ===== tombol sidebar nempel bawah (layout) ===== */
  .dash-sidebar{ display:flex; flex-direction:column; }

  .dash-side-actions{
    margin-top:auto;
    padding-top: 14px;
    border-top: 1px solid rgba(255,255,255,.12);
    display:grid;
    gap: 10px;
  }

  /* ====== tombol kembali atas kiri ====== */
  .tp-topback{
    width: fit-content;
    margin: 6px 0 10px;
  }

  /* ====== tombol ====== */
  .tp-btn{
    display:inline-flex;
    align-items:center;
    gap:10px;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 16px;
    text-decoration:none;
    border: 1px solid #e2e8f0;
    cursor:pointer;
    background:#fff;
  }
  .tp-btn i{ font-size: 18px; }
  .tp-btn-ghost{ background:#fff; color: var(--navy2); }
  .tp-btn-primary{ background: var(--yellow); border-color: transparent; color: #0f172a; }
  .tp-btn-danger-ghost{
    border: 1px solid #f1b4b4;
    color:#9b1c1c;
    background:#fff;
  }

  /* ====== SECTION ====== */
  .tp-section-title{
    display:flex;
    align-items:center;
    gap:10px;
    color: var(--navy2);
    font-size: 18px;
    padding: 6px 6px 10px;
  }
  .tp-badge{
    width: 28px;
    height: 28px;
    border-radius: 10px;
    display:grid;
    place-items:center;
    background: #eef6f9;
    color: var(--navy2);
    font-size: 15px;
  }
  .tp-divider{
    height:1px;
    background: #eef3f6;
    margin: 0 6px 14px;
  }

  /* ====== FORM CONTROL ====== */
  .tp-form{ font-family: inherit; }

  .tp-label{
    display:block;
    font-size: 15px;
    color: var(--muted);
    margin-bottom: 8px;
  }

  .tp-input, .tp-select, .tp-textarea, .tp-file{
    width:100%;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 12px;
    font-family: inherit;
    font-size: 16px;
    outline: none;
    background: #fff;
  }

  .tp-control{ position:relative; }
  .tp-control .tp-select{ appearance:none; padding-right: 42px; }
  .tp-icon{
    position:absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    opacity: .55;
    pointer-events:none;
    font-size: 18px;
  }

  /* ===== ACTIONS ===== */
  .tp-actions{
    display:flex;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 6px 2px;
    margin-top: 6px;
  }

  /* RADIO CARDS */
  .tp-radio-wrap{ display:grid; gap: 12px; }
  .tp-radio-card{
    display:flex;
    align-items:center;
    gap: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 14px;
    background:#fff;
    cursor:pointer;
    user-select:none;
    color: var(--navy2);
    font-size: 16px;
  }
  .tp-radio-card input{ display:none; }
  .tp-radio-dot{
    width: 18px;
    height: 18px;
    border-radius: 999px;
    border: 2px solid var(--navy2);
    display:inline-block;
    position:relative;
  }
  .tp-radio-card.active{
    background: #dff1ff;
    border-color: #b6dcff;
  }
  .tp-radio-card.active .tp-radio-dot::after{
    content:"";
    position:absolute;
    inset: 4px;
    border-radius:999px;
    background: var(--navy2);
  }

  /* ACCORDION */
  .tp-acc-item{
    border: 1px solid #e6eef2;
    border-radius: 14px;
    background:#fff;
    box-shadow: 0 10px 20px rgba(2,8,23,.04);
    overflow:hidden;
  }

  .tp-acc-head{
    width:100%;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap: 12px;
    padding: 12px 14px;
    border: 0;
    background: #dff1ff;
    cursor:pointer;
    font-family: inherit;
    color: var(--navy2);
    font-size: 16px;
  }

  .tp-acc-left{ display:flex; align-items:center; gap: 10px; }
  .tp-acc-left i{ font-size: 18px; }
  .tp-acc-ic{ opacity:.85; transition: transform .15s ease; font-size: 18px; }

  .tp-acc-body{
    border-top: 1px solid #eef3f6;
    background:#fff;
    padding: 14px;
  }

  .tp-upload-row{ margin-bottom: 0; }

  /* FILE EXISTING */
  .tp-filebox{
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:space-between;
    box-shadow: 0 10px 20px rgba(2,8,23,.06);
  }
  .tp-filebox-name{
    color: var(--navy2);
    font-size: 15px;
    word-break: break-word;
  }
  .tp-filebox-x{
    border:0;
    background:transparent;
    font-size: 20px;
    font-weight: 400 !important;
    line-height: 1;
    cursor:pointer;
    opacity: .75;
    color: #0f172a;
  }
  .tp-filebox-x:hover{ opacity: 1; }

  /* DROPZONE */
  .tp-dropzone{
    display:grid;
    place-items:center;
    text-align:center;
    gap: 8px;
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    padding: 22px 16px;
    cursor:pointer;
    user-select:none;
    background:#fff;
  }
  .tp-file-hidden{ display:none; }
  .tp-drop-ic{
    width: 48px;
    height: 48px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    display:grid;
    place-items:center;
    color: var(--navy2);
    font-size: 24px;
    background:#fff;
  }
  .tp-drop-title{ color: var(--navy2); font-size: 16px; }
  .tp-drop-sub{ color: var(--muted); font-size: 14px; }
  .tp-drop-meta{ color:#94a3b8; font-size: 13px; }
  .tp-drop-btn{
    margin-top: 8px;
    background: var(--navy2);
    color:#fff;
    font-size: 16px;
    padding: 10px 18px;
    border-radius: 10px;
  }

  @media(max-width:1100px){
    .tp-actions{ flex-direction: column; }
    .tp-btn{ justify-content:center; }
  }

  /* =========================================================
     PATCH: samakan jarak kolom/card dgn Tambah Pengadaan
  ========================================================= */
  .tp-cardbox{
    background:#fff !important;
    border-radius:14px !important;
    box-shadow: 0 10px 20px rgba(2, 8, 23, .06) !important;
    border: 1px solid #eef3f6 !important;
    margin-bottom: 14px !important;
    overflow: hidden !important;
  }
  .tp-cardbox > div{
    padding: 18px 18px 18px !important;
  }
  .tp-grid{
    padding: 0 !important;
    gap: 14px 18px !important;
  }
  .tp-section-title{
    padding: 0 0 10px !important;
  }
  .tp-divider{
    margin: 0 0 14px !important;
  }
  .tp-acc{
    padding: 0 !important;
    display: grid !important;
    gap: 14px !important;
  }
  .tp-help{
    margin: 0 0 12px !important;
    font-size: 15px;
    color: #64748b;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function(){

    // ===== radio cards active (konsisten dgn Tambah Pengadaan) =====
    document.addEventListener('click', function(e){
      const card = e.target.closest('.tp-radio-card');
      if(!card) return;

      const wrap = card.closest('.tp-radio-wrap');
      if(!wrap) return;

      wrap.querySelectorAll('.tp-radio-card').forEach(c => c.classList.remove('active'));
      card.classList.add('active');

      const input = card.querySelector('input[type="radio"]');
      if(input) input.checked = true;
    });

    // set active awal sesuai radio checked
    document.querySelectorAll('.tp-radio-wrap').forEach(wrap => {
      wrap.querySelectorAll('.tp-radio-card').forEach(c => c.classList.remove('active'));
      const checked = wrap.querySelector('input[type="radio"]:checked');
      if(checked) checked.closest('.tp-radio-card').classList.add('active');
    });

    // ===== accordion default: ketutup =====
    document.querySelectorAll('.tp-acc-item').forEach(item => {
      const head = item.querySelector('.tp-acc-head');
      const body = item.querySelector('.tp-acc-body');
      const ic = item.querySelector('.tp-acc-ic');
      if(!head || !body) return;

      body.style.display = 'none';
      if(ic) ic.style.transform = 'rotate(-90deg)';
      head.setAttribute('aria-expanded', 'false');

      head.addEventListener('click', () => {
        const isOpen = body.style.display !== 'none';
        body.style.display = isOpen ? 'none' : 'block';
        if(ic) ic.style.transform = isOpen ? 'rotate(-90deg)' : 'rotate(0deg)';
        head.setAttribute('aria-expanded', String(!isOpen));
      });
    });

    // tombol "Pilih File" click = trigger input file
    document.querySelectorAll('.tp-dropzone').forEach(zone => {
      const input = zone.querySelector('input[type="file"]');
      const btn = zone.querySelector('.tp-drop-btn');
      if(input && btn){
        btn.addEventListener('click', (ev) => {
          ev.preventDefault();
          input.click();
        });
      }
    });

    // ===== helper: update badge (x/1) per sesi =====
    function updateItemCount(accItem){
      if(!accItem) return;

      const row = accItem.querySelector('.tp-upload-row');
      const filledEl = accItem.querySelector('.js-item-filled');
      if(!row || !filledEl) return;

      const del = row.querySelector('.tp-del-flag');
      const has = row.getAttribute('data-has') === '1';
      const fileInput = row.querySelector('input[type="file"]');
      const hasNew = !!(fileInput && fileInput.files && fileInput.files.length > 0);
      const willDelete = del && del.value === '1';

      const filled = ((has && !willDelete) || hasNew) ? 1 : 0;
      filledEl.textContent = String(filled);
    }

    document.querySelectorAll('.tp-acc-item').forEach(item => updateItemCount(item));

    // ===== klik × => set delete flag, hide filebox, show dropzone =====
    document.addEventListener('click', function(ev){
      const x = ev.target.closest('.tp-existing-x');
      if(!x) return;

      const accItem = x.closest('.tp-acc-item');
      const row = x.closest('.tp-upload-row');
      if(!row) return;

      const delFlag = row.querySelector('.tp-del-flag');
      const existWrap = row.querySelector('.tp-file-exist');
      const zone = row.querySelector('.tp-dropzone');
      const input = row.querySelector('input[type="file"]');

      if(delFlag) delFlag.value = '1';
      if(existWrap) existWrap.style.display = 'none';
      if(zone) zone.style.display = 'grid';
      if(input) input.value = '';

      row.setAttribute('data-has', '0');
      updateItemCount(accItem);
    });

    // ===== saat user pilih file baru: anggap terisi + update count =====
    document.querySelectorAll('.tp-upload-row input[type="file"]').forEach(inp => {
      inp.addEventListener('change', function(){
        const accItem = inp.closest('.tp-acc-item');
        const row = inp.closest('.tp-upload-row');
        if(!row) return;

        const delFlag = row.querySelector('.tp-del-flag');
        if(delFlag) delFlag.value = '0';

        const existWrap = row.querySelector('.tp-file-exist');
        if(existWrap) existWrap.style.display = 'none';

        row.setAttribute('data-has', '0');
        updateItemCount(accItem);
      });
    });

  });
</script>

</body>
</html>
