<?php

namespace App\Http\Controllers\PPK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PpkController extends Controller
{
    public function dashboard()
    {
        $ppkName = auth()->user()->name ?? 'Unit Kerja'; // opsional kalau dibutuhkan blade

        return view('PPK.Dashboard', compact('ppkName'));
    }

    public function arsipIndex(Request $request)
    {
        $ppkName = "PPK Utama";

        // =========================
        // DUMMY DATA (tetap)
        // =========================
        $arsipList = [
            [
                'id' => 101,
                'judul' => 'Pengadaan Server',
                'tahun' => 2025,
                'metode' => 'Tender Terbuka',
                'status' => 'Pemilihan',
                'nilai_kontrak' => 'Rp. 100.866.549.000,00',
            ],
            [
                'id' => 102,
                'judul' => 'Pengadaan Jasa Konsultan',
                'tahun' => 2024,
                'metode' => 'Penunjukan Langsung',
                'status' => 'Pelaksanaan',
                'nilai_kontrak' => 'Rp. 50.000.549.000,00',
            ],
            // Kalau mau test pagination beneran, boleh duplikat data ini banyakin
        ];

        // =========================
        // PAGINATION (dari array)
        // =========================
        $perPage = 10; // jumlah data per halaman
        $page = (int) $request->query('page', 1);
        if ($page < 1) $page = 1;

        $total = count($arsipList);
        $offset = ($page - 1) * $perPage;

        $items = array_slice($arsipList, $offset, $perPage);

        $arsips = new LengthAwarePaginator(
            $items,            // data untuk halaman ini
            $total,            // total seluruh data
            $perPage,          // per halaman
            $page,             // halaman sekarang
            [
                'path' => $request->url(),      // path URL /ppk/arsip
                'query' => $request->query(),   // penting: biar filter/search kebawa (kalau ada)
            ]
        );

        // NOTE:
        // - sekarang view akan menerima $arsips (paginator)
        // - biar Blade pagination kamu jalan, pakai $arsips di view
        return view('PPK.ArsipPBJ', compact('ppkName', 'arsips'));
    }

    public function arsipEdit($id)
    {
        $ppkName = "PPK Utama";

        $arsip = (object) [
            'id' => (int) $id,
            'judul' => 'Pengadaan Server',
            'tahun' => 2025,
            'metode' => 'Tender Terbuka',
            'status' => 'Pemilihan',
        ];

        return view('PPK.EditArsip', compact('ppkName', 'arsip'));
    }

    public function arsipUpdate(Request $request, $id)
    {
        $request->validate([
            'judul'  => 'nullable|string|max:255',
            'tahun'  => 'nullable|integer',
            'metode' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        return redirect()
            ->route('ppk.arsip')
            ->with('success', 'Arsip berhasil diperbarui (dummy).');
    }

    public function pengadaanCreate()
    {
        $ppkName = "PPK Utama";

        return view('PPK.TambahPengadaan', compact('ppkName'));
    }

    public function pengadaanStore(Request $request)
    {
        $request->validate([
            'judul' => 'nullable|string|max:255',
            'tahun' => 'nullable|integer',
        ]);

        return redirect()
            ->route('ppk.pengadaan.create')
            ->with('success', 'Pengadaan berhasil disimpan (dummy).');
    }
}
