<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIAPABAJA - Sistem Informasi Arsip Pengadaan</title>

  <style>
    :root{
      --navy:#1e5668;
      --navy-2:#164a5a;
      --yellow:#f6c100;
      --bg:#f6f7f9;
      --text:#0f172a;
      --muted:#64748b;
      --card:#ffffff;
      --border:#d6e3ea;
      --shadow: 0 18px 45px rgba(2, 8, 23, .12);
      --shadow-soft: 0 10px 28px rgba(2, 8, 23, .10);
      --radius:16px;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      color:var(--text);
      background:linear-gradient(180deg,#ffffff 0%, #ffffff 40%, #f6f7f9 100%);
    }
    a{color:inherit;text-decoration:none}
    .container{max-width:1100px;margin:0 auto;padding:0 18px}

    .nav{
      background:var(--navy);
      color:#fff;
      position:sticky;top:0;z-index:30;
      border-bottom:4px solid var(--yellow);
    }
    .nav-inner{
      display:flex;align-items:center;justify-content:space-between;
      height:64px;
    }
    .brand{
      display:flex;align-items:center;gap:10px;font-weight:800;letter-spacing:.5px;
    }
    .brand .badge{
      width:34px;height:34px;border-radius:10px;
      background:rgba(255,255,255,.12);
      display:grid;place-items:center;
      border:1px solid rgba(255,255,255,.18);
    }
    .brand .name{color:var(--yellow);font-size:18px}

    .nav-links{
      display:flex;align-items:center;gap:22px;
      font-size:13px;color:rgba(255,255,255,.85);
    }
    .nav-links a:hover{color:#fff}

    .btn{
      border:none;cursor:pointer;
      padding:10px 14px;border-radius:10px;
      font-weight:700;font-size:13px;
      display:inline-flex;align-items:center;gap:8px;
      transition:.2s;
    }
    .btn-primary{
      background:var(--navy-2);color:#fff;
      box-shadow:0 10px 22px rgba(22,74,90,.25);
    }
    .btn-primary:hover{transform:translateY(-1px);filter:brightness(1.02)}
    .btn-white{
      background:#fff;color:var(--navy-2);
      border:1px solid rgba(255,255,255,.6);
    }
    .btn-white:hover{transform:translateY(-1px)}

    .hero{padding:48px 0 18px}
    .hero-grid{
      display:grid;grid-template-columns: 1.15fr .85fr;
      gap:28px;align-items:center;
    }
    .hero h1{margin:0;font-size:34px;line-height:1.18;letter-spacing:-.5px}
    .hero .u{color:var(--yellow);font-weight:800;display:block;margin-top:6px;font-size:22px}
    .hero p{
      margin:12px 0 18px;color:var(--muted);
      max-width:560px;font-size:14px;line-height:1.7;
    }
    .hero-illustration{
      width:100%;border-radius:22px;
      background:linear-gradient(135deg, rgba(30,86,104,.08), rgba(246,193,0,.08));
      border:1px solid rgba(30,86,104,.15);
      box-shadow:var(--shadow-soft);
      overflow:hidden;min-height:240px;
      display:grid;place-items:center;
    }
    .hero-illustration img{max-width:92%;height:auto;display:block;transform:translateY(6px)}

    .section-title{text-align:center;padding:28px 0 10px}
    .section-title h2{margin:0;font-size:20px;letter-spacing:-.2px}
    .section-title p{margin:6px 0 0;font-size:12px;color:var(--muted)}

    .cards{margin-top:12px;display:grid;gap:14px}
    .card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:18px;
      padding:14px 16px;
      box-shadow:0 10px 20px rgba(2,8,23,.06);
    }
    .card-top{
      display:flex;align-items:flex-start;justify-content:space-between;gap:12px;
      padding-bottom:10px;border-bottom:1px solid #eef3f6;
    }
    .card-date{font-size:11px;color:var(--muted);margin-bottom:6px}
    .card-title{font-weight:800;font-size:14px;color:var(--navy-2)}
    .card-meta{
      margin-top:10px;
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:6px 18px;
      font-size:11px;
      color:#334155;
    }
    .kv{display:flex;gap:8px}
    .k{color:var(--muted);min-width:92px}
    .v{font-weight:600}
    .btn-detail{
      padding:10px 12px;border-radius:12px;background:var(--navy-2);
      color:#fff;font-weight:800;font-size:12px;border:0;cursor:pointer;
      display:inline-flex;align-items:center;gap:8px;white-space:nowrap;height:40px;
    }
    .btn-detail:hover{filter:brightness(1.03);transform:translateY(-1px)}
    .more{display:flex;justify-content:flex-end;padding:10px 0 0}
    .more a{color:var(--navy-2);font-weight:800;font-size:12px;display:inline-flex;align-items:center;gap:8px}

    .stats-wrap{padding:22px 0 0}
    .stats-card{
      max-width:520px;margin:0 auto;background:#fff;border:1px solid #e8eef2;
      border-radius:18px;box-shadow:var(--shadow-soft);overflow:hidden;
    }
    .stats-head{padding:12px 16px;font-weight:800;text-align:center;border-bottom:1px solid #eef3f6}
    .tabs{padding:12px 16px;display:flex;justify-content:center}
    .tab-pill{
      background:#eef3f6;border-radius:999px;padding:6px;display:flex;gap:6px;
      box-shadow:inset 0 0 0 1px #dde7ee;
    }
    .tab{
      padding:8px 16px;font-size:12px;border-radius:999px;cursor:pointer;
      color:#0f172a;font-weight:800;border:0;background:transparent;
    }
    .tab.active{
      background:var(--navy-2);color:#fff;
      box-shadow:0 10px 16px rgba(22,74,90,.25);
    }
    .donut{padding:18px 18px 20px;display:grid;grid-template-columns: 1fr .75fr;gap:18px;align-items:center}
    .legend{font-size:12px}
    .legend .item{display:flex;align-items:center;gap:10px;margin:8px 0;color:#334155;font-weight:600}
    .dot{width:10px;height:10px;border-radius:50%;background:#94a3b8}

    .feature-wrap{padding:16px 0 56px}
    .features{
      background:#fff;border-radius:18px;box-shadow:var(--shadow);
      border:1px solid #e9eef2;padding:26px 22px;max-width:980px;margin:0 auto;
    }
    .feature-grid{display:grid;grid-template-columns: 1fr 1fr;gap:22px 40px}
    .feature{display:flex;gap:14px;align-items:flex-start;padding:10px 6px}
    .icon{
      width:52px;height:52px;border-radius:14px;background:var(--navy-2);
      display:grid;place-items:center;box-shadow:0 10px 18px rgba(22,74,90,.25);
      flex:0 0 auto;
    }
    .icon svg{width:22px;height:22px;fill:#ffd54d}
    .feature h4{margin:0 0 6px;font-size:14px}
    .feature p{margin:0;color:var(--muted);font-size:12px;line-height:1.6}

    .footer{margin-top:28px;background:var(--navy);color:rgba(255,255,255,.85)}
    .footer-inner{
      padding:26px 0;display:grid;
      grid-template-columns: 1.2fr 1.2fr .6fr;
      gap:18px;align-items:start;
    }
    .footer .mini{font-size:12px;color:rgba(255,255,255,.7);line-height:1.7;margin-top:10px}
    .footer h5{margin:0;color:#fff;font-size:12px;letter-spacing:.3px}
    .social{display:flex;gap:10px;justify-content:flex-end;align-items:center;padding-top:8px}
    .social a{
      width:34px;height:34px;border-radius:10px;display:grid;place-items:center;
      background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.16);
    }
    .copyright{
      border-top:1px solid rgba(255,255,255,.12);
      padding:14px 0;text-align:center;font-size:12px;color:rgba(255,255,255,.7);
    }

    @media(max-width: 900px){
      .hero-grid{grid-template-columns:1fr}
      .donut{grid-template-columns:1fr}
      .feature-grid{grid-template-columns:1fr}
      .footer-inner{grid-template-columns:1fr}
      .social{justify-content:flex-start}
    }
  </style>
</head>

<body>
  {{-- NAVBAR --}}
  <header class="nav">
    <div class="container nav-inner">
      <div class="brand">
        <div class="badge" aria-hidden="true">🏛️</div>
        <div class="name">SIAPABAJA</div>
      </div>

      <nav class="nav-links">
        <a href="#beranda">Beranda</a>
        <a href="#pbi">PBI</a>
        <a href="#kontak">Kontak</a>

        {{-- front-end: sementara arahkan ke /login --}}
        <a class="btn btn-white" href="/login">Masuk</a>
      </nav>
    </div>
  </header>

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

          <a class="btn btn-primary" href="#arsip">MULAI</a>
        </div>

        <div class="hero-illustration">
          {{-- taruh gambar di public/images/hero-illustration.png --}}
          <img src="{{ asset('images/hero-illustration.png') }}" alt="Ilustrasi pengguna"
               onerror="this.style.display='none'">
        </div>
      </div>
    </div>
  </section>

  {{-- ARSIP LIST (frontend dummy) --}}
  <section id="arsip">
    <div class="container">
      <div class="section-title">
        <h2>Arsip Pengadaan Barang dan Jasa</h2>
        <p>Daftar dokumen pengadaan barang dan jasa yang dapat diakses oleh masyarakat.</p>
      </div>

      <div class="cards">
        @for($i=0; $i<4; $i++)
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
  <section id="statistika" class="stats-wrap">
  <div class="container">

    <div class="section-title">
      <h2>Statistika</h2>
      <p>Ringkasan arsip berdasarkan tanggal, jenis, dan unit.</p>
    </div>

    <div class="stats-card">
      <div class="stats-head">Statistika</div>

      <div class="stats-body">

        <!-- KIRI -->
        <div class="stats-left">
          <div class="stats-tabs">
            <button type="button" class="stats-tab active">Tanggal</button>
            <button type="button" class="stats-tab">Jenis</button>
            <button type="button" class="stats-tab">Unit</button>
          </div>

          <div class="stats-chart">
            <div class="donut-figma"></div>
          </div>
        </div>

        <!-- KANAN -->
        <div class="stats-legend">
          <div class="legend-item">
            <span class="dot" style="background:#3b82f6"></span>
            <span>Lorem ipsum</span>
          </div>
          <div class="legend-item">
            <span class="dot" style="background:#ec4899"></span>
            <span>Lorem ipsum</span>
          </div>
          <div class="legend-item">
            <span class="dot" style="background:#ef4444"></span>
            <span>Lorem ipsum</span>
          </div>
          <div class="legend-item">
            <span class="dot" style="background:#22c55e"></span>
            <span>Lorem ipsum</span>
          </div>
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

  {{-- FOOTER --}}
  <footer id="kontak" class="footer">
    <div class="container footer-inner">
      <div>
        <div class="brand">
          <div class="badge" aria-hidden="true">🏛️</div>
          <div class="name">SIAPABAJA</div>
        </div>
        <div class="mini">
          Sistem pengarsipan pengadaan barang dan jasa untuk mendukung keterbukaan informasi dan efisiensi pengelolaan dokumen.
        </div>
      </div>

      <div>
        <h5>Contact us</h5>
        <div class="mini">
          Jalan Prof. Dr. HR. Boenjamin 708 Kotak Pos 115 Grendeng Purwokerto 53122<br/>
          Telepon (0281) 635292 (Hunting), 633337, 638795 Faksimile 631802
        </div>
      </div>

      <div class="social" aria-label="Social links">
        <a href="#" aria-label="Facebook">f</a>
        <a href="#" aria-label="Instagram">◎</a>
        <a href="#" aria-label="Twitter">𝕏</a>
        <a href="#" aria-label="LinkedIn">in</a>
      </div>
    </div>

    <div class="copyright">
      Copyright © {{ date('Y') }} SIAPABAJA. All rights reserved.
    </div>
  </footer>

  <script>
    // Tab UI kecil (frontend)
    document.querySelectorAll('.tab').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
      });
    });
  </script>
</body>
</html>