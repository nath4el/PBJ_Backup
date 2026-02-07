<div class="stat-card">
  <div class="stat-head">
    <div class="stat-title">{{ $title }}</div>
  </div>

  <div class="stat-filters">
    <select class="stat-sel" id="barYear">
      <option selected disabled>Tahun</option>
      <option>2020</option><option>2021</option><option>2022</option>
      <option>2023</option><option>2024</option><option>2025</option><option>2026</option>
    </select>

    <select class="stat-sel" id="barUnit">
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
  </div>

  <div class="stat-body stat-body--bar">
    <div class="stat-canvas stat-canvas--bar">
      <canvas id="{{ $barId }}"></canvas>
    </div>
  </div>
</div>
