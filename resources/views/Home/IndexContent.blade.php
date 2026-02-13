{{-- resources/views/Home/IndexContent.blade.php --}}

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

@php
  use Illuminate\Support\Str;

  /**
   * ✅ Ambil 5 arsip PUBLIK paling update
   */
  $arsipPublik = \App\Models\Pengadaan::with('unit')
    ->where('status_arsip', 'Publik')
    ->orderByDesc('updated_at')
    ->limit(5)
    ->get();

  function idDate($dt){
    if(!$dt) return '-';
    try{
      $t = \Carbon\Carbon::parse($dt);
      $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      return (int)$t->format('d').' '.$bulan[(int)$t->format('m')].' '.$t->format('Y');
    }catch(\Throwable $e){
      return '-';
    }
  }

  function rupiah($v){
    if($v === null || $v === '') return '-';
    if(is_string($v) && Str::contains($v, 'Rp')) return $v;
    $n = is_numeric($v) ? (float)$v : (float)preg_replace('/[^\d]/', '', (string)$v);
    return 'Rp '.number_format($n, 0, ',', '.');
  }

  /**
   * ✅ Builder dokumen untuk modal (ambil field yang mengandung dokumen/file/lampiran)
   * Output: array per-field -> list file
   */
  function buildDokumenListForHome($pengadaan){
    if(!$pengadaan) return [];
    $attrs = method_exists($pengadaan, 'getAttributes') ? $pengadaan->getAttributes() : (array)$pengadaan;

    $out = [];
    foreach($attrs as $field => $rawValue){
      $lk = strtolower((string)$field);

      if(!(str_contains($lk,'dokumen') || str_contains($lk,'file') || str_contains($lk,'lampiran'))) continue;
      if(in_array($field, ['dokumen_tidak_dipersyaratkan','dokumen_tidak_dipersyaratkan_json'], true)) continue;

      $files = [];
      if(is_array($rawValue)) $files = $rawValue;
      elseif(is_string($rawValue) && trim($rawValue) !== ''){
        $s = trim($rawValue);
        $decoded = json_decode($s, true);
        if(is_array($decoded)) $files = $decoded;
        else $files = [$s];
      }

      $files = array_values(array_filter(array_map(function($x){
        if($x === null) return null;
        $s = trim((string)$x);
        if($s === '') return null;

        $s = str_replace('\\','/',$s);
        $s = explode('?', $s)[0];

        if(Str::startsWith($s, ['http://','https://'])){
          $u = parse_url($s);
          if(!empty($u['path'])) $s = $u['path'];
        }

        $s = ltrim($s,'/');
        if(Str::startsWith($s, 'public/'))  $s = Str::after($s, 'public/');
        if(Str::startsWith($s, 'storage/')) $s = Str::after($s, 'storage/');
        $s = preg_replace('#^storage/#','',$s);

        return $s !== '' ? $s : null;
      }, $files)));

      if(count($files) === 0) continue;

      foreach($files as $path){
        $out[$field][] = [
          'field' => $field,
          'name'  => basename($path),
          'url'   => '/storage/'.ltrim($path,'/'),
        ];
      }
    }

    return $out;
  }
@endphp

{{-- ARSIP LIST (REAL DB: 5 TERBARU, HANYA PUBLIK) --}}
<section id="arsip">
  <div class="container">
    <div class="section-title">
      <h2>Arsip Pengadaan Barang dan Jasa</h2>
      <p>Daftar dokumen pengadaan barang dan jasa yang dapat diakses oleh masyarakat.</p>
    </div>

    <div class="cards">
      @if($arsipPublik->count() === 0)
        <div style="opacity:.85;padding:18px;border-radius:14px;background:#fff;border:1px solid rgba(0,0,0,.08);">
          Belum ada arsip publik yang bisa ditampilkan.
        </div>
      @endif

      @foreach($arsipPublik as $item)
        @php
          $unitName = $item->unit?->nama ?? ($item->unit?->name ?? '-');
          $docsData = buildDokumenListForHome($item);

          $rawE = is_array($item->dokumen_tidak_dipersyaratkan)
            ? $item->dokumen_tidak_dipersyaratkan
            : (json_decode((string)$item->dokumen_tidak_dipersyaratkan, true) ?: []);

          $docNote = '';
          if(is_array($rawE) && count($rawE) > 0){
            $docNote = implode(', ', array_map(fn($x) => is_string($x) ? $x : json_encode($x), $rawE));
          } else {
            $eVal = is_string($item->dokumen_tidak_dipersyaratkan ?? null) ? trim((string)$item->dokumen_tidak_dipersyaratkan) : ($item->dokumen_tidak_dipersyaratkan ?? null);
            if($eVal === true || $eVal === 1 || $eVal === "1" || (is_string($eVal) && in_array(strtolower($eVal), ["ya","iya","true","yes"], true))){
              $docNote = "Dokumen pada Kolom E bersifat opsional (tidak dipersyaratkan).";
            } elseif(is_string($eVal) && $eVal !== ''){
              $docNote = $eVal;
            }
          }

          $payload = [
            'title'   => $item->nama_pekerjaan ?? '-',
            'unit'    => $unitName,
            'tahun'   => $item->tahun ?? '-',
            'idrup'   => $item->id_rup ?? '-',
            'status'  => $item->status_pekerjaan ?? '-',
            'rekanan' => $item->nama_rekanan ?? '-',
            'jenis'   => $item->jenis_pengadaan ?? '-',
            'pagu'    => rupiah($item->pagu_anggaran),
            'hps'     => rupiah($item->hps),
            'kontrak' => rupiah($item->nilai_kontrak),
            'docnote' => $docNote,
            'docs'    => $docsData,
          ];

          $dateLabel = idDate($item->updated_at ?? $item->created_at);
        @endphp

        <article class="card">
          <div class="card-top">
            <div>
              <div class="card-date">{{ $dateLabel }}</div>
              <div class="card-title">{{ $item->nama_pekerjaan ?? '-' }}</div>
            </div>

            <button
              type="button"
              class="btn-detail js-open-detail"
              data-payload='@json($payload)'
            >
              <i class="bi bi-info-circle"></i> Lihat Detail
            </button>
          </div>

          <div class="card-meta">
            <div class="meta-line"><span class="meta-k">Unit Kerja</span> : <span class="meta-v">{{ $unitName }}</span></div>
            <div class="meta-line"><span class="meta-k">ID RUP</span> : <span class="meta-v">{{ $item->id_rup ?? '-' }}</span></div>
            <div class="meta-line"><span class="meta-k">Status Pekerjaan</span> : <span class="meta-v">{{ $item->status_pekerjaan ?? '-' }}</span></div>
            <div class="meta-line"><span class="meta-k">Nilai Kontrak</span> : <span class="meta-v">{{ rupiah($item->nilai_kontrak) }}</span></div>
            <div class="meta-line"><span class="meta-k">Rekanan</span> : <span class="meta-v">{{ $item->nama_rekanan ?? '-' }}</span></div>
          </div>
        </article>
      @endforeach
    </div>

    <div class="more">
      <a href="{{ route('home.pbj') }}">
        Lihat Selengkapnya <span style="font-size:18px">›</span>
      </a>
    </div>
  </div>
</section>

{{-- ✅ MODAL DETAIL (PAKAI SISTEM pbj-modal-* sesuai landing.css) --}}
<div id="detailModal" class="pbj-modal-overlay" onclick="closeDetailModal()">
  <div class="pbj-modal" onclick="event.stopPropagation()">

    <div class="pbj-modal-head">
      <h3 class="pbj-modal-title" id="mTitle">-</h3>
      <button type="button" class="pbj-modal-close" onclick="closeDetailModal()">&times;</button>
    </div>

    <div class="pbj-modal-body">

      <div class="pbj-info-grid">
        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-envelope"></i></div>
          <div>
            <div class="pbj-info-k">Unit Kerja</div>
            <div class="pbj-info-v" id="mUnit">-</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-calendar3"></i></div>
          <div>
            <div class="pbj-info-k">Tahun Anggaran</div>
            <div class="pbj-info-v" id="mTahun">-</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-credit-card-2-front"></i></div>
          <div>
            <div class="pbj-info-k">ID RUP</div>
            <div class="pbj-info-v" id="mIdrup">-</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-bookmark-check"></i></div>
          <div>
            <div class="pbj-info-k">Status Pekerjaan</div>
            <div class="pbj-info-v" id="mStatus">-</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-person"></i></div>
          <div>
            <div class="pbj-info-k">Nama Rekanan</div>
            <div class="pbj-info-v" id="mRekanan">-</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-folder2"></i></div>
          <div>
            <div class="pbj-info-k">Jenis Pengadaan</div>
            <div class="pbj-info-v" id="mJenis">-</div>
          </div>
        </div>
      </div>

      <div class="pbj-divider"></div>

      <div class="pbj-section-title">Informasi Anggaran</div>
      <div class="pbj-budget-grid">
        <div class="pbj-budget-card">
          <div class="pbj-budget-k">Pagu Anggaran</div>
          <div class="pbj-budget-v" id="mPagu">-</div>
        </div>
        <div class="pbj-budget-card">
          <div class="pbj-budget-k">HPS</div>
          <div class="pbj-budget-v" id="mHps">-</div>
        </div>
        <div class="pbj-budget-card">
          <div class="pbj-budget-k">Nilai Kontrak</div>
          <div class="pbj-budget-v" id="mKontrak">-</div>
        </div>
      </div>

      <div class="pbj-divider"></div>

      <div class="pbj-section-title">Dokumen Pengadaan</div>

      {{-- ✅ UPDATED: 2 kolom max + tombol hanya ICON (eye) --}}
      <div class="pbj-docs-grid" id="mDocs"></div>

      <div id="mDocsEmpty" style="margin-top:10px;opacity:.85;display:none;">
        Tidak ada dokumen yang diupload.
      </div>

      {{-- ✅ KOLOM E --}}
      <div class="pbj-divider" id="mDocNoteDivider" style="display:none;"></div>
      <div id="mDocNoteBox" style="display:none;">
        <div class="pbj-section-title">Dokumen tidak dipersyaratkan</div>
        <div style="opacity:.85;" id="mDocNote">-</div>
      </div>

    </div>
  </div>
</div>

{{-- ✅ CSS KHUSUS MODAL DOKUMEN (HANYA LIHAT DETAIL) --}}
<style>
  /* maksimal 2 dokumen per baris */
  #mDocs.pbj-docs-grid{
    display:grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap:12px;
  }
  @media (max-width: 900px){
    #mDocs.pbj-docs-grid{ grid-template-columns: 1fr; }
  }

  /* card dokumen mirip Unit/Arsip: icon kiri, nama, tombol icon kanan */
  #mDocs .pbj-doc-card{
    border:1px solid rgba(0,0,0,.08);
    background:#fff;
    border-radius:16px;
    padding:12px 14px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
  }
  #mDocs .pbj-doc-left{
    display:flex;
    align-items:center;
    gap:12px;
    min-width:0;
    flex:1;
  }
  #mDocs .pbj-doc-ic{
    width:44px;
    height:44px;
    border-radius:16px;
    display:grid;
    place-items:center;
    background:#f8fbfd;
    border:1px solid rgba(0,0,0,.06);
    flex:0 0 auto;
  }
  #mDocs .pbj-doc-name{
    min-width:0;
    overflow:hidden;
    text-overflow:ellipsis;
    white-space:nowrap;
    font-weight:700;
    line-height:1.3;
  }

  /* ✅ tombol aksi: ICON saja (eye) */
  #mDocs .pbj-doc-act{
    width:40px;
    height:40px;
    border-radius:14px;
    display:grid;
    place-items:center;
    background:#f8fbfd;
    border:1px solid rgba(0,0,0,.08);
    color:#0f172a;
    text-decoration:none;
    flex:0 0 auto;
  }
  #mDocs .pbj-doc-act i{ font-size:16px; line-height:1; display:block; }
  #mDocs .pbj-doc-act:hover{ background:#eef6f8; }
</style>

{{-- STATISTIKA (SAMA PERSIS LANDING) --}}
<section class="stats-wrap" id="statistika">
  <div class="container">
    <div class="section-title">
      <h2>Statistik</h2>
    </div>

    <div class="stats-2col">
      @include('Partials.statistika-donut', ['title' => 'Status Arsip', 'donutId' => 'landingDonut'])
      @include('Partials.statistika-bar',   ['title' => 'Metode Pengadaan', 'barId' => 'landingBar'])
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
      <a href="{{ asset('regulasi/'.$item['file']) }}" target="_blank" class="reg-item">
        <div class="reg-icon"><i class="bi bi-file-earmark-text"></i></div>
        <div class="reg-text">{{ $item['judul'] }}</div>
      </a>
    @endforeach
  </div>
</section>

@push('scripts')
<script>
/* ======================
   MODAL (pbj-modal-*) => MATCH landing.css
====================== */
function openDetailModal(payload){
  const modal = document.getElementById('detailModal');
  if(!modal) return;

  // isi data
  document.getElementById('mTitle').textContent   = payload?.title   ?? '-';
  document.getElementById('mUnit').textContent    = payload?.unit    ?? '-';
  document.getElementById('mTahun').textContent   = payload?.tahun   ?? '-';
  document.getElementById('mIdrup').textContent   = payload?.idrup   ?? '-';
  document.getElementById('mStatus').textContent  = payload?.status  ?? '-';
  document.getElementById('mRekanan').textContent = payload?.rekanan ?? '-';
  document.getElementById('mJenis').textContent   = payload?.jenis   ?? '-';

  document.getElementById('mPagu').textContent    = payload?.pagu    ?? '-';
  document.getElementById('mHps').textContent     = payload?.hps     ?? '-';
  document.getElementById('mKontrak').textContent = payload?.kontrak ?? '-';

  // dokumen
  const docsWrap  = document.getElementById('mDocs');
  const docsEmpty = document.getElementById('mDocsEmpty');
  docsWrap.innerHTML = '';

  const toViewerUrl = (storageUrl) => {
    // ✅ mode=public agar viewer public bisa disable download tanpa ganggu PPK
    return `/file-viewer?file=${encodeURIComponent(storageUrl)}&mode=public`;
  };

  const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
  }[c]));

  const docsObj = payload?.docs || {};
  let totalDocs = 0;

  Object.keys(docsObj).forEach(field => {
    const arr = Array.isArray(docsObj[field]) ? docsObj[field] : [];
    arr.forEach(it => {
      if(!it?.url) return;
      totalDocs++;

      const name = it?.name || 'Dokumen';
      const viewer = toViewerUrl(it.url);

      const card = document.createElement('div');
      card.className = 'pbj-doc-card';
      card.innerHTML = `
        <div class="pbj-doc-left">
          <span class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></span>
          <span class="pbj-doc-name" title="${esc(name)}">${esc(name)}</span>
        </div>

        <!-- ✅ HANYA ICON EYE, TIDAK ADA DOWNLOAD -->
        <a href="${esc(viewer)}"
           target="_blank"
           class="pbj-doc-act"
           rel="noopener"
           title="Lihat Dokumen"
           aria-label="Lihat Dokumen"
           onclick="event.stopPropagation();"
        >
          <i class="bi bi-eye"></i>
        </a>
      `;
      docsWrap.appendChild(card);
    });
  });

  docsEmpty.style.display = totalDocs ? 'none' : 'block';

  // kolom E
  const note = (payload?.docnote || '').trim();
  const noteDivider = document.getElementById('mDocNoteDivider');
  const noteBox = document.getElementById('mDocNoteBox');
  const noteEl = document.getElementById('mDocNote');

  if(note){
    noteEl.textContent = note;
    noteDivider.style.display = 'block';
    noteBox.style.display = 'block';
  }else{
    noteEl.textContent = '-';
    noteDivider.style.display = 'none';
    noteBox.style.display = 'none';
  }

  // show modal
  modal.classList.add('show');
  document.body.style.overflow = 'hidden';
}

function closeDetailModal(){
  const modal = document.getElementById('detailModal');
  if(!modal) return;
  modal.classList.remove('show');
  document.body.style.overflow = '';
}

window.openDetailModal = openDetailModal;
window.closeDetailModal = closeDetailModal;

document.addEventListener('keydown', (e) => {
  if(e.key === 'Escape') closeDetailModal();
});

document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.js-open-detail').forEach(btn => {
    btn.addEventListener('click', function(){
      let payload = {};
      try{
        payload = JSON.parse(this.dataset.payload || '{}') || {};
      }catch(e){
        payload = {};
      }
      openDetailModal(payload);
    });
  });
});

/* =========================
   DATA DUMMY (STATISTIKA — tetap seperti sebelumnya)
========================= */
const donutData = {
  "2020": { "all": [28, 17, 22, 33], "Fakultas Pertanian": [10, 10, 30, 50] },
  "2021": { "all": [20, 25, 15, 40], "Fakultas Pertanian": [12, 18, 30, 40] }
};

const barData = {
  "2020": { "all": [35, 90, 65, 50, 75, 25], "Fakultas Pertanian": [10, 40, 20, 15, 25, 8] },
  "2021": { "all": [20, 70, 55, 40, 60, 18], "Fakultas Pertanian": [12, 35, 25, 10, 22, 6] }
};

const BAR_LABELS = [
  ["Pengadaan","Langsung"],
  ["Penunjukan","Langsung"],
  ["E-Purchasing/","E-Catalog"],
  ["Tender","Terbatas"],
  ["Tender","Terbuka"],
  ["Swakelola"]
];

function pickData(obj, year, unit, fallbackLen){
  if(obj?.[year]?.[unit]) return obj[year][unit];
  if(obj?.[year]?.all) return obj[year].all;
  return new Array(fallbackLen).fill(0);
}

/* =========================
   INIT DONUT (SAMA PERSIS LANDING)
========================= */
const donutColors = ['#0B4A5E', '#111827', '#F6C100', '#D6A357'];

const donutCtx = document.getElementById('landingDonut');
const donutYearEl = document.getElementById('donutYear');
const donutUnitEl = document.getElementById('donutUnit');

let donutChart = null;

if(donutCtx){
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

/* =========================
   INIT BAR (SAMA PERSIS LANDING)
========================= */
const splitLabel = (value) => {
  if(Array.isArray(value)) return value;
  const s = String(value ?? '');
  if(s.includes('\n')) return s.split('\n');
  const parts = s.trim().split(/\s+/);
  if(parts.length === 2) return [parts[0], parts[1]];
  return s;
};

const barCtx = document.getElementById('landingBar');
const barYearEl = document.getElementById('barYear');
const barUnitEl = document.getElementById('barUnit');

let barChart = null;

if(barCtx){
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
            maxRotation: 0,
            minRotation: 0,
            autoSkip: false,
            padding: 6,
            font: { family: 'Nunito', weight: '400', size: 11 },
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
</script>
@endpush
