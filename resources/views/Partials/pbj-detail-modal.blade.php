{{-- resources/views/partials/pbj-detail-modal.blade.php --}}

<div id="pbjDetailOverlay" class="pbj-detail-overlay" onclick="closeDetailModal()">
  <div class="pbj-detail-modal" onclick="event.stopPropagation()">

    {{-- HEADER --}}
    <div class="pbj-detail-head">
      <div class="pbj-detail-title" id="d_judul">
        Detail Arsip
      </div>
      <button type="button" class="pbj-detail-close" onclick="closeDetailModal()" aria-label="Tutup">×</button>
    </div>

    <div class="pbj-detail-body">
      <div class="pbj-detail-sep"></div>

      {{-- 6 KOTAK INFO --}}
      <div class="pbj-detail-grid">
        <div class="pbj-ditem">
          <div class="pbj-dic"><i class="bi bi-building"></i></div>
          <div>
            <div class="pbj-dk">Unit Kerja</div>
            <div class="pbj-dv" id="d_unit">-</div>
          </div>
        </div>

        <div class="pbj-ditem">
          <div class="pbj-dic"><i class="bi bi-calendar3"></i></div>
          <div>
            <div class="pbj-dk">Tahun Anggaran</div>
            <div class="pbj-dv" id="d_tahun">-</div>
          </div>
        </div>

        <div class="pbj-ditem">
          <div class="pbj-dic"><i class="bi bi-credit-card-2-front"></i></div>
          <div>
            <div class="pbj-dk">ID RUP</div>
            <div class="pbj-dv" id="d_rup">-</div>
          </div>
        </div>

        <div class="pbj-ditem">
          <div class="pbj-dic"><i class="bi bi-bookmark-check"></i></div>
          <div>
            <div class="pbj-dk">Status Pekerjaan</div>
            <div class="pbj-dv" id="d_status">-</div>
          </div>
        </div>

        <div class="pbj-ditem">
          <div class="pbj-dic"><i class="bi bi-person"></i></div>
          <div>
            <div class="pbj-dk">Nama Rekanan</div>
            <div class="pbj-dv" id="d_rekanan">-</div>
          </div>
        </div>

        <div class="pbj-ditem">
          <div class="pbj-dic"><i class="bi bi-folder2"></i></div>
          <div>
            <div class="pbj-dk">Jenis Pengadaan</div>
            <div class="pbj-dv" id="d_jenis">-</div>
          </div>
        </div>
      </div>

      <div class="pbj-detail-sep"></div>

      {{-- ANGGARAN --}}
      <div class="pbj-detail-subtitle">Informasi Anggaran</div>
      <div class="pbj-budget-grid">
        <div class="pbj-bitem">
          <div class="pbj-bk">Pagu Anggaran</div>
          <div class="pbj-bv" id="d_pagu">-</div>
        </div>
        <div class="pbj-bitem">
          <div class="pbj-bk">HPS</div>
          <div class="pbj-bv" id="d_hps">-</div>
        </div>
        <div class="pbj-bitem">
          <div class="pbj-bk">Nilai Kontrak</div>
          <div class="pbj-bv" id="d_nilai">-</div>
        </div>
      </div>

      <div class="pbj-detail-sep"></div>

      {{-- DOKUMEN --}}
      <div class="pbj-detail-subtitle">Dokumen Pengadaan</div>

      <div class="pbj-docs-scroll">
        <div class="pbj-docs-grid" id="d_docs">
          {{-- diisi oleh JS --}}
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  function openDetailModal(d){
    const overlay = document.getElementById('pbjDetailOverlay');
    if(!overlay) return;

    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';

    // isi text
    document.getElementById('d_judul').innerText    = d.judul || d.nama || 'Detail Arsip';
    document.getElementById('d_unit').innerText     = d.unit || '-';
    document.getElementById('d_tahun').innerText    = d.tahun_anggaran || d.tahun || '-';
    document.getElementById('d_rup').innerText      = d.id_rup || d.rup || '-';
    document.getElementById('d_status').innerText   = d.status_pekerjaan || d.status || '-';
    document.getElementById('d_rekanan').innerText  = d.rekanan || '-';
    document.getElementById('d_jenis').innerText    = d.jenis_pengadaan || '-';
    document.getElementById('d_pagu').innerText     = d.pagu || '-';
    document.getElementById('d_hps').innerText      = d.hps || '-';
    document.getElementById('d_nilai').innerText    = d.nilai_kontrak || d.nilai || '-';

    // dokumen (kalau kamu belum punya dari backend)
    const docs = d.dokumen || [
      { nama: 'Dokumen RUP', url: (d.doc_url || '#') },
      { nama: 'Dokumen Kontrak', url: (d.doc_url || '#') },
      { nama: 'Dokumen HPS', url: (d.doc_url || '#') },
      { nama: 'Dokumen Lainnya', url: (d.doc_url || '#') },
      { nama: 'Dokumen Lainnya', url: (d.doc_url || '#') },
      { nama: 'Dokumen Lainnya', url: (d.doc_url || '#') },
    ];

    const wrap = document.getElementById('d_docs');
    if(wrap){
      wrap.innerHTML = docs.map(x => `
        <div class="pbj-doc-row">
          <div class="pbj-doc-left">
            <span class="pbj-doc-ic"><i class="bi bi-file-earmark"></i></span>
            <span class="pbj-doc-name">${x.nama || 'Dokumen'}</span>
          </div>
          <a class="pbj-doc-btn" href="${x.url || '#'}" target="_blank" rel="noopener">
            <i class="bi bi-eye"></i> Lihat
          </a>
        </div>
      `).join('');
    }
  }

  function closeDetailModal(){
    const overlay = document.getElementById('pbjDetailOverlay');
    if(!overlay) return;

    overlay.classList.remove('show');
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', (e) => {
    if(e.key === 'Escape') closeDetailModal();
  });

  // expose global (biar onclick bisa akses)
  window.openDetailModal = openDetailModal;
  window.closeDetailModal = closeDetailModal;
</script>