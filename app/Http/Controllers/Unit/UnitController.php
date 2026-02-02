<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function dashboard()
    {
        // Kalau blade dashboard butuh nama unit, siapkan aja:
        $unitName = "Fakultas Teknik";

        return view('Unit.Dashboard', compact('unitName'));
    }

    public function arsipIndex()
    {
        $unitName = "Fakultas Teknik";

        // Dummy data tabel arsip (silakan sesuaikan kalau blade kamu butuh field tertentu)
        $arsipList = [
            [
                'id' => 1,
                'judul' => 'Pengadaan Laptop',
                'tahun' => 2025,
                'metode' => 'E-Purchasing / E-Catalogue',
                'status' => 'Selesai',
            ],
            [
                'id' => 2,
                'judul' => 'Pengadaan ATK',
                'tahun' => 2024,
                'metode' => 'Pengadaan Langsung',
                'status' => 'Pelaksanaan',
            ],
        ];

        // Kamu bisa pakai $arsipList atau kalau blade kamu sudah pakai $arsip, ganti nama variabelnya.
        return view('Unit.ArsipPBJ', compact('unitName', 'arsipList'));
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
            ->route('unit.arsip.edit', $id)
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
