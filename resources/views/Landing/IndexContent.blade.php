{{-- HERO --}}
<section id="Dashboard" class="hero">
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

        <a class="btn btn-primary" href="#arsip">Lihat Arsip Terbaru</a>
      </div>

      <div class="hero-illustration">
        <img
          src="{{ asset('image/amico.png') }}"
          alt="Ilustrasi Arsip"
          class="hero-img"
        >
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

            <button type="button" class="btn-detail" onclick="openDetailModal()">
              <i class="bi bi-info-circle"></i> Lihat Detail
            </button>
          </div>

          <div class="card-meta">
            <div class="meta-line"><span class="meta-k">Unit Kerja</span> : <span class="meta-v">Fakultas Teknik</span></div>
            <div class="meta-line"><span class="meta-k">ID RUP</span> : <span class="meta-v">RUP-2026-001-FT</span></div>
            <div class="meta-line"><span class="meta-k">Status Pekerjaan</span> : <span class="meta-v">Selesai</span></div>
            <div class="meta-line"><span class="meta-k">Nilai Kontrak</span> : <span class="meta-v">Rp 475.000.000</span></div>
            <div class="meta-line"><span class="meta-k">Rekanan</span> : <span class="meta-v">PT Teknologi Maju Bersama</span></div>
          </div>
        </article>
      @endfor
    </div>

 <div class="more">
  <a href="{{ auth()->check() ? route('home') : route('login') }}">
    Lihat Selengkapnya <span style="font-size:18px">›</span>
  </a>
</div>

  </div>
</section>

{{-- MODAL DETAIL (PAKAI pbj-modal-*) --}}
<div id="detailModal" class="pbj-modal-overlay" onclick="closeDetailModal()">
  <div class="pbj-modal" onclick="event.stopPropagation()">

    <div class="pbj-modal-head">
      <h3 class="pbj-modal-title">
        Penyediaan Jasa Keamanan (SATPAM) Universitas Jenderal Soedirman
      </h3>
      <button type="button" class="pbj-modal-close" onclick="closeDetailModal()">&times;</button>
    </div>

    <div class="pbj-modal-body">

      {{-- 6 info box --}}
      <div class="pbj-info-grid">
        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-envelope"></i></div>
          <div>
            <div class="pbj-info-k">Unit Kerja</div>
            <div class="pbj-info-v">Fakultas Teknik</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-calendar3"></i></div>
          <div>
            <div class="pbj-info-k">Tahun Anggaran</div>
            <div class="pbj-info-v">2026</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-credit-card-2-front"></i></div>
          <div>
            <div class="pbj-info-k">ID RUP</div>
            <div class="pbj-info-v">RUP-2-26-001-FT</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-bookmark-check"></i></div>
          <div>
            <div class="pbj-info-k">Status Pekerjaan</div>
            <div class="pbj-info-v">Selesai</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-person"></i></div>
          <div>
            <div class="pbj-info-k">Nama Rekanan</div>
            <div class="pbj-info-v">PT Teknologi Maju Bersama</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-folder2"></i></div>
          <div>
            <div class="pbj-info-k">Jenis Pengadaan</div>
            <div class="pbj-info-v">Tender</div>
          </div>
        </div>
      </div>

      <div class="pbj-divider"></div>

      {{-- Informasi Anggaran --}}
      <div class="pbj-section-title">Informasi Anggaran</div>
      <div class="pbj-budget-grid">
        <div class="pbj-budget-card">
          <div class="pbj-budget-k">Pagu Anggaran</div>
          <div class="pbj-budget-v">Rp 500.000.000</div>
        </div>

        <div class="pbj-budget-card">
          <div class="pbj-budget-k">HPS</div>
          <div class="pbj-budget-v">Rp 480.000.000</div>
        </div>

        <div class="pbj-budget-card">
          <div class="pbj-budget-k">Nilai Kontrak</div>
          <div class="pbj-budget-v">Rp 475.000.000</div>
        </div>
      </div>

      <div class="pbj-divider"></div>

      {{-- Dokumen Pengadaan --}}
      <div class="pbj-section-title">Dokumen Pengadaan</div>

      @php
        $docs = array_fill(0, 12, [
          'nama' => 'RUP',
          'url'  => asset('dokumen/sample.pdf')
        ]);
      @endphp

      <div class="pbj-docs-grid">
        @foreach($docs as $doc)
          <div class="pbj-doc-card">
            <div class="pbj-doc-left">
              <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
              <div class="pbj-doc-name">{{ $doc['nama'] }}</div>
            </div>

            <a class="pbj-doc-btn" href="{{ $doc['url'] }}" target="_blank" rel="noopener">
              <i class="bi bi-eye"></i> Lihat Dokumen
            </a>
          </div>
        @endforeach
      </div>

    </div>
  </div>
</div>

{{-- STATISTIKA --}}
<section class="stats-wrap" id="statistika">
  <div class="container">
    <div class="section-title">
      <h2>Statistik</h2>
    </div>

    <div class="stats-2col">

      {{-- KIRI : STATUS ARSIP --}}
      @include('Partials.statistika-donut', [
        'title' => 'Status Arsip',
        'donutId' => 'landingDonut'
      ])

      {{-- KANAN : METODE PENGADAAN --}}
      @include('Partials.statistika-bar', [
        'title' => 'Metode Pengadaan',
        'barId' => 'landingBar'
      ])

    </div>
  </div>
</section>

{{-- REGULASI --}}
<section class="reg-wrap" id="regulasi">
  @php
    $regulasi = [
      [
        'judul' => '01 Perpres-No-12-Tahun-2021 Perubahan Atas Peraturan Presiden Nomor 16 Tahun 2018 tentang PBJ Pemerintah',
        'file'  => '01 Perpres-No-12-Tahun-2021 Perubahan Atas Peraturan Presiden Nomor 16 Tahun 2018 tentang PBJ Pemerintah.pdf'
      ],
      [
        'judul' => '02 Peraturan LKPP No. 12 Tahun 2021 Tentang Pedoman Pelaksanaan PBJ Pemerintah Melalui Penyedia',
        'file'  => '02 Peraturan LKPP No. 12 Tahun 2021 Tentang Pedoman Pelaksanaan PBJ Pemerintah Melalui Penyedia.pdf'
      ],
      [
        'judul' => '03 Peraturan Rektor Unsoed No. 2 Tahun 2023 Tentang  Pedoman Pengadaan Barang Jasa Unsoed',
        'file'  => '03 Peraturan Rektor Unsoed No. 2 Tahun 2023 Tentang  Pedoman Pengadaan BarangJasa Unsoed.pdf'
      ],
    ];
  @endphp

  <div class="container">
    <div class="section-title">
      <h2>Regulasi</h2>
    </div>
  </div>

  <div class="reg-card">
    @foreach($regulasi as $item)
      <a href="{{ asset('regulasi/'.$item['file']) }}"
         target="_blank"
         class="reg-item">
        <div class="reg-icon">
          <i class="bi bi-file-earmark-text"></i>
        </div>
        <div class="reg-text">
          {{ $item['judul'] }}
        </div>
      </a>
    @endforeach
  </div>
</section>

@push('scripts')
<script>
  // ======================
  // MODAL
  // ======================
  function openDetailModal() {
    const modal = document.getElementById('detailModal');
    if (!modal) return;

    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    if (!modal) return;

    modal.classList.remove('show');
    document.body.style.overflow = '';
  }

  window.openDetailModal = openDetailModal;
  window.closeDetailModal = closeDetailModal;

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDetailModal();
  });

  // =========================
  // DATA DUMMY (contoh)
  // struktur: data[year][unit]
  // =========================
  const donutData = {
    "2020": {
      "all": [28, 17, 22, 33],
      "Fakultas Pertanian": [10, 10, 30, 50]
    },
    "2021": {
      "all": [20, 25, 15, 40],
      "Fakultas Pertanian": [12, 18, 30, 40]
    }
  };

  const barData = {
    "2020": {
      "all": [35, 90, 65, 50, 75, 25],
      "Fakultas Pertanian": [10, 40, 20, 15, 25, 8]
    },
    "2021": {
      "all": [20, 70, 55, 40, 60, 18],
      "Fakultas Pertanian": [12, 35, 25, 10, 22, 6]
    }
  };

  const BAR_LABELS = [
    ["Pengadaan","Langsung"],
    ["Penunjukan","Langsung"],
    ["E-Purchasing/","E-Catalog"],
    ["Tender","Terbatas"],
    ["Tender","Terbuka"],
    ["Swakelola"]
  ];

  // helper ambil data aman
  function pickData(obj, year, unit, fallbackLen) {
    if (obj?.[year]?.[unit]) return obj[year][unit];
    if (obj?.[year]?.all) return obj[year].all;
    return new Array(fallbackLen).fill(0);
  }

  // =========================
  // INIT DONUT (SAMA PERSIS TEMEN)
  // =========================
  const donutColors = ['#0B4A5E', '#111827', '#F6C100', '#D6A357'];

  const donutCtx = document.getElementById('landingDonut');
  const donutYearEl = document.getElementById('donutYear');
  const donutUnitEl = document.getElementById('donutUnit');

  let donutChart = null;

  if (donutCtx) {
    const initYear = (donutYearEl?.value && donutYearEl.value !== 'Tahun') ? donutYearEl.value : "2020";
    const initUnit = donutUnitEl?.value || "all";

    donutChart = new Chart(donutCtx, {
      type: 'doughnut',
      data: {
        labels: ['Perencanaan','Pemilihan','Pelaksanaan','Selesai'],
        datasets: [{
          data: pickData(donutData, initYear, initUnit, 4),
          backgroundColor: donutColors,
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '55%',

        // ✅ geser legend agak ke tengah (sama persis temen)
        layout: { padding: { right: 70 } },

        plugins: {
          legend: {
            display: true,
            position: 'right',
            labels: {
              boxWidth: 10,
              boxHeight: 10,
              padding: 12,
              font: { family: 'Nunito', weight: '400', size: 14 }
            }
          },
          tooltip: { enabled: true }
        }
      }
    });

    function updateDonut(){
      const year = (donutYearEl?.value && donutYearEl.value !== 'Tahun') ? donutYearEl.value : "2020";
      const unit = donutUnitEl?.value || "all";
      donutChart.data.datasets[0].data = pickData(donutData, year, unit, 4);
      donutChart.update();
    }

    donutYearEl?.addEventListener('change', updateDonut);
    donutUnitEl?.addEventListener('change', updateDonut);
  }


  // =========================
  // INIT BAR (SAMA PERSIS TEMEN)
  // =========================

  // helper supaya label tidak miring & jadi 2 baris
  const splitLabel = (value) => {
    if (Array.isArray(value)) return value;
    const s = String(value ?? '');
    if (s.includes('\n')) return s.split('\n');
    const parts = s.trim().split(/\s+/);
    if (parts.length === 2) return [parts[0], parts[1]];
    return s;
  };

  const barCtx = document.getElementById('landingBar');
  const barYearEl = document.getElementById('barYear');
  const barUnitEl = document.getElementById('barUnit');

  let barChart = null;

  if (barCtx) {
    const initYear = (barYearEl?.value && barYearEl.value !== 'Tahun') ? barYearEl.value : "2020";
    const initUnit = barUnitEl?.value || "all";

    barChart = new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: BAR_LABELS,
        datasets: [{
          label: initYear,
          data: pickData(barData, initYear, initUnit, 6),
          backgroundColor: '#F6C100',
          borderWidth: 0,

          // ❗️INI BEDA UTAMA DARI PUNYA KAMU
          borderRadius: 6   // temen: bulat halus (BUKAN bawah rata)
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              font: { family: 'Nunito', weight: '400', size: 14 }
            }
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

              // ❗️INI YANG BIKIN SAMA PERSIS
              callback: function (value) {
                const raw = this.getLabelForValue(value);
                return splitLabel(raw);
              }
            },
            grid: { display: false }
          }
        }
      }
    });

    function updateBar(){
      const year = (barYearEl?.value && barYearEl.value !== 'Tahun') ? barYearEl.value : "2020";
      const unit = barUnitEl?.value || "all";

      barChart.data.datasets[0].label = year;
      barChart.data.datasets[0].data = pickData(barData, year, unit, 6);
      barChart.update();
    }

    barYearEl?.addEventListener('change', updateBar);
    barUnitEl?.addEventListener('change', updateBar);
  }

  function openDetailModal(){
    const modal = document.getElementById('detailModal');
    if(!modal) return;
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeDetailModal(){
    const modal = document.getElementById('detailModal');
    if(!modal) return;
    modal.classList.remove('show');
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDetailModal();
  });
 // Toggle Dropdown Profile setelah login (versi lebih aman)
document.addEventListener('DOMContentLoaded', function () {
    const profileBtn = document.querySelector('.nav-user-btn');
    const profileMenu = document.querySelector('.nav-user-menu');

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Toggle tampilkan/sembunyikan menu
            const isVisible = profileMenu.style.display === 'block';
            profileMenu.style.display = isVisible ? 'none' : 'block';
        });

        // Tutup dropdown jika klik di luar area profile
        document.addEventListener('click', function (e) {
            if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
