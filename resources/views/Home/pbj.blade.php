{{-- resources/views/Home/pbj.blade.php --}}
@extends('layouts.app-home')
@section('title', 'Arsip PBJ | SIAPABAJA')

@section('content')
<section class="pbj-page">
  <div class="container">

    {{-- ✅ bedanya HOME: balik ke home --}}
    <a class="detail-back" href="{{ route('home') }}">
      <i class="bi bi-chevron-left"></i> Kembali
    </a>

    @php
      use App\Models\Pengadaan;
      use App\Models\Unit;
      use Illuminate\Support\Str;

      // =========================
      // ✅ FILTER (SAMA KONSEP DENGAN PPK/ArsipPBJ)
      // Bedanya: HOME hanya tampilkan arsip PUBLIK
      // Dan "status" di HOME = status_pekerjaan
      // =========================
      $q = request('q');
      $unitId = request('unit_id');
      $statusPekerjaan = request('status_pekerjaan');
      $tahun = request('tahun');

      // opsi status pekerjaan (samakan dengan PPK)
      $statusPekerjaanOptions = ["Perencanaan", "Pemilihan", "Pelaksanaan", "Selesai"];

      // ✅ Unit dropdown dari DB (SEMUA unit yang ada di tabel units) -> sama seperti PPK/ArsipPBJ
      $unitOptions = Unit::orderBy('nama')->get();

      // ✅ Tahun dropdown dari DB (HANYA yang muncul di pengadaans publik)
      $tahunOptions = Pengadaan::where('status_arsip', 'Publik')
        ->whereNotNull('tahun')
        ->select('tahun')
        ->distinct()
        ->orderBy('tahun', 'desc')
        ->pluck('tahun')
        ->map(fn($t) => (int)$t)
        ->values();

      // =========================
      // ✅ QUERY ARSIP (REALTIME + URUT SAMA PPK)
      // paling atas = yang TERUPDATE
      // =========================
      $arsipQuery = Pengadaan::with('unit')
        ->where('status_arsip', 'Publik');

      if($q){
        $qq = trim($q);
        $arsipQuery->where(function($sub) use ($qq){
          $sub->where('nama_pekerjaan','like',"%{$qq}%")
              ->orWhere('id_rup','like',"%{$qq}%")
              ->orWhere('nama_rekanan','like',"%{$qq}%");
        });
      }

      if($unitId && is_numeric($unitId)){
        $arsipQuery->where('unit_id', (int)$unitId);
      }

      if($statusPekerjaan && in_array($statusPekerjaan, $statusPekerjaanOptions, true)){
        $arsipQuery->where('status_pekerjaan', $statusPekerjaan);
      }

      if($tahun && is_numeric($tahun)){
        $arsipQuery->where('tahun', (int)$tahun);
      }

      // ✅ urutan realtime sama seperti Home/IndexContent & PPK (terbaru di atas)
      $arsips = $arsipQuery
        ->orderByDesc('updated_at')
        ->orderByDesc('id')
        ->get();

      $totalRows = $arsips->count();

      // helper rupiah (samakan tampilan)
      $rupiah = function($v){
        if($v === null || $v === '') return '-';
        if(is_string($v) && Str::contains($v, 'Rp')) return $v;
        $n = is_numeric($v) ? (float)$v : (float)preg_replace('/[^\d]/', '', (string)$v);
        return 'Rp. ' . number_format($n, 0, ',', '.') . ',00';
      };

      function chipClass($s){
        return match($s){
          'Perencanaan' => 'chip chip-yellow',
          'Pemilihan'   => 'chip chip-purple',
          'Pelaksanaan' => 'chip chip-pink',
          'Selesai'     => 'chip chip-green',
          default       => 'chip'
        };
      }

      /**
       * ✅ Builder dokumen untuk modal (SAMA PERSIS dengan Home/IndexContent)
       * Output: object per-field -> list file
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

      // ✅ Kolom E (dokumen tidak dipersyaratkan) -> sama seperti IndexContent
      function buildDocNoteForHome($pengadaan){
        if(!$pengadaan) return '';

        $rawE = is_array($pengadaan->dokumen_tidak_dipersyaratkan ?? null)
          ? $pengadaan->dokumen_tidak_dipersyaratkan
          : (json_decode((string)($pengadaan->dokumen_tidak_dipersyaratkan ?? ''), true) ?: []);

        if(is_array($rawE) && count($rawE) > 0){
          return implode(', ', array_map(fn($x) => is_string($x) ? $x : json_encode($x), $rawE));
        }

        $eVal = is_string($pengadaan->dokumen_tidak_dipersyaratkan ?? null)
          ? trim((string)$pengadaan->dokumen_tidak_dipersyaratkan)
          : ($pengadaan->dokumen_tidak_dipersyaratkan ?? null);

        if($eVal === true || $eVal === 1 || $eVal === "1" || (is_string($eVal) && in_array(strtolower($eVal), ["ya","iya","true","yes"], true))){
          return "Dokumen pada Kolom E bersifat opsional (tidak dipersyaratkan).";
        }

        return is_string($eVal) ? $eVal : '';
      }

      // ✅ payload untuk modal (match IndexContent)
      $payloadRows = $arsips->map(function($a) use ($rupiah){
        $unitName = $a->unit?->nama ?? ($a->unit?->name ?? '-');

        return [
          'title'   => $a->nama_pekerjaan ?? '-',
          'unit'    => $unitName,
          'tahun'   => $a->tahun ?? '-',
          'idrup'   => $a->id_rup ?? '-',
          'status'  => $a->status_pekerjaan ?? '-',
          'rekanan' => $a->nama_rekanan ?? '-',
          'jenis'   => $a->jenis_pengadaan ?? '-',
          'pagu'    => $rupiah($a->pagu_anggaran),
          'hps'     => $rupiah($a->hps),
          'kontrak' => $rupiah($a->nilai_kontrak),
          'docnote' => buildDocNoteForHome($a),
          'docs'    => buildDokumenListForHome($a),
        ];
      })->values()->all();
    @endphp

    {{-- FILTER BAR --}}
    <form class="pbj-filters" method="GET" action="{{ url()->current() }}">
      <div class="pbj-search">
        <i class="bi bi-search"></i>
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari..." />
      </div>

      {{-- ✅ Unit dari DB (SEMUA units.nama) --}}
      <select class="pbj-select" name="unit_id" onchange="this.form.submit()">
        <option value="" {{ !$unitId ? 'selected' : '' }}>Semua Unit</option>
        @foreach($unitOptions as $u)
          <option value="{{ $u->id }}" {{ (string)$unitId === (string)$u->id ? 'selected' : '' }}>
            {{ $u->nama }}
          </option>
        @endforeach
      </select>

      {{-- ✅ Status di HOME = Status Pekerjaan --}}
      <select class="pbj-select" name="status_pekerjaan" onchange="this.form.submit()">
        <option value="" {{ !$statusPekerjaan ? 'selected' : '' }}>Semua Status</option>
        @foreach($statusPekerjaanOptions as $sp)
          <option value="{{ $sp }}" {{ (string)$statusPekerjaan === (string)$sp ? 'selected' : '' }}>
            {{ $sp }}
          </option>
        @endforeach
      </select>

      {{-- ✅ Tahun dari DB --}}
      <select class="pbj-select" name="tahun" onchange="this.form.submit()">
        <option value="" {{ !$tahun ? 'selected' : '' }}>Semua Tahun</option>
        @foreach($tahunOptions as $t)
          <option value="{{ $t }}" {{ (string)$tahun === (string)$t ? 'selected' : '' }}>
            {{ $t }}
          </option>
        @endforeach
      </select>

      <div class="pbj-actions">
        {{-- ✅ refresh = reset filter --}}
        <a class="pbj-icon-btn" href="{{ url()->current() }}" title="Refresh" style="display:inline-flex; align-items:center; justify-content:center;">
          <i class="bi bi-arrow-clockwise"></i>
        </a>
      </div>
    </form>

    {{-- TABLE CARD --}}
    <div class="pbj-card">
      <table class="pbj-table" style="table-layout:fixed; width:100%;">
        <thead>
          <tr>
            <th style="width:90px;">Tahun</th>
            <th style="width:180px;">Unit Kerja</th>
            <th>Nama Pekerjaan</th>
            <th style="width:200px;">
              <span class="pbj-th-sort">
                Nilai Kontrak
                <button type="button" class="pbj-sort-btn" id="sortNilaiBtn" title="Urutkan Nilai Kontrak">
                  <i class="bi bi-arrow-down-up" id="sortNilaiIcon"></i>
                </button>
              </span>
            </th>
            <th style="width:140px;">Status Arsip</th>
            <th style="width:180px;">Status Pekerjaan</th>
            <th class="pbj-col-action" style="width:90px;">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @foreach($arsips as $idx => $a)
            @php
              $payload = $payloadRows[$idx] ?? [];
              $nilaiText = $rupiah($a->nilai_kontrak ?? null);
            @endphp
            <tr>
              <td>{{ $a->tahun ?? '-' }}</td>
              <td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                {{ $a->unit?->nama ?? '-' }}
              </td>

              {{-- ✅ TIDAK MENAMPILKAN ID RUP di bawah nama pekerjaan --}}
              <td class="pbj-job">
                <div class="pbj-job-title" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  {{ $a->nama_pekerjaan ?? '-' }}
                </div>
              </td>

              <td class="pbj-money">{{ $nilaiText }}</td>

              <td>
                <span class="pbj-arsip">
                  <i class="bi bi-eye"></i> Publik
                </span>
              </td>

              <td>
                <span class="{{ chipClass($a->status_pekerjaan ?? '') }}">{{ $a->status_pekerjaan ?? '-' }}</span>
              </td>

              <td class="pbj-col-action">
                <button type="button" class="pbj-link pbj-detail-btn" onclick='openDetailModal(@json($payload))'>
                  Detail
                </button>
              </td>
            </tr>
          @endforeach

          @if($totalRows === 0)
            <tr>
              <td colspan="7" style="text-align:center; padding:22px;">
                Tidak ada data arsip publik yang sesuai filter.
              </td>
            </tr>
          @endif
        </tbody>
      </table>

      {{-- PAGINATION BAWAH --}}
      <div class="pbj-foot">
        <div class="pbj-foot-left" id="pbjFootText">
          Halaman 1 dari 1 • Menampilkan {{ $totalRows }} dari {{ $totalRows }} data
        </div>

        <div class="pbj-pager">
          <button class="pbj-page-btn" type="button" disabled>
            <i class="bi bi-chevron-left"></i>
          </button>
          <button class="pbj-page-num is-active" type="button">1</button>
          <button class="pbj-page-btn" type="button" disabled>
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>

  </div>
</section>

{{-- ✅ MODAL DETAIL (SAMA PERSIS Home/IndexContent) --}}
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

      {{-- ✅ 2 kolom max + tombol hanya ICON (eye) --}}
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

{{-- ✅ CSS KHUSUS MODAL DOKUMEN (SAMA PERSIS Home/IndexContent) --}}
<style>
  #mDocs.pbj-docs-grid{
    display:grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap:12px;
  }
  @media (max-width: 900px){
    #mDocs.pbj-docs-grid{ grid-template-columns: 1fr; }
  }

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
@endsection

@push('scripts')
<script>
// SORT NILAI KONTRAK
document.addEventListener('DOMContentLoaded', () => {
  const btn   = document.getElementById('sortNilaiBtn');
  const icon  = document.getElementById('sortNilaiIcon');
  const tbody = document.querySelector('.pbj-table tbody');
  if (!btn || !icon || !tbody) return;

  let direction = 'desc';

  function parseRupiah(text){
    return parseInt((text || '').replace(/[^\d]/g, '')) || 0;
  }

  btn.addEventListener('click', () => {
    const rows = Array.from(tbody.querySelectorAll('tr'))
      .filter(tr => tr.children && tr.children.length >= 7);

    rows.sort((a, b) => {
      const aVal = parseRupiah(a.children[3].innerText);
      const bVal = parseRupiah(b.children[3].innerText);
      return direction === 'desc' ? bVal - aVal : aVal - bVal;
    });

    rows.forEach(row => tbody.appendChild(row));

    if(direction === 'desc'){
      direction = 'asc';
      icon.className = 'bi bi-sort-up';
    }else{
      direction = 'desc';
      icon.className = 'bi bi-sort-down-alt';
    }
  });
});

/* ======================
   MODAL (SAMA PERSIS Home/IndexContent)
====================== */
function openDetailModal(payload){
  const modal = document.getElementById('detailModal');
  if(!modal) return;

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

  const docsWrap  = document.getElementById('mDocs');
  const docsEmpty = document.getElementById('mDocsEmpty');
  docsWrap.innerHTML = '';

  const toViewerUrl = (storageUrl) => {
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

  modal.classList.add('show');
  document.body.style.overflow = 'hidden';
}

function closeDetailModal(){
  const modal = document.getElementById('detailModal');
  if(!modal) return;
  modal.classList.remove('show');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e){
  if(e.key === 'Escape') closeDetailModal();
});
</script>
@endpush
