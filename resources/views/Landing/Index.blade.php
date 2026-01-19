@extends('layouts.app')

@section('title', 'SIAPABAJA - Landing')

@section('content')
  {{-- HERO --}}
  <section id="beranda" class="hero">
    <div class="container">
      <div class="hero-grid">
        <div>
          <h1>
            Sistem Informasi Arsip<br/>
            Pengadaan Barang dan Jasa
            <span class="u">Universitas Jenderal Soedirman</span>
          </h1>

          <p>
            SIAPABAJA merupakan sistem informasi berbasis web yang digunakan untuk mengelola dan mengarsipkan dokumen
            pengadaan barang dan jasa di lingkungan Universitas Jenderal Soedirman.
          </p>

          <a class="btn btn-primary" href="#arsip">Lihat Arsip</a>
        </div>

        <div class="hero-illustration">
          <img src="{{ asset('images/hero-illustration.png') }}" alt="Ilustrasi pengguna"
               onerror="this.style.display='none'">
        </div>
      </div>
    </div>
  </section>

  {{-- ARSIP LIST (dummy frontend) --}}
  <section id="arsip">
    <div class="container">
      <div class="section-title">
        <h2>Arsip Pengadaan Barang dan Jasa</h2>
        <p>Daftar dokumen pengadaan barang dan jasa yang dapat diakses oleh masyarakat.</p>
      </div>

      <div class="cards">
        @for($i=0; $i<5; $i++)
          <article class="card">
            <div class="card-top">
              <div>
                <div class="card-date">15 Januari 2026</div>
                <div class="card-title">Penyediaan Jasa Keamanan (SATPAM) Universitas Jenderal Soedirman</div>
              </div>

              <a href="/detail-arsip" class="btn-detail"> ⓘ Lihat Detail</a>
              
            </div>

            <div class="card-meta">
              <div class="kv"><div class="k">Unit Kerja</div><div class="v">: Fakultas Teknik</div></div>
              <div class="kv"><div class="k">ID RUP</div><div class="v">: RUP-2-26-001-FT</div></div>
              <div class="kv"><div class="k">Status Pekerjaan</div><div class="v">: Selesai</div></div>
              <div class="kv"><div class="k">Nilai Kontrak</div><div class="v">: Rp 475.000.000</div></div>
              <div class="kv"><div class="k">Rekanan</div><div class="v">: PT Teknologi Maju Bersama</div></div>
            </div>
          </article>
        @endfor
      </div>

      <div class="more">
        <a href="/arsip">Lihat Selengkapnya <span style="font-size:18px">›</span></a>
      </div>
    </div>
  </section>

  {{-- STATISTIKA --}}
<section class="stats-wrap" id="pbi">
  <div class="container">
    <div class="section-title">
      <h2>Statistika</h2>
      <p>Ringkasan arsip berdasarkan tanggal, jenis, dan unit.</p>
    </div>

    <div class="stats-card">
      <div class="stats-head">Statistika</div>

      <div class="stats-tabs">
        <button class="stats-tab active" type="button">Tanggal</button>
        <button class="stats-tab" type="button">Jenis</button>
        <button class="stats-tab" type="button">Unit</button>
      </div>

      <div class="stats-body">
        <div class="stats-chart">
          <svg width="240" height="240" viewBox="0 0 220 220" aria-label="Donut chart">
            <circle cx="110" cy="110" r="70" fill="none" stroke="#e2e8f0" stroke-width="34"></circle>

            <circle cx="110" cy="110" r="70" fill="none" stroke="#ef4444" stroke-width="34"
                    stroke-dasharray="165 280" stroke-linecap="round"
                    transform="rotate(-90 110 110)"></circle>

            <circle cx="110" cy="110" r="70" fill="none" stroke="#22c55e" stroke-width="34"
                    stroke-dasharray="95 350" stroke-linecap="round"
                    transform="rotate(45 110 110)"></circle>

            <circle cx="110" cy="110" r="70" fill="none" stroke="#3b82f6" stroke-width="34"
                    stroke-dasharray="75 370" stroke-linecap="round"
                    transform="rotate(155 110 110)"></circle>

            <circle cx="110" cy="110" r="70" fill="none" stroke="#ec4899" stroke-width="34"
                    stroke-dasharray="60 380" stroke-linecap="round"
                    transform="rotate(225 110 110)"></circle>
          </svg>
        </div>

        <div class="stats-legend">
          <div class="legend-item"><span class="dot" style="background:#3b82f6"></span> Lorem ipsum</div>
          <div class="legend-item"><span class="dot" style="background:#ec4899"></span> Lorem ipsum</div>
          <div class="legend-item"><span class="dot" style="background:#ef4444"></span> Lorem ipsum</div>
          <div class="legend-item"><span class="dot" style="background:#22c55e"></span> Lorem ipsum</div>
        </div>
      </div>
    </div>
  </div>
</section>


  {{-- KEUNGGULAN --}}
  <section class="feature-wrap">
    <div class="container">
      <div class="section-title">
        <h2>Keunggulan Sistem</h2>
        <p>Fitur utama yang mendukung pengarsipan pengadaan secara rapi dan transparan.</p>
      </div>

      <div class="features">
        <div class="feature-grid">
          <div class="feature">
            <div class="icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M6 2h9l3 3v17H6V2zm2 6h8v2H8V8zm0 4h8v2H8v-2zm0 4h6v2H8v-2z"/></svg>
            </div>
            <div>
              <h4>Arsip Terpusat</h4>
              <p>Semua arsip dokumen tersimpan rapi dalam satu sistem untuk memudahkan pencarian.</p>
            </div>
          </div>

          <div class="feature">
            <div class="icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 1a5 5 0 0 1 5 5v3h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h1V6a5 5 0 0 1 5-5zm3 8V6a3 3 0 0 0-6 0v3h6z"/></svg>
            </div>
            <div>
              <h4>Hak Akses Terkontrol</h4>
              <p>Akses dibedakan untuk PPK (super admin) dan Unit (admin) agar data tetap aman.</p>
            </div>
          </div>

          <div class="feature">
            <div class="icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16zm0 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12zm10 18-5.2-5.2 1.4-1.4L22 20.6 20 22z"/></svg>
            </div>
            <div>
              <h4>Mudah Diakses Publik</h4>
              <p>Landing page menampilkan arsip yang bisa dilihat masyarakat untuk keterbukaan informasi.</p>
            </div>
          </div>

          <div class="feature">
            <div class="icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 5c5 0 9 4.5 10 7-1 2.5-5 7-10 7S3 14.5 2 12c1-2.5 5-7 10-7zm0 2C8.5 7 5.5 9.8 4.3 12 5.5 14.2 8.5 17 12 17s6.5-2.8 7.7-5C18.5 9.8 15.5 7 12 7zm0 2.5A2.5 2.5 0 1 1 12 14a2.5 2.5 0 0 1 0-5z"/></svg>
            </div>
            <div>
              <h4>Transparansi Informasi</h4>
              <p>Mendukung akuntabilitas dengan data yang jelas: unit, status, nilai kontrak, dan rekanan.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  // tab aktif (frontend)
  document.querySelectorAll('.tab').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });
</script>
@endpush
@push('scripts')
<script>
  document.querySelectorAll('.stats-tab').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.stats-tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });
</script>
@endpush

