{{-- resources/views/Home/pbj.blade.php --}}
@extends('layouts.app-home')
@section('title', 'Arsip PBJ | SIAPABAJA')

@section('content')
<section class="pbj-page">
  <div class="container">

    <a class="detail-back" href="{{ route('landing') }}">

      <i class="bi bi-chevron-left"></i> Kembali
    </a>

    {{-- FILTER BAR --}}
    <div class="pbj-filters">
      <div class="pbj-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Cari..." />
      </div>

      <select class="pbj-select">
        <option selected>Semua Unit</option>
        <option>Fakultas Pertanian</option>
        <option>Fakultas Biologi</option>
        <option>Fakultas Ekonomi dan Bisnis</option>
        <option>Fakultas Peternakan</option>
        <option>Fakultas Hukum</option>
        <option>Fakultas Ilmu Sosial dan Ilmu Politik</option>
        <option>Fakultas Kedokteran</option>
        <option>Fakultas Teknik</option>
        <option>Fakultas Ilmu-Ilmu Kesehatan</option>
        <option>Fakultas Ilmu Budaya</option>
        <option>Fakultas Matematika dan Ilmu Pengetahuan Alam</option>
        <option>Fakultas Perikanan dan Ilmu Kelautan</option>
        <option>Pascasarjana</option>
        <option>LPPM</option>
        <option>LPMPP</option>
        <option>Biro Akademik dan Kemahasiswaan</option>
        <option>Biro Perencanaan, Kerjasama, dan Humas</option>
        <option>Biro Keuangan dan Umum</option>
        <option>Badan Pengelola Usaha</option>
        <option>RSGMP</option>
        <option>Satuan Pengawasan Internal</option>
        <option>UPA Perpustakaan</option>
        <option>UPA Bahasa</option>
        <option>UPA Layanan Laboratorium Terpadu</option>
        <option>UPA Layanan Uji Kompetensi</option>
        <option>UPA Pengembangan Karir dan Kewirausahaan</option>
        <option>UPA TIK</option>
     </select>

      <select class="pbj-select">
        <option>Semua Status</option>
        <option>Perencanaan</option>
        <option>Pemilihan</option>
        <option>Pelaksanaan</option>
        <option>Selesai</option>
      </select>

      <select class="pbj-select">
        <option>Semua Tahun</option>
        <option>2026</option>
        <option>2025</option>
        <option>2024</option>
      </select>

      <div class="pbj-actions">
        <button class="pbj-icon-btn" type="button" title="Refresh">
          <i class="bi bi-arrow-clockwise"></i>
        </button>
      </div>
    </div>

    @php
      $rows = [
        [
          'tahun'=>2024,
          'unit'=>'Fakultas Teknik',
          'nama'=>'Pengadaan Laboratorium Komputer Terpadu',
          'judul'=>'Penyediaan Jasa Keamanan (SATPAM) Universitas Jenderal Soedirman',
          'rup'=>'RUP-2026-001-FT',
          'nilai'=>'Rp. 100.866.549.000,00',
          'arsip'=>'Publik',
          'status'=>'Perencanaan',

          // data modal (biar persis lihat detail)
          'tahun_anggaran'=>2026,
          'id_rup'=>'RUP-2-26-001-FT',
          'status_pekerjaan'=>'Selesai',
          'rekanan'=>'PT Teknologi Maju Bersama',
          'jenis_pengadaan'=>'Tender',
          'pagu'=>'Rp 500.000.000',
          'hps'=>'Rp 480.000.000',
          'nilai_kontrak'=>'Rp 475.000.000',
        ],
        [
          'tahun'=>2024,
          'unit'=>'Fakultas Ekonomi dan Bisnis',
          'nama'=>'Pengadaan Laboratorium Komputer Terpadu',
          'judul'=>'Pengadaan Server Infrastruktur Fakultas',
          'rup'=>'RUP-2026-002-FEB',
          'nilai'=>'Rp. 100.866.549.000,00',
          'arsip'=>'Privat',
          'status'=>'Pemilihan',

          'tahun_anggaran'=>2026,
          'id_rup'=>'RUP-2-26-002-FEB',
          'status_pekerjaan'=>'Pemilihan',
          'rekanan'=>'PT Sumber Daya Digital',
          'jenis_pengadaan'=>'Tender',
          'pagu'=>'Rp 300.000.000',
          'hps'=>'Rp 280.000.000',
          'nilai_kontrak'=>'Rp 275.000.000',
        ],
        [
          'tahun'=>2024,
          'unit'=>'LPMPP',
          'nama'=>'Pengadaan Laboratorium Komputer Terpadu',
          'judul'=>'Pengadaan Jasa Konsultan Pengembangan Sistem',
          'rup'=>'RUP-2026-003-FAPET',
          'nilai'=>'Rp. 100.866.549.000,00',
          'arsip'=>'Publik',
          'status'=>'Pelaksanaan',

          'tahun_anggaran'=>2026,
          'id_rup'=>'RUP-2-26-003-LPMPP',
          'status_pekerjaan'=>'Pelaksanaan',
          'rekanan'=>'PT Inovasi Nusantara',
          'jenis_pengadaan'=>'Tender',
          'pagu'=>'Rp 200.000.000',
          'hps'=>'Rp 190.000.000',
          'nilai_kontrak'=>'Rp 185.000.000',
        ],
        [
          'tahun'=>2024,
          'unit'=>'Fakultas Hukum',
          'nama'=>'Pengadaan Laboratorium Komputer Terpadu',
          'judul'=>'Pengadaan Perangkat Jaringan Fakultas Hukum',
          'rup'=>'RUP-2026-004-FH',
          'nilai'=>'Rp. 100.866.549.000,00',
          'arsip'=>'Privat',
          'status'=>'Selesai',

          'tahun_anggaran'=>2026,
          'id_rup'=>'RUP-2-26-004-FH',
          'status_pekerjaan'=>'Selesai',
          'rekanan'=>'PT Teknologi Maju Bersama',
          'jenis_pengadaan'=>'Tender',
          'pagu'=>'Rp 150.000.000',
          'hps'=>'Rp 140.000.000',
          'nilai_kontrak'=>'Rp 135.000.000',
        ],
      ];

      function chipClass($s){
        return match($s){
          'Perencanaan' => 'chip chip-yellow',
          'Pemilihan'   => 'chip chip-purple',
          'Pelaksanaan' => 'chip chip-pink',
          'Selesai'     => 'chip chip-green',
          default       => 'chip'
        };
      }
    @endphp

    {{-- TABLE CARD --}}
    <div class="pbj-card">
      <table class="pbj-table">
        <thead>
          <tr>
            <th>Tahun</th>
            <th>Unit Kerja</th>
            <th>Nama Pekerjaan</th>
            <th>
              <span class="pbj-th-sort">
              Nilai Kontrak
              <button type="button"
              class="pbj-sort-btn"
              id="sortNilaiBtn"
              title="Urutkan Nilai Kontrak">
        <i class="bi bi-arrow-down-up" id="sortNilaiIcon"></i>
      </button>
    </span>
  </th>
            <th>Status Arsip</th>
            <th>Status Pekerjaan</th>
            <th class="pbj-col-action">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @foreach($rows as $r)
            <tr>
              <td>{{ $r['tahun'] }}</td>
              <td>{{ $r['unit'] }}</td>

              <td class="pbj-job">
                <div class="pbj-job-title">{{ $r['nama'] }}</div>
                <div class="pbj-job-sub">| {{ $r['rup'] }}</div>
              </td>

              <td class="pbj-money">{{ $r['nilai'] }}</td>

              <td>
                <span class="pbj-arsip">
                  @if($r['arsip']==='Publik')
                    <i class="bi bi-eye"></i> Publik
                  @else
                    <i class="bi bi-eye-slash"></i> Privat
                  @endif
                </span>
              </td>

              <td>
                <span class="{{ chipClass($r['status']) }}">{{ $r['status'] }}</span>
              </td>

              <td class="pbj-col-action">
                <button type="button" class="pbj-link pbj-detail-btn"
                  onclick='openPbjDetail(@json($r))'>
                  Detail
                </button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      {{-- PAGINATION BAWAH (persis gambar 2) --}}
      <div class="pbj-foot">
        <div class="pbj-foot-left" id="pbjFootText">
          Halaman 1 dari 1 • Menampilkan {{ count($rows) }} dari {{ count($rows) }} data
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

{{-- =========================
     MODAL DETAIL (PERSIS LIHAT DETAIL)
========================= --}}
<div class="pbj-modal-overlay" id="pbjModal" aria-hidden="true">
  <div class="pbj-modal">
    <div class="pbj-modal-head">
      <h3 class="pbj-modal-title" id="pbjM_judul">Detail Arsip</h3>
      <button type="button" class="pbj-modal-close" onclick="closePbjDetail()" aria-label="Tutup">×</button>
    </div>

    <div class="pbj-modal-body">
      <div class="pbj-info-grid">
        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-envelope"></i></div>
          <div>
            <div class="pbj-info-k">Unit Kerja</div>
            <div class="pbj-info-v" id="pbjM_unit">-</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-calendar3"></i></div>
          <div>
            <div class="pbj-info-k">Tahun Anggaran</div>
            <div class="pbj-info-v" id="pbjM_tahun">-</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-card-text"></i></div>
          <div>
            <div class="pbj-info-k">ID RUP</div>
            <div class="pbj-info-v" id="pbjM_rup">-</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-bookmark-check"></i></div>
          <div>
            <div class="pbj-info-k">Status Pekerjaan</div>
            <div class="pbj-info-v" id="pbjM_status">-</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-person"></i></div>
          <div>
            <div class="pbj-info-k">Nama Rekanan</div>
            <div class="pbj-info-v" id="pbjM_rekanan">-</div>
          </div>
        </div>

        <div class="pbj-info-card">
          <div class="pbj-info-ic"><i class="bi bi-folder2"></i></div>
          <div>
            <div class="pbj-info-k">Jenis Pengadaan</div>
            <div class="pbj-info-v" id="pbjM_jenis">-</div>
          </div>
        </div>
      </div>

      <div class="pbj-divider"></div>

      <div class="pbj-section-title">Informasi Anggaran</div>
      <div class="pbj-budget-grid">
        <div class="pbj-budget-card">
          <div class="pbj-budget-k">Pagu Anggaran</div>
          <div class="pbj-budget-v" id="pbjM_pagu">-</div>
        </div>

        <div class="pbj-budget-card">
          <div class="pbj-budget-k">HPS</div>
          <div class="pbj-budget-v" id="pbjM_hps">-</div>
        </div>

        <div class="pbj-budget-card">
          <div class="pbj-budget-k">Nilai Kontrak</div>
          <div class="pbj-budget-v" id="pbjM_nilai">-</div>
        </div>
      </div>

      <div class="pbj-divider"></div>

      <div class="pbj-section-title">Dokumen Pengadaan</div>
      <div class="pbj-docs-grid">
        <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

        <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

        <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

         <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

         <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

         <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

         <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

         <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

         <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

         <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

         <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>

         <div class="pbj-doc-card">
          <div class="pbj-doc-left">
            <div class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></div>
            <div class="pbj-doc-name">Dokumen RUP</div>
          </div>
          <a href="#" class="pbj-doc-btn"><i class="bi bi-eye"></i> Lihat Dokumen</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// =========================
// SORT NILAI KONTRAK
// =========================
document.addEventListener('DOMContentLoaded', () => {
  const btn   = document.getElementById('sortNilaiBtn');
  const icon  = document.getElementById('sortNilaiIcon');
  const tbody = document.querySelector('.pbj-table tbody');

  if (!btn || !icon || !tbody) return;

  let direction = 'desc'; // default: tertinggi dulu

  function parseRupiah(text){
    return parseInt(
      text.replace(/[^\d]/g, '')
    ) || 0;
  }

  btn.addEventListener('click', () => {
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
      const aVal = parseRupiah(a.children[3].innerText);
      const bVal = parseRupiah(b.children[3].innerText);
      return direction === 'desc' ? bVal - aVal : aVal - bVal;
    });

    rows.forEach(row => tbody.appendChild(row));

    // toggle arah
    if(direction === 'desc'){
      direction = 'asc';
      icon.className = 'bi bi-sort-up';
    }else{
      direction = 'desc';
      icon.className = 'bi bi-sort-down-alt';
    }
  });
});

  function openPbjDetail(d){
    const modal = document.getElementById('pbjModal');
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    document.getElementById('pbjM_judul').innerText = d.judul || d.nama || 'Detail Arsip';
    document.getElementById('pbjM_unit').innerText  = d.unit || '-';
    document.getElementById('pbjM_tahun').innerText = d.tahun_anggaran || d.tahun || '-';
    document.getElementById('pbjM_rup').innerText   = d.id_rup || d.rup || '-';
    document.getElementById('pbjM_status').innerText= d.status_pekerjaan || d.status || '-';
    document.getElementById('pbjM_rekanan').innerText = d.rekanan || '-';
    document.getElementById('pbjM_jenis').innerText = d.jenis_pengadaan || 'Tender';
    document.getElementById('pbjM_pagu').innerText  = d.pagu || '-';
    document.getElementById('pbjM_hps').innerText   = d.hps || '-';
    document.getElementById('pbjM_nilai').innerText = d.nilai_kontrak || d.nilai || '-';
  }

  function closePbjDetail(){
    const modal = document.getElementById('pbjModal');
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  document.addEventListener('click', function(e){
    const overlay = document.getElementById('pbjModal');
    if(!overlay) return;
    if(overlay.classList.contains('show') && e.target === overlay){
      closePbjDetail();
    }
  });

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape') closePbjDetail();
  });
</script>
@endsection
