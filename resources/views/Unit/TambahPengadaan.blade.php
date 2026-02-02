{{-- resources/views/unit/tambah_pengadaan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah Pengadaan - SIAPABAJA</title>

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
  // dummy frontend (nanti backend tinggal ganti)
  $unitName = "Fakultas Teknik";

  // opsi dummy dropdown
  $tahunOptions = [2022, 2023, 2024, 2025, 2026];
  $unitOptions  = ["Fakultas Teknik", "Fakultas Hukum", "Fakultas Ekonomi dan Bisnis"];
  $jenisPengadaanOptions = ["Tender", "E-Katalog", "Pengadaan Langsung", "Seleksi", "Penunjukan Langsung"];
  $statusPekerjaanOptions = ["Perencanaan", "Pemilihan", "Pelaksanaan", "Selesai"];
@endphp

<div class="dash-wrap">
  {{-- SIDEBAR (SAMA PERSIS DENGAN DASHBOARD UNIT) --}}
  <aside class="dash-sidebar">
    <div class="dash-brand">
      <div class="dash-logo">
        <img src="{{ asset('image/Logo_Unsoed.png') }}" alt="Logo Unsoed">
      </div>

      <div class="dash-text">
        {{-- ✅ semi-bold hanya judul web --}}
        <div class="dash-app">SIAPABAJA</div>
        <div class="dash-role">PIC (Unit)</div>
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

      <a class="dash-link" href="{{ url('/unit/arsip') }}">
        <span class="ic"><i class="bi bi-archive"></i></span>
        Arsip PBJ
      </a>

      <a class="dash-link active" href="{{ url('/unit/pengadaan/tambah') }}">
        <span class="ic"><i class="bi bi-plus-square"></i></span>
        Tambah Pengadaan
      </a>
    </nav>

    {{-- ===== TOMBOL KEMBALI & KELUAR ===== --}}
    <div class="dash-side-actions">
      <a class="dash-side-btn" href="{{ url('/unit/dashboard') }}">
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
    {{-- HEADER --}}
    <header class="dash-header">
      {{-- ✅ semi-bold hanya judul page --}}
      <h1>Tambah Arsip Pengadaan Barang dan Jasa</h1>
      <p>Lengkapi formulir dibawah ini untuk menambahkan arsip PBJ</p>
    </header>

    {{-- FORM: SETIAP SECTION PUNYA CARD SENDIRI --}}
    <form action="{{ url('/unit/pengadaan/store') }}" method="POST" class="tp-form">
      @csrf

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
                      <option value="{{ $t }}">{{ $t }}</option>
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
                      <option value="{{ $u }}">{{ $u }}</option>
                    @endforeach
                  </select>
                  <i class="bi bi-chevron-down tp-icon"></i>
                </div>
              </div>

              <div class="tp-field tp-full">
                <label class="tp-label">Nama Pekerjaan</label>
                <input type="text" name="nama_pekerjaan" class="tp-input" placeholder="Nama Pekerjaan" />
              </div>

              <div class="tp-field">
                <label class="tp-label">ID RUP</label>
                <input type="text" name="id_rup" class="tp-input" placeholder="RUP-xxxx-xxxx-xxx-xx" />
              </div>

              <div class="tp-field">
                <label class="tp-label">Jenis Pengadaan</label>
                <div class="tp-control">
                  <select name="jenis_pengadaan" class="tp-select">
                    <option value="">Pilih Jenis Pengadaan</option>
                    @foreach($jenisPengadaanOptions as $jp)
                      <option value="{{ $jp }}">{{ $jp }}</option>
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
                      <option value="{{ $sp }}">{{ $sp }}</option>
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
                  <label class="tp-radio-card active">
                    <input type="radio" name="status_arsip" value="Publik" checked>
                    <span class="tp-radio-dot"></span>
                    <span class="tp-radio-text">Publik</span>
                  </label>

                  <label class="tp-radio-card">
                    <input type="radio" name="status_arsip" value="Privat">
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
                <input type="text" name="pagu_anggaran" class="tp-input" placeholder="Rp" />
              </div>

              <div class="tp-field">
                <label class="tp-label">HPS (Rp)</label>
                <input type="text" name="hps" class="tp-input" placeholder="Rp" />
              </div>

              <div class="tp-field">
                <label class="tp-label">Nilai Kontrak (Rp)</label>
                <input type="text" name="nilai_kontrak" class="tp-input" placeholder="Rp" />
              </div>

              <div class="tp-field">
                <label class="tp-label">Nama Rekanan</label>
                <input type="text" name="nama_rekanan" class="tp-input" placeholder="Nama Rekanan" />
              </div>
            </div>
          </div>
        </div>
      </section>

      {{-- D. Dokumen Pengadaan --}}
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
              {{-- =========================
                   TOTAL 37 SESI (0/1)
                   1 sesi = 1 dokumen upload
                   (desain tetap sama)
              ========================== --}}

              {{-- 1 --}}
              <div class="tp-acc-item" data-acc="open">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Kerangka Acuan Kerja atau KAK (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_kak" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 2 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Harga Perkiraan Sendiri atau HPS (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_hps" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 3 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Spesifikasi Teknis (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_spesifikasi_teknis" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 4 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Rancangan Kontrak (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_rancangan_kontrak" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 5 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Lembar Data Kualifikasi (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_lembar_data_kualifikasi" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 6 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Lembar Data Pemilihan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_lembar_data_pemilihan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 7 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Daftar Kuantitas dan Harga (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_daftar_kuantitas_harga" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 8 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Jadwal dan Lokasi Pekerjaan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_jadwal_lokasi_pekerjaan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 9 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Gambar Rancangan Pekerjaan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_gambar_rancangan_pekerjaan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 10 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Dokumen Analisis Mengenai Dampak Lingkungan atau AMDAL (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_amdal" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 11 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Dokumen Penawaran (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_penawaran" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 12 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Penawaran (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="surat_penawaran" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 13 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Sertifikat atau Lisensi Kemenkumham (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_kemenkumham" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 14 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Berita Acara Pemberian Penjelasan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="ba_pemberian_penjelasan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 15 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Berita Acara Pengumuman Negosiasi (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="ba_pengumuman_negosiasi" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 16 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Berita Acara Sanggah dan Sanggah Banding (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="ba_sanggah_banding" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 17 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Berita Acara Penetapan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="ba_penetapan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 18 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Laporan Hasil Pemilihan Penyedia (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="laporan_hasil_pemilihan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 19 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Penunjukan Penyedia Barang Jasa atau SPPBJ (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_sppbj" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 20 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Perjanjian Kemitraan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="surat_perjanjian_kemitraan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 21 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Perjanjian Swakelola (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="surat_perjanjian_swakelola" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 22 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Penugasan Tim Swakelola (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="surat_penugasan_tim_swakelola" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 23 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Nota Kesepahaman atau MoU (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_mou" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 24 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Dokumen Kontrak (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_kontrak" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 25 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Ringkasan Kontrak (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="ringkasan_kontrak" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 26 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Jaminan Pelaksanaan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="jaminan_pelaksanaan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 27 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Jaminan Uang Muka (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="jaminan_uang_muka" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 28 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Jaminan Pemeliharaan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="jaminan_pemeliharaan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 29 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Tagihan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="surat_tagihan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 30 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Pesanan Elektronik atau E-Purchasing (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="surat_pesanan_epurchasing" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 31 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Perintah Mulai Kerja atau SPMK (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_spmk" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 32 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Surat Perintah Perjalanan Dinas atau SPPD (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="dokumen_sppd" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 33 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Laporan Pelaksanaan Pekerjaan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="laporan_pelaksanaan_pekerjaan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 34 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Laporan Penyelesaian Pekerjaan (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="laporan_penyelesaian_pekerjaan" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 35 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Berita Acara Pembayaran atau BAP (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="bap" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 36 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Berita Acara Serah Terima Sementara atau BAST Sementara (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="bast_sementara" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- 37 --}}
              <div class="tp-acc-item">
                <button type="button" class="tp-acc-head" aria-expanded="true">
                  <span class="tp-acc-left">
                    <i class="bi bi-file-earmark-text"></i>
                    Berita Acara Serah Terima Final atau BAST Final (0/1)
                  </span>
                  <i class="bi bi-chevron-down tp-acc-ic"></i>
                </button>
                <div class="tp-acc-body">
                  <div class="tp-upload-row" style="margin-bottom:0;">
                    <label class="tp-dropzone">
                      <input type="file" name="bast_akhir" class="tp-file-hidden" />
                      <div class="tp-drop-ic"><i class="bi bi-upload"></i></div>
                      <div class="tp-drop-title">Upload Dokumen Anda</div>
                      <div class="tp-drop-sub">Klik untuk upload atau drag & drop</div>
                      <div class="tp-drop-meta">Format : PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</div>
                      <div class="tp-drop-btn">Pilih File</div>
                    </label>
                  </div>
                </div>
              </div>

            </div>{{-- /tp-acc --}}
          </div>
        </div>
      </section>

      {{-- ACTIONS --}}
      <div class="tp-actions">
        <a href="{{ url('/unit/dashboard') }}" class="tp-btn tp-btn-ghost">
          <i class="bi bi-arrow-left"></i>
          Kembali
        </a>

        <button type="submit" class="tp-btn tp-btn-primary">
          <i class="bi bi-check2-circle"></i>
          Simpan Arsip
        </button>
      </div>
    </form>
  </main>
</div>

<style>
  /* =============================
     TAMBAH PENGADAAN (NO BOLD)
     Aturan:
     - Judul web (dash-app) & judul page (h1) = semi-bold (600)
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

  /* ====== FORM LAYOUT ====== */
  .tp-form{ font-family: inherit; }

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
  .tp-textarea{ resize: vertical; }

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

  .tp-actions{
    display:flex;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 6px 2px;
    margin-top: 6px;
  }
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

  .tp-upload-row{ margin-bottom: 16px; }
  .tp-upload-label{
    color: var(--navy2);
    font-size: 16px;
    margin: 2px 0 10px;
  }

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
     PATCH: samakan jarak kolom/card Tambah Pengadaan
     dengan Edit Arsip (tp-card)
     (hanya spacing/layout, tidak ubah konten)
  ========================================================= */

  /* shell card tp-cardbox = tp-card */
  .tp-cardbox{
    background:#fff !important;
    border-radius:14px !important;
    box-shadow: 0 10px 20px rgba(2, 8, 23, .06) !important;
    border: 1px solid #eef3f6 !important;
    margin-bottom: 14px !important;
    overflow: hidden !important;
  }

  /* samakan padding dalam card (override div inline padding) */
  .tp-cardbox > div{
    padding: 18px 18px 18px !important;
  }

  /* hilangkan extra side padding yang bikin jarak kolom beda */
  .tp-grid{
    padding: 0 !important;          /* sebelumnya: 0 6px 6px */
    gap: 14px 18px !important;
  }

  .tp-section-title{
    padding: 0 0 10px !important;   /* sebelumnya: 6px 6px 10px */
  }

  .tp-divider{
    margin: 0 0 14px !important;    /* sebelumnya: 0 6px 14px */
  }

  .tp-acc{
    padding: 0 !important;          /* sebelumnya: 0 6px 6px */
    display: grid !important;
    gap: 14px !important;
  }

  .tp-help{
    margin: 0 0 12px !important;    /* override style inline margin:0 6px 14px */
    font-size: 15px;
    color: #64748b;
  }
</style>

<script>
  // toggle active state untuk radio cards
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

  // set active awal sesuai radio yang checked + accordion per sesi (independen)
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.tp-radio-wrap').forEach(wrap => {
      wrap.querySelectorAll('.tp-radio-card').forEach(c => c.classList.remove('active'));
      const checked = wrap.querySelector('input[type="radio"]:checked');
      if(checked) checked.closest('.tp-radio-card').classList.add('active');
    });

    // accordion: default CLOSED saat halaman dibuka
    document.querySelectorAll('.tp-acc-item').forEach(item => {
      const head = item.querySelector('.tp-acc-head');
      const body = item.querySelector('.tp-acc-body');
      const ic = item.querySelector('.tp-acc-ic');
      if(!head || !body) return;

      // ✅ default closed (ketutup)
      body.style.display = 'none';
      if(ic) ic.style.transform = 'rotate(-90deg)';
      head.setAttribute('aria-expanded', 'false');

      head.addEventListener('click', () => {
        const isOpen = body.style.display !== 'none';
        body.style.display = isOpen ? 'none' : 'block';
        head.classList.toggle('is-open', !isOpen);
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
  });
</script>

</body>
</html>
