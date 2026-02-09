<?php

namespace App\Http\Controllers\PPK;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            // kalau tabel/kolom ada dan sudah jalan, ini akan kepakai
            $query = Pengadaan::query()->latest();

            // OPTIONAL: kalau mau filter/search nanti bisa ditambah di sini

            $dbPaginated = $query->paginate(10)->withQueryString();

            // Kalau ada data DB, langsung pakai
            if ($dbPaginated->total() > 0) {
                // pastikan itemnya bisa dipakai blade yang sebelumnya pakai array field
                // kalau blade kamu pakai ->judul, ->tahun, dst, ini sudah oke
                $arsips = $dbPaginated;

                return view('PPK.ArsipPBJ', compact('ppkName', 'arsips'));
            }
        } catch (\Throwable $e) {
            // kalau DB belum siap (tabel belum ada / kolom beda), kita fallback ke dummy
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
     * Edit Arsip
     * - coba ambil dari DB
     * - fallback dummy
     */
    public function arsipEdit($id)
    {
        $ppkName = auth()->user()->name ?? "PPK Utama";

        try {
            $pengadaan = Pengadaan::find($id);
            if ($pengadaan) {
                // mapping minimal yang biasa dipakai blade edit kamu
                $arsip = (object) [
                    'id' => $pengadaan->id,
                    'judul' => $pengadaan->nama_pengadaan ?? ($pengadaan->judul ?? 'Pengadaan'),
                    'tahun' => $pengadaan->tahun_anggaran ?? ($pengadaan->tahun ?? date('Y')),
                    'metode' => $pengadaan->metode_pengadaan ?? ($pengadaan->metode ?? '-'),
                    'status' => $pengadaan->status ?? '-',
                ];

                return view('PPK.EditArsip', compact('ppkName', 'arsip'));
            }
        } catch (\Throwable $e) {
            // ignore, fallback dummy
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
     * Update Arsip
     * - coba update DB kalau ada record Pengadaan
     * - fallback dummy redirect
     */
    public function arsipUpdate(Request $request, $id)
    {
        $request->validate([
            'judul'  => 'nullable|string|max:255',
            'tahun'  => 'nullable|integer',
            'metode' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        try {
            $pengadaan = Pengadaan::find($id);
            if ($pengadaan) {
                // simpan ke kolom yang ada (sesuaikan model kamu)
                // aman: hanya set kalau kolomnya ada & property bisa diisi
                if (isset($pengadaan->nama_pengadaan)) $pengadaan->nama_pengadaan = $request->judul ?? $pengadaan->nama_pengadaan;
                if (isset($pengadaan->tahun_anggaran)) $pengadaan->tahun_anggaran = $request->tahun ?? $pengadaan->tahun_anggaran;
                if (isset($pengadaan->metode_pengadaan)) $pengadaan->metode_pengadaan = $request->metode ?? $pengadaan->metode_pengadaan;
                if (isset($pengadaan->status)) $pengadaan->status = $request->status ?? $pengadaan->status;

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
     * Tampilkan form Tambah Pengadaan
     */
    public function pengadaanCreate()
    {
        $ppkName = auth()->user()->name ?? "PPK Utama";
        return view('PPK.TambahPengadaan', compact('ppkName'));
    }

    /**
     * Simpan Pengadaan ke Database + Upload dokumen
     * (INI versi DB kamu, dipertahankan)
     */
    public function pengadaanStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pengadaan'   => 'required|string|max:255',
            'id_rup'           => 'nullable|string|max:100',
            'unit_kerja'       => 'required|string|max:255',
            'tahun_anggaran'   => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'metode_pengadaan' => 'required|string|max:100',
            'pagu_anggaran'    => 'required|numeric|min:0',
            'hps'              => 'nullable|numeric|min:0|lte:pagu_anggaran',
            'nilai_kontrak'    => 'nullable|numeric|min:0',
            'rekanan'          => 'nullable|string|max:255',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'deskripsi'        => 'nullable|string',
            'dokumen_rup'      => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status']  = 'Perencanaan';

        if ($request->hasFile('dokumen_rup') && $request->file('dokumen_rup')->isValid()) {
            $path = $request->file('dokumen_rup')->store('dokumen_rup', 'public');
            $validated['dokumen_rup'] = $path;
        }

        Pengadaan::create($validated);

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
}