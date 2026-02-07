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

        <a class="btn btn-primary" href="#arsip">Lihat Arsip</a>
      </div>

      <div class="hero-illustration">
        <img
          src="{{ asset('image/landingicon.svg') }}"
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
  <a href="{{ route('home.pbj') }}">
    Lihat Selengkapnya <span style="font-size:18px">›</span>
  </a>
</div>




  </div>
</section>

{{-- MODAL DETAIL (PERSIS) + SCROLL KHUSUS DOKUMEN --}}
<div id="detailModal" class="detail-overlay" onclick="closeDetailModal()">
  <div class="detail-modal" onclick="event.stopPropagation()">

    <div class="detail-head">
      <div class="detail-title">
        Penyediaan Jasa Keamanan (SATPAM) Universitas Jenderal Soedirman
      </div>
      <button type="button" class="detail-x" onclick="closeDetailModal()">&times;</button>
    </div>

    <div class="detail-body">
      <div class="detail-sep"></div>

      {{-- 6 kotak info --}}
      <div class="detail-grid">
        <div class="ditem">
          <div class="dic"><i class="bi bi-envelope"></i></div>
          <div><div class="dk">Unit Kerja</div><div class="dv">Fakultas Teknik</div></div>
        </div>

        <div class="ditem">
          <div class="dic"><i class="bi bi-calendar3"></i></div>
          <div><div class="dk">Tahun Anggaran</div><div class="dv">2026</div></div>
        </div>

        <div class="ditem">
          <div class="dic"><i class="bi bi-credit-card-2-front"></i></div>
          <div><div class="dk">ID RUP</div><div class="dv">RUP-2-26-001-FT</div></div>
        </div>

        <div class="ditem">
          <div class="dic"><i class="bi bi-bookmark-check"></i></div>
          <div><div class="dk">Status Pekerjaan</div><div class="dv">Selesai</div></div>
        </div>

        <div class="ditem">
          <div class="dic"><i class="bi bi-person"></i></div>
          <div><div class="dk">Nama Rekanan</div><div class="dv">PT Teknologi Maju Bersama</div></div>
        </div>

        <div class="ditem">
          <div class="dic"><i class="bi bi-folder2"></i></div>
          <div><div class="dk">Jenis Pengadaan</div><div class="dv">Tender</div></div>
        </div>
      </div>

      <div class="detail-sep"></div>

      <div class="detail-subtitle">Informasi Anggaran</div>
      <div class="budget-grid">
        <div class="bitem"><div class="bk">Pagu Anggaran</div><div class="bv">Rp 500.000.000</div></div>
        <div class="bitem"><div class="bk">HPS</div><div class="bv">Rp 480.000.000</div></div>
        <div class="bitem"><div class="bk">Nilai Kontrak</div><div class="bv">Rp 475.000.000</div></div>
      </div>

      <div class="detail-sep"></div>

      <div class="detail-subtitle">Dokumen Pengadaan</div>

      @php
        $docs = array_fill(0, 12, ['nama' => 'Dokumen RUP', 'url' => '#']);
      @endphp

      <div class="docs-scroll">
        <div class="docs-grid">
          @foreach($docs as $doc)
            <div class="doc-row">
              <div class="doc-left">
                <span class="doc-ic"><i class="bi bi-file-earmark"></i></span>
                <span class="doc-name">{{ $doc['nama'] }}</span>
              </div>

              <a 
                  href="{{ asset('dokumen/sample.pdf') }}" 
                  target="_blank"
                  class="doc-btn doc-view"
                >
                <i class="bi bi-eye"></i> Lihat
              </a>
            </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</div>

{{-- STATISTIKA --}}
<section class="stats-wrap" id="statistika">
  <div class="container">
    <div class="section-title">
      <h2>Statistika</h2>
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
        'judul' => '03 Peraturan Rektor Unsoed No. 2 Tahun 2023 Tentang  Pedoman Pengadaan BarangJasa Unsoed',
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
  // INIT DONUT
  // =========================
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
          backgroundColor: ['#134a5a','#0f172a','#f6c100','#d6a75c'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: { legend: { display:false } }
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
  // INIT BAR
  // =========================
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
  backgroundColor: '#f6c100',
  borderWidth: 0,

  borderRadius: { topLeft: 10, topRight: 10, bottomLeft: 0, bottomRight: 0 },
  borderSkipped: false,

  // 🔥 INI KUNCI NYA
  barThickness: 34,
  maxBarThickness: 34
}]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,

    // ✅ biar rapi kayak contoh
    layout: { padding: { left: 8, right: 8, top: 0, bottom: 0 } },

    plugins: {
      legend: {
        display: true,
        position: 'bottom',
        labels: { boxWidth: 14, boxHeight: 10 }
      }
    },
    scales: {
      x: { grid: { display:false }, ticks: { color:'#6b7280', font:{ size:11 } } },
      y: {
        beginAtZero: true,
        suggestedMax: 100,
        ticks: { stepSize: 20, color:'#6b7280' },
        grid: { color:'rgba(17,24,39,0.08)' }
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

  const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-links a');

window.addEventListener('scroll', () => {
  let current = '';

  sections.forEach(section => {
    const sectionTop = section.offsetTop - 120;
    if (pageYOffset >= sectionTop) {
      current = section.getAttribute('id');
    }
  });

  navLinks.forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('href')?.includes('#' + current)) {
      link.classList.add('active');
    }
  });
});
</script>
@endpush
