<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UnitController extends Controller
{
    public function dashboard()
    {
        // Kalau blade dashboard butuh nama unit, siapkan aja:
        $unitName = auth()->user()->name ?? 'Unit Kerja';

        return view('Unit.Dashboard', compact('unitName'));
    }

    public function arsipIndex(Request $request)
    {
        $unitName = auth()->user()->name;

        // Dummy data tabel arsip (silakan sesuaikan kalau blade kamu butuh field tertentu)
        // NOTE: field ini sengaja pakai schema yang nyambung ke blade kamu
        $arsipList = [
            [
                'id' => 1,
                'pekerjaan' => 'Pengadaan Laptop | RUP-2026-001-FT',
                'tahun' => 2025,
                'metode_pbj' => 'E-Purchasing / E-Catalogue',
                'nilai_kontrak' => 'Rp. 10.000.000,00',
                'status_arsip' => 'Publik',
                'status_pekerjaan' => 'Selesai',
                'unit' => 'Fakultas Teknik',
            ],
            [
                'id' => 2,
                'pekerjaan' => 'Pengadaan ATK | RUP-2026-002-FT',
                'tahun' => 2024,
                'metode_pbj' => 'Pengadaan Langsung',
                'nilai_kontrak' => 'Rp. 2.500.000,00',
                'status_arsip' => 'Privat',
                'status_pekerjaan' => 'Pelaksanaan',
                'unit' => 'Fakultas Teknik',
            ],
            // ✅ biar kelihatan pagination, duplikasi dummy jadi banyak item
            [
                'id' => 3,
                'pekerjaan' => 'Pengadaan Printer | RUP-2026-003-FT',
                'tahun' => 2025,
                'metode_pbj' => 'Pengadaan Langsung',
                'nilai_kontrak' => 'Rp. 3.750.000,00',
                'status_arsip' => 'Publik',
                'status_pekerjaan' => 'Pemilihan',
                'unit' => 'Fakultas Teknik',
            ],
            [
                'id' => 4,
                'pekerjaan' => 'Pengadaan Proyektor | RUP-2026-004-FT',
                'tahun' => 2024,
                'metode_pbj' => 'E-Purchasing / E-Catalogue',
                'nilai_kontrak' => 'Rp. 8.200.000,00',
                'status_arsip' => 'Privat',
                'status_pekerjaan' => 'Perencanaan',
                'unit' => 'Fakultas Teknik',
            ],
            [
                'id' => 5,
                'pekerjaan' => 'Pengadaan Server | RUP-2026-005-FT',
                'tahun' => 2025,
                'metode_pbj' => 'Tender',
                'nilai_kontrak' => 'Rp. 150.000.000,00',
                'status_arsip' => 'Publik',
                'status_pekerjaan' => 'Pelaksanaan',
                'unit' => 'Fakultas Teknik',
            ],
            [
                'id' => 6,
                'pekerjaan' => 'Pengadaan Router | RUP-2026-006-FT',
                'tahun' => 2024,
                'metode_pbj' => 'Pengadaan Langsung',
                'nilai_kontrak' => 'Rp. 5.500.000,00',
                'status_arsip' => 'Publik',
                'status_pekerjaan' => 'Selesai',
                'unit' => 'Fakultas Teknik',
            ],
        ];

        // =========================
        // ✅ PAGINATION (DUMMY) - bikin $arsips jadi LengthAwarePaginator
        // =========================
        $perPage = (int) ($request->get('per_page', 4)); // biar kelihatan pagination
        $page    = (int) ($request->get('page', 1));

        $collection = Collection::make($arsipList);
        $total = $collection->count();

        $items = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $arsips = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(), // supaya query string tetap kebawa
            ]
        );

        // ✅ Blade kamu butuh $arsips untuk munculin pagination
        return view('Unit.ArsipPBJ', compact('unitName', 'arsips'));
    }

    public function arsipEdit($id)
    {
        $unitName = "Fakultas Teknik";

        // Dummy 1 arsip untuk edit page
        // Penting: bikin object agar bisa dipanggil $arsip->id di blade
        $arsip = (object) [
            'id' => (int) $id,
            'judul' => 'Pengadaan Laptop',
            'tahun' => 2025,
            'metode' => 'E-Purchasing / E-Catalogue',
            'status' => 'Selesai',
        ];

        return view('Unit.EditArsip', compact('unitName', 'arsip'));
    }

    public function arsipUpdate(Request $request, $id)
    {
        // sementara dummy: validasi minimal biar aman
        $request->validate([
            'judul'  => 'nullable|string|max:255',
            'tahun'  => 'nullable|integer',
            'metode' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        // TODO: kalau sudah ada model, lakukan update di sini

        return redirect()
            ->route('unit.arsip')
            ->with('success', 'Arsip berhasil diperbarui (dummy).');
    }

    public function pengadaanCreate()
    {
        $unitName = "Fakultas Teknik";

        return view('Unit.TambahPengadaan', compact('unitName'));
    }

    public function pengadaanStore(Request $request)
    {
        // dummy validasi minimal
        $request->validate([
            'judul' => 'nullable|string|max:255',
            'tahun' => 'nullable|integer',
        ]);

        // TODO: simpan ke DB kalau sudah siap

        return redirect()
            ->route('unit.pengadaan.create')
            ->with('success', 'Pengadaan berhasil disimpan (dummy).');
    }
}
