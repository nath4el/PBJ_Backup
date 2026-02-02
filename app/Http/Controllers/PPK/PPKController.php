<?php

namespace App\Http\Controllers\PPK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PpkController extends Controller
{
    public function dashboard()
    {
        $ppkName = "PPK Utama"; // opsional kalau dibutuhkan blade

        return view('PPK.Dashboard', compact('ppkName'));
    }

    public function arsipIndex()
    {
        $ppkName = "PPK Utama";

        $arsipList = [
            [
                'id' => 101,
                'judul' => 'Pengadaan Server',
                'tahun' => 2025,
                'metode' => 'Tender Terbuka',
                'status' => 'Pemilihan',
            ],
            [
                'id' => 102,
                'judul' => 'Pengadaan Jasa Konsultan',
                'tahun' => 2024,
                'metode' => 'Penunjukan Langsung',
                'status' => 'Pelaksanaan',
            ],
        ];

        return view('PPK.ArsipPBJ', compact('ppkName', 'arsipList'));
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
            ->route('ppk.arsip.edit', $id)
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
