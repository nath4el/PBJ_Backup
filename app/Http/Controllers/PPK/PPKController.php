<?php

namespace App\Http\Controllers\PPK;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PpkController extends Controller
{
    /**
     * Dashboard PPK
     */
    public function dashboard()
    {
        $ppkName = auth()->user()->name ?? 'PPK Utama';
        return view('PPK.Dashboard', compact('ppkName'));
    }

    /**
     * Daftar Arsip PBJ
     * - Prioritas: ambil dari DB (Pengadaan)
     * - Fallback: dummy 12 data biar view tetap kebuka walau DB kosong / belum siap
     */
    public function arsipIndex(Request $request)
    {
        $ppkName = auth()->user()->name ?? "PPK Utama";

        // =========================
        // 1) Coba ambil dari DB
        // =========================
        try {
            // eager load unit untuk tampilkan nama unit di arsip
            $query = Pengadaan::with('unit')->latest();

            $dbPaginated = $query->paginate(10)->withQueryString();

            if ($dbPaginated->total() > 0) {
                // Mapping ke bentuk yang sama seperti dummy agar blade tidak perlu diubah
                $mapped = $dbPaginated->getCollection()->map(function ($p) {
                    return [
                        'id' => $p->id,

                        // blade dummy pakai "judul", kita isi dari "nama_pekerjaan"
                        'judul' => $p->nama_pekerjaan ?? '-',
                        'tahun' => $p->tahun ?? null,

                        // blade dummy pakai metode & status
                        // metode kita mapping dari jenis_pengadaan
                        'metode' => $p->jenis_pengadaan ?? '-',
                        'status' => $p->status_pekerjaan ?? '-',

                        // angka rupiah di DB integer -> tampilin string rupiah
                        'nilai_kontrak' => $this->formatRupiah($p->nilai_kontrak),

                        // tambahan detail yang dipakai dummy view
                        'unit' => $p->unit?->nama ?? '-',
                        'status_arsip' => $p->status_arsip ?? '-',
                        'idrup' => $p->id_rup ?? '-',
                        'rekanan' => $p->nama_rekanan ?? '-',
                        'jenis' => $p->jenis_pengadaan ?? '-',
                        'pagu' => $this->formatRupiah($p->pagu_anggaran),
                        'hps' => $this->formatRupiah($p->hps),

                        // dokumen_tidak_dipersyaratkan di DB jsonb -> tampilkan teks ringkas
                        'dokumen_tidak_dipersyaratkan' => is_array($p->dokumen_tidak_dipersyaratkan)
                            ? (count($p->dokumen_tidak_dipersyaratkan) > 0
                                ? 'Ada dokumen pendukung (opsional).'
                                : 'Tidak ada dokumen pendukung.')
                            : 'Tidak ada dokumen pendukung.',
                    ];
                });

                // Ganti collection paginator -> tetap paginator, tapi itemnya array dummy-shape
                $dbPaginated->setCollection($mapped);

                $arsips = $dbPaginated;
                return view('PPK.ArsipPBJ', compact('ppkName', 'arsips'));
            }
        } catch (\Throwable $e) {
            // fallback dummy
        }

        // =========================
        // 2) FALLBACK: DUMMY 12 DATA
        // =========================
        $arsipList = [
            [
                'id' => 101,
                'judul' => 'Pengadaan Server Virtualisasi',
                'tahun' => 2025,
                'metode' => 'Tender Terbuka',
                'status' => 'Pemilihan',
                'nilai_kontrak' => 'Rp. 980.000.000,00',

                'unit' => 'UPT TIK',
                'status_arsip' => 'Privat',
                'idrup' => '2026-009',
                'rekanan' => 'PT Data Cloud Indonesia',
                'jenis' => 'Tender',
                'pagu' => 'Rp 1.050.000.000',
                'hps' => 'Rp 1.020.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Dokumen Kolom E tidak dipersyaratkan. Jika ada, unggah sebagai dokumen pendukung.',
            ],
            [
                'id' => 102,
                'judul' => 'Pengadaan Perangkat Presentasi Ruang Rapat',
                'tahun' => 2024,
                'metode' => 'E-Purchasing',
                'status' => 'Pelaksanaan',
                'nilai_kontrak' => 'Rp. 185.000.000,00',

                'unit' => 'Fakultas Ekonomi dan Bisnis',
                'status_arsip' => 'Privat',
                'idrup' => '2026-002',
                'rekanan' => 'CV Sinar Media',
                'jenis' => 'E-Katalog',
                'pagu' => 'Rp 200.000.000',
                'hps' => 'Rp 195.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Kolom E berisi dokumen pendukung (opsional). Jika tidak diunggah, proses tetap dapat berjalan.',
            ],
            [
                'id' => 103,
                'judul' => 'Pengadaan Laboratorium Komputer Terpadu',
                'tahun' => 2024,
                'metode' => 'Pengadaan Langsung',
                'status' => 'Perencanaan',
                'nilai_kontrak' => 'Rp. 1.250.000.000,00',

                'unit' => 'Fakultas Teknik',
                'status_arsip' => 'Publik',
                'idrup' => '2026-001',
                'rekanan' => 'PT Teknologi Maju Nusantara',
                'jenis' => 'Tender',
                'pagu' => 'Rp 1.300.000.000',
                'hps' => 'Rp 1.280.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Dokumen pada Kolom E bersifat opsional (tidak dipersyaratkan).',
            ],
            [
                'id' => 104,
                'judul' => 'Pemeliharaan Jaringan Internet Kampus',
                'tahun' => 2024,
                'metode' => 'Pengadaan Langsung',
                'status' => 'Pelaksanaan',
                'nilai_kontrak' => 'Rp. 95.000.000,00',

                'unit' => 'Fakultas Hukum',
                'status_arsip' => 'Publik',
                'idrup' => '2026-003',
                'rekanan' => 'PT Netlink Solusi',
                'jenis' => 'Non Tender',
                'pagu' => 'Rp 100.000.000',
                'hps' => 'Rp 98.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Dokumen pada Kolom E tidak dipersyaratkan untuk pengadaan ini.',
            ],
            [
                'id' => 105,
                'judul' => 'Pengadaan Alat Kesehatan Klinik',
                'tahun' => 2024,
                'metode' => 'Tender Cepat',
                'status' => 'Selesai',
                'nilai_kontrak' => 'Rp. 620.000.000,00',

                'unit' => 'Fakultas Kedokteran',
                'status_arsip' => 'Privat',
                'idrup' => '2026-004',
                'rekanan' => 'PT Medika Sehat Sentosa',
                'jenis' => 'Tender',
                'pagu' => 'Rp 650.000.000',
                'hps' => 'Rp 640.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Dokumen Kolom E (opsional): surat dukungan / brosur tambahan.',
            ],
            [
                'id' => 106,
                'judul' => 'Pengadaan Bibit dan Pupuk Praktikum',
                'tahun' => 2023,
                'metode' => 'E-Purchasing',
                'status' => 'Selesai',
                'nilai_kontrak' => 'Rp. 75.500.000,00',

                'unit' => 'Fakultas Pertanian',
                'status_arsip' => 'Publik',
                'idrup' => '2025-005',
                'rekanan' => 'UD Tani Makmur',
                'jenis' => 'E-Katalog',
                'pagu' => 'Rp 80.000.000',
                'hps' => 'Rp 79.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Dokumen pada Kolom E bersifat opsional (tidak dipersyaratkan).',
            ],
            [
                'id' => 107,
                'judul' => 'Pengadaan Buku Referensi Perpustakaan',
                'tahun' => 2023,
                'metode' => 'E-Purchasing',
                'status' => 'Pelaksanaan',
                'nilai_kontrak' => 'Rp. 120.000.000,00',

                'unit' => 'Fakultas Ilmu Budaya',
                'status_arsip' => 'Privat',
                'idrup' => '2025-006',
                'rekanan' => 'PT Pustaka Utama',
                'jenis' => 'E-Katalog',
                'pagu' => 'Rp 130.000.000',
                'hps' => 'Rp 128.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Kolom E tidak wajib. Silakan unggah jika ada dokumen pendukung tambahan.',
            ],
            [
                'id' => 108,
                'judul' => 'Pengadaan Reagen Laboratorium Kimia',
                'tahun' => 2023,
                'metode' => 'Tender',
                'status' => 'Pemilihan',
                'nilai_kontrak' => 'Rp. 410.000.000,00',

                'unit' => 'Fakultas MIPA',
                'status_arsip' => 'Publik',
                'idrup' => '2025-007',
                'rekanan' => 'PT Labindo Raya',
                'jenis' => 'Tender',
                'pagu' => 'Rp 450.000.000',
                'hps' => 'Rp 440.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Dokumen Kolom E opsional (tidak dipersyaratkan).',
            ],
            [
                'id' => 109,
                'judul' => 'Jasa Konsultansi Penyusunan Roadmap Riset',
                'tahun' => 2024,
                'metode' => 'Seleksi',
                'status' => 'Perencanaan',
                'nilai_kontrak' => 'Rp. 275.000.000,00',

                'unit' => 'LPPM',
                'status_arsip' => 'Publik',
                'idrup' => '2026-008',
                'rekanan' => 'PT Konsultan Mandiri',
                'jenis' => 'Seleksi',
                'pagu' => 'Rp 300.000.000',
                'hps' => 'Rp 295.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Kolom E berisi lampiran tambahan (opsional), misalnya TOR versi revisi.',
            ],
            [
                'id' => 110,
                'judul' => 'Pengadaan Peralatan Kebersihan Gedung',
                'tahun' => 2024,
                'metode' => 'Pengadaan Langsung',
                'status' => 'Selesai',
                'nilai_kontrak' => 'Rp. 48.500.000,00',

                'unit' => 'Biro Umum',
                'status_arsip' => 'Publik',
                'idrup' => '2026-010',
                'rekanan' => 'CV Bersih Jaya',
                'jenis' => 'Non Tender',
                'pagu' => 'Rp 50.000.000',
                'hps' => 'Rp 49.500.000',

                'dokumen_tidak_dipersyaratkan' => 'Dokumen pada Kolom E bersifat opsional (tidak dipersyaratkan).',
            ],
            [
                'id' => 111,
                'judul' => 'Pengadaan Pakan Ternak Praktikum',
                'tahun' => 2024,
                'metode' => 'E-Purchasing',
                'status' => 'Pemilihan',
                'nilai_kontrak' => 'Rp. 135.000.000,00',

                'unit' => 'Fakultas Peternakan',
                'status_arsip' => 'Privat',
                'idrup' => '2026-011',
                'rekanan' => 'PT Agro Feed Nusantara',
                'jenis' => 'E-Katalog',
                'pagu' => 'Rp 150.000.000',
                'hps' => 'Rp 147.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Kolom E opsional: sertifikat/COA tambahan (jika tersedia).',
            ],
            [
                'id' => 112,
                'judul' => 'Pengadaan Lisensi Software Pembelajaran',
                'tahun' => 2023,
                'metode' => 'Tender Cepat',
                'status' => 'Perencanaan',
                'nilai_kontrak' => 'Rp. 360.000.000,00',

                'unit' => 'LPMPP',
                'status_arsip' => 'Publik',
                'idrup' => '2025-012',
                'rekanan' => 'PT Edu Tech Solution',
                'jenis' => 'Tender',
                'pagu' => 'Rp 390.000.000',
                'hps' => 'Rp 385.000.000',

                'dokumen_tidak_dipersyaratkan' => 'Dokumen pada Kolom E tidak dipersyaratkan untuk pengadaan ini.',
            ],
        ];

        $perPage = 10;
        $page = (int) $request->query('page', 1);
        if ($page < 1) $page = 1;

        $total = count($arsipList);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($arsipList, $offset, $perPage);

        $arsips = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('PPK.ArsipPBJ', compact('ppkName', 'arsips'));
    }

    /**
     * Edit Arsip (PPK)
     * - ambil dari DB kalau ada
     * - fallback dummy
     */
    public function arsipEdit($id)
    {
        $ppkName = auth()->user()->name ?? "PPK Utama";

        try {
            $pengadaan = Pengadaan::with('unit')->find($id);
            if ($pengadaan) {
                // mapping minimal yang dipakai blade edit kamu (judul, tahun, metode, status)
                $arsip = (object) [
                    'id' => $pengadaan->id,
                    'judul' => $pengadaan->nama_pekerjaan ?? '-',
                    'tahun' => $pengadaan->tahun ?? (int)date('Y'),
                    'metode' => $pengadaan->jenis_pengadaan ?? '-',
                    'status' => $pengadaan->status_pekerjaan ?? '-',
                ];

                return view('PPK.EditArsip', compact('ppkName', 'arsip'));
            }
        } catch (\Throwable $e) {
            // fallback dummy
        }

        $arsip = (object) [
            'id' => (int) $id,
            'judul' => 'Pengadaan Server',
            'tahun' => 2025,
            'metode' => 'Tender Terbuka',
            'status' => 'Pemilihan',
        ];

        return view('PPK.EditArsip', compact('ppkName', 'arsip'));
    }

    /**
     * Update Arsip (PPK)
     * - update DB (kolom baru)
     */
    public function arsipUpdate(Request $request, $id)
    {
        $request->validate([
            'judul'  => 'nullable|string|max:255',
            'tahun'  => 'nullable|integer|min:2000|max:' . (date('Y') + 5),
            'metode' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        try {
            $pengadaan = Pengadaan::find($id);
            if ($pengadaan) {
                // mapping dari form edit (judul/tahun/metode/status) -> kolom pengadaans baru
                if ($request->filled('judul'))  $pengadaan->nama_pekerjaan = $request->judul;
                if ($request->filled('tahun'))  $pengadaan->tahun = (int)$request->tahun;
                if ($request->filled('metode')) $pengadaan->jenis_pengadaan = $request->metode;
                if ($request->filled('status')) $pengadaan->status_pekerjaan = $request->status;

                $pengadaan->save();

                return redirect()
                    ->route('ppk.arsip')
                    ->with('success', 'Arsip berhasil diperbarui (database).');
            }
        } catch (\Throwable $e) {
            // fallback dummy
        }

        return redirect()
            ->route('ppk.arsip')
            ->with('success', 'Arsip berhasil diperbarui (dummy).');
    }

    /**
     * Tampilkan form Tambah Pengadaan (PPK)
     * (Tidak mengubah view. Kita hanya menambahkan $units jika view butuh dropdown)
     */
    public function pengadaanCreate()
    {
        $ppkName = auth()->user()->name ?? "PPK Utama";
        $units = Unit::orderBy('nama')->get(); // aman meskipun view tidak memakai
        return view('PPK.TambahPengadaan', compact('ppkName', 'units'));
    }

    /**
     * Simpan Pengadaan ke Database + Upload dokumen (sesuai form TambahPengadaan.blade.php yang kamu upload)
     * - support: unit_id (jika form sudah pakai id)
     * - support: unit_kerja string (jika form masih pakai nama unit)
     * - simpan dokumen multiple ke storage + jsonb
     */
    public function pengadaanStore(Request $request)
    {
        // 1) Resolusi unit_id tanpa mengubah view
        $unitId = $request->input('unit_id');

        if (!$unitId) {
            $unitKerja = trim((string) $request->input('unit_kerja', ''));
            if ($unitKerja !== '') {
                $unit = Unit::whereRaw('LOWER(nama) = ?', [mb_strtolower($unitKerja)])->first();
                if (!$unit) {
                    return back()->withErrors(['unit_kerja' => 'Unit kerja belum ada di master units.'])->withInput();
                }
                $unitId = $unit->id;
            }
        }

        if (!$unitId) {
            return back()->withErrors(['unit_id' => 'Unit wajib dipilih.'])->withInput();
        }

        // 2) Validasi field sesuai FORM kamu (tanpa ubah file view)
        $data = $request->validate([
            // Informasi umum
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'nama_pekerjaan' => 'nullable|string|max:255',
            'id_rup' => 'nullable|string|max:255',
            'jenis_pengadaan' => 'required|string|max:100',
            'status_pekerjaan' => 'required|string|max:100',

            // akses arsip
            'status_arsip' => 'required|in:Publik,Privat',

            // anggaran (di form biasanya string rupiah)
            'pagu_anggaran' => 'nullable|string|max:50',
            'hps' => 'nullable|string|max:50',
            'nilai_kontrak' => 'nullable|string|max:50',
            'nama_rekanan' => 'nullable|string|max:255',

            // hidden json
            'dokumen_tidak_dipersyaratkan_json' => 'nullable|string',
        ]);

        // 3) Normalisasi rupiah -> integer
        $toInt = function ($v) {
            if ($v === null) return null;
            $num = preg_replace('/[^0-9]/', '', (string)$v);
            return $num === '' ? null : (int)$num;
        };
        $data['pagu_anggaran'] = $toInt($data['pagu_anggaran'] ?? null);
        $data['hps'] = $toInt($data['hps'] ?? null);
        $data['nilai_kontrak'] = $toInt($data['nilai_kontrak'] ?? null);

        // 4) Parse dokumen tidak dipersyaratkan json
        $data['dokumen_tidak_dipersyaratkan'] = [];
        if (!empty($data['dokumen_tidak_dipersyaratkan_json'])) {
            $decoded = json_decode($data['dokumen_tidak_dipersyaratkan_json'], true);
            if (is_array($decoded)) $data['dokumen_tidak_dipersyaratkan'] = $decoded;
        }
        unset($data['dokumen_tidak_dipersyaratkan_json']);

        // 5) Set kolom wajib DB
        $data['unit_id'] = (int)$unitId;
        $data['created_by'] = Auth::id();

        // 6) create dulu untuk dapat id (folder upload rapi)
        $pengadaan = Pengadaan::create($data);

        // 7) Upload dokumen multiple sesuai field name di form
        $fileFields = [
            'dokumen_kak','dokumen_hps','dokumen_spesifikasi_teknis','dokumen_rancangan_kontrak',
            'dokumen_lembar_data_kualifikasi','dokumen_lembar_data_pemilihan','dokumen_daftar_kuantitas_harga',
            'dokumen_jadwal_lokasi_pekerjaan','dokumen_gambar_rancangan_pekerjaan','dokumen_amdal',
            'dokumen_penawaran','surat_penawaran','dokumen_kemenkumham','ba_pemberian_penjelasan',
            'ba_pengumuman_negosiasi','ba_sanggah_banding','ba_penetapan','laporan_hasil_pemilihan',
            'dokumen_sppbj','surat_perjanjian_kemitraan','surat_perjanjian_swakelola',
            'surat_penugasan_tim_swakelola','dokumen_mou','dokumen_kontrak','ringkasan_kontrak',
            'jaminan_pelaksanaan','jaminan_uang_muka','jaminan_pemeliharaan','surat_tagihan',
            'surat_pesanan_epurchasing','dokumen_spmk','dokumen_sppd','laporan_pelaksanaan_pekerjaan',
            'laporan_penyelesaian_pekerjaan','bap','bast_sementara','bast_akhir','dokumen_pendukung_lainya',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $paths = [];
                foreach ((array) $request->file($field) as $file) {
                    if (!$file) continue;
                    $filename = Str::random(8) . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $stored = $file->storeAs("public/pengadaan/{$pengadaan->id}/{$field}", $filename);
                    $paths[] = Str::replaceFirst('public/', '', $stored); // simpan tanpa "public/"
                }
                $pengadaan->{$field} = $paths;
            }
        }

        $pengadaan->save();

        return redirect()
            ->route('ppk.arsip')
            ->with('success', 'Pengadaan baru berhasil ditambahkan!');
    }

    // =========================
    // ✅ KELOLA AKUN (PPK)
    // =========================
    public function kelolaAkun()
    {
        $ppkName = auth()->user()->name ?? "PPK Utama";
        return view('PPK.KelolaAkun', compact('ppkName'));
    }

    /**
     * Update akun (name/email + optional password)
     */
    public function updateAkun(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()
                ->back()
                ->withErrors(['auth' => 'Kamu belum login. Silakan login dulu.'])
                ->withInput();
        }

        $wantsPasswordChange = $request->filled('password') || $request->filled('password_confirmation');

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ];

        if ($wantsPasswordChange) {
            $rules['current_password'] = ['required', 'string'];
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            $rules['current_password'] = ['nullable', 'string'];
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        $data = $request->validate($rules);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if ($wantsPasswordChange) {
            if (!Hash::check($data['current_password'], $user->password)) {
                return redirect()
                    ->back()
                    ->withErrors(['current_password' => 'Password saat ini salah.'])
                    ->withInput();
            }

            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()
            ->route('ppk.kelola.akun')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Helper format rupiah
     */
    private function formatRupiah($value): string
    {
        if ($value === null || $value === '') return '-';
        $num = (int) $value;
        return 'Rp ' . number_format($num, 0, ',', '.');
    }
}
