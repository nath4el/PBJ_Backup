<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function dashboard()
    {
        $unitName = auth()->user()->name ?? 'Unit Kerja';
        $unitId   = auth()->user()->unit_id;

        if (!$unitId) {
            abort(403, 'Akun unit belum terhubung ke unit_id.');
        }

        $tahunOptions = Pengadaan::where('unit_id', $unitId)
            ->whereNotNull('tahun')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->map(fn($t) => (int)$t)
            ->values()
            ->all();

        if (count($tahunOptions) === 0) {
            $y = (int)date('Y');
            $tahunOptions = [$y, $y - 1, $y - 2, $y - 3, $y - 4];
        }

        $defaultYear = $tahunOptions[0] ?? (int)date('Y');

        $totalArsip = Pengadaan::where('unit_id', $unitId)->count();
        $publik     = Pengadaan::where('unit_id', $unitId)->where('status_arsip', 'Publik')->count();
        $privat     = Pengadaan::where('unit_id', $unitId)->where('status_arsip', 'Privat')->count();

        $paketYear  = Pengadaan::where('unit_id', $unitId)->where('tahun', $defaultYear)->count();
        $nilaiYear  = (int) Pengadaan::where('unit_id', $unitId)->where('tahun', $defaultYear)->sum('nilai_kontrak');

        $summary = [
            ["label"=>"Total Arsip", "value"=>$totalArsip, "accent"=>"navy", "icon"=>"bi-file-earmark-text"],
            ["label"=>"Arsip Publik", "value"=>$publik, "accent"=>"yellow", "icon"=>"bi-eye"],
            ["label"=>"Arsip Private", "value"=>$privat, "accent"=>"gray", "icon"=>"bi-eye-slash"],
            ["label"=>"Total Arsip Pengadaan", "value"=>$paketYear, "accent"=>"navy", "icon"=>"bi-file-earmark-text", "sub"=>"Paket Pengadaan Barang dan Jasa"],
            ["label"=>"Total Nilai Pengadaan", "value"=>$this->formatRupiahNumber($nilaiYear), "accent"=>"yellow", "icon"=>"bi-buildings", "sub"=>"Nilai Kontrak Pengadaan"],
        ];

        $statusLabels = ["Perencanaan","Pemilihan","Pelaksanaan","Selesai"];
        $statusValues = $this->countByStatusPekerjaan($unitId, null, $statusLabels);

        $barLabels = [
            "Pengadaan\nLangsung",
            "Penunjukan\nLangsung",
            "E-Purchasing /\nE-Catalog",
            "Tender\nTerbatas",
            "Tender\nTerbuka",
            "Swakelola"
        ];
        $barValues = $this->countByMetodePengadaan($unitId, null, $barLabels);

        return view('Unit.Dashboard', compact(
            'unitName',
            'summary',
            'tahunOptions',
            'statusLabels',
            'statusValues',
            'barLabels',
            'barValues',
            'defaultYear'
        ));
    }

    public function dashboardStats(Request $request)
    {
        $unitId = auth()->user()->unit_id;
        if (!$unitId) {
            return response()->json(['message' => 'Akun unit belum terhubung ke unit_id.'], 403);
        }

        $tahun = $request->query('tahun');
        $tahun = ($tahun === null || $tahun === '') ? null : (int)$tahun;

        $paket = Pengadaan::where('unit_id', $unitId)
            ->when($tahun !== null, fn($q) => $q->where('tahun', $tahun))
            ->count();

        $nilai = (int) Pengadaan::where('unit_id', $unitId)
            ->when($tahun !== null, fn($q) => $q->where('tahun', $tahun))
            ->sum('nilai_kontrak');

        $statusLabels = ["Perencanaan","Pemilihan","Pelaksanaan","Selesai"];
        $statusValues = $this->countByStatusPekerjaan($unitId, $tahun, $statusLabels);

        $barLabels = [
            "Pengadaan\nLangsung",
            "Penunjukan\nLangsung",
            "E-Purchasing /\nE-Catalog",
            "Tender\nTerbatas",
            "Tender\nTerbuka",
            "Swakelola"
        ];
        $barValues = $this->countByMetodePengadaan($unitId, $tahun, $barLabels);

        return response()->json([
            'tahun' => $tahun,
            'paket' => ['count' => $paket],
            'nilai' => ['sum' => $nilai, 'formatted' => $this->formatRupiahNumber($nilai)],
            'status' => ['labels' => $statusLabels, 'values' => $statusValues],
            'metode' => ['labels' => $barLabels, 'values' => $barValues],
        ]);
    }

    public function dashboardData(Request $request)
    {
        return $this->dashboardStats($request);
    }

    private function countByStatusPekerjaan(int $unitId, ?int $tahun, array $labels): array
    {
        $rows = Pengadaan::where('unit_id', $unitId)
            ->when($tahun !== null, fn($q) => $q->where('tahun', $tahun))
            ->select('status_pekerjaan', DB::raw('COUNT(*) as cnt'))
            ->groupBy('status_pekerjaan')
            ->pluck('cnt', 'status_pekerjaan')
            ->toArray();

        return array_map(fn($lbl) => (int)($rows[$lbl] ?? 0), $labels);
    }

    private function countByMetodePengadaan(int $unitId, ?int $tahun, array $labels): array
    {
        $map = [
            "Pengadaan\nLangsung"          => ["Pengadaan Langsung", "Pengadaan\nLangsung"],
            "Penunjukan\nLangsung"        => ["Penunjukan Langsung", "Penunjukan\nLangsung"],
            "E-Purchasing /\nE-Catalog"   => ["E-Purchasing / E-Catalog", "E-Purchasing/E-Catalog", "E-Purchasing", "E-Catalog", "E-Catalogue"],
            "Tender\nTerbatas"            => ["Tender Terbatas", "Tender\nTerbatas"],
            "Tender\nTerbuka"             => ["Tender Terbuka", "Tender\nTerbuka", "Tender"],
            "Swakelola"                   => ["Swakelola"],
        ];

        $raw = Pengadaan::where('unit_id', $unitId)
            ->when($tahun !== null, fn($q) => $q->where('tahun', $tahun))
            ->select('jenis_pengadaan', DB::raw('COUNT(*) as cnt'))
            ->groupBy('jenis_pengadaan')
            ->pluck('cnt', 'jenis_pengadaan')
            ->toArray();

        $out = [];
        foreach ($labels as $lbl) {
            $alts = $map[$lbl] ?? [$lbl];
            $sum = 0;
            foreach ($alts as $k) $sum += (int)($raw[$k] ?? 0);
            $out[] = $sum;
        }
        return $out;
    }

    private function formatRupiahNumber($value): string
    {
        $num = (int)($value ?? 0);
        return 'Rp ' . number_format($num, 0, ',', '.');
    }

    public function arsipIndex(Request $request)
    {
        $unitName = auth()->user()->name ?? 'Unit Kerja';
        $unitId   = auth()->user()->unit_id;

        if (!$unitId) {
            abort(403, 'Akun unit belum terhubung ke unit_id.');
        }

        $arsips = Pengadaan::with('unit')
            ->where('unit_id', $unitId)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $mapped = $arsips->getCollection()->map(function (Pengadaan $p) {
            return [
                'id' => $p->id,
                'pekerjaan' => ($p->nama_pekerjaan ?? '-'),

                'id_rup' => $p->id_rup ?? '-',
                'tahun' => $p->tahun ?? null,
                'metode_pbj' => $p->jenis_pengadaan ?? '-',
                'jenis_pengadaan' => $p->jenis_pengadaan ?? '-',
                'status_pekerjaan' => $p->status_pekerjaan ?? '-',
                'status_arsip' => $p->status_arsip ?? '-',

                'nilai_kontrak' => $this->formatRupiah($p->nilai_kontrak),
                'pagu_anggaran' => $this->formatRupiah($p->pagu_anggaran),
                'hps' => $this->formatRupiah($p->hps),

                'nama_rekanan' => $p->nama_rekanan ?? '-',
                'unit' => $p->unit?->nama ?? '-',

                // ✅ penting: setiap item dokumen punya url route('unit.arsip.dokumen.show', ...)
                'dokumen' => $this->buildDokumenList($p),

                'dokumen_tidak_dipersyaratkan' => $this->normalizeArray($p->dokumen_tidak_dipersyaratkan),
            ];
        });

        $arsips->setCollection($mapped);

        return view('Unit.ArsipPBJ', compact('unitName', 'arsips'));
    }

    public function arsipEdit($id)
    {
        $unitName = auth()->user()->name ?? 'Unit Kerja';
        $unitId   = auth()->user()->unit_id;

        if (!$unitId) {
            abort(403, 'Akun unit belum terhubung ke unit_id.');
        }

        $pengadaan = Pengadaan::where('id', $id)
            ->where('unit_id', $unitId)
            ->firstOrFail();

        $arsip = (object)[
            'id' => $pengadaan->id,
            'judul' => $pengadaan->nama_pekerjaan ?? '-',
            'tahun' => $pengadaan->tahun ?? (int)date('Y'),
            'metode' => $pengadaan->jenis_pengadaan ?? '-',
            'status' => $pengadaan->status_pekerjaan ?? '-',
        ];

        return view('Unit.EditArsip', compact('unitName', 'arsip'));
    }

    public function arsipUpdate(Request $request, $id)
    {
        $request->validate([
            'judul'  => 'nullable|string|max:255',
            'tahun'  => 'nullable|integer|min:2000|max:' . (date('Y') + 5),
            'metode' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        $unitId = auth()->user()->unit_id;
        if (!$unitId) {
            abort(403, 'Akun unit belum terhubung ke unit_id.');
        }

        $pengadaan = Pengadaan::where('id', $id)
            ->where('unit_id', $unitId)
            ->firstOrFail();

        if ($request->filled('judul'))  $pengadaan->nama_pekerjaan = $request->judul;
        if ($request->filled('tahun'))  $pengadaan->tahun = (int)$request->tahun;
        if ($request->filled('metode')) $pengadaan->jenis_pengadaan = $request->metode;
        if ($request->filled('status')) $pengadaan->status_pekerjaan = $request->status;

        $pengadaan->save();

        return redirect()
            ->route('unit.arsip')
            ->with('success', 'Arsip berhasil diperbarui.');
    }

    public function arsipDestroy(Request $request, $id)
    {
        $unitId = auth()->user()->unit_id;
        if (!$unitId) {
            return response()->json(['message' => 'Akun unit belum terhubung ke unit_id.'], 403);
        }

        $pengadaan = Pengadaan::where('id', $id)
            ->where('unit_id', $unitId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            Storage::disk('public')->deleteDirectory("pengadaan/{$pengadaan->id}");
            $pengadaan->delete();

            DB::commit();
            return response()->json([
                'message' => 'Arsip berhasil dihapus.',
                'deleted_ids' => [(string)$id],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus arsip.'], 500);
        }
    }

    public function arsipBulkDestroy(Request $request)
    {
        $unitId = auth()->user()->unit_id;
        if (!$unitId) {
            return response()->json(['message' => 'Akun unit belum terhubung ke unit_id.'], 403);
        }

        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $ids = array_values(array_unique($data['ids']));

        $items = Pengadaan::where('unit_id', $unitId)
            ->whereIn('id', $ids)
            ->get();

        if ($items->count() === 0) {
            return response()->json(['message' => 'Tidak ada data yang bisa dihapus.'], 404);
        }

        DB::beginTransaction();
        try {
            $deleted = [];
            foreach ($items as $p) {
                Storage::disk('public')->deleteDirectory("pengadaan/{$p->id}");
                $deleted[] = (string)$p->id;
            }

            Pengadaan::where('unit_id', $unitId)
                ->whereIn('id', $items->pluck('id')->all())
                ->delete();

            DB::commit();

            return response()->json([
                'message' => 'Arsip terpilih berhasil dihapus.',
                'deleted_ids' => $deleted,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus arsip terpilih.'], 500);
        }
    }

    public function pengadaanCreate()
    {
        $unitName = auth()->user()->name ?? 'Unit Kerja';
        $unitId = auth()->user()->unit_id;

        $selectedUnitId = $unitId;
        $selectedUnitName = $unitId ? Unit::find($unitId)?->nama : null;

        $units = Unit::orderBy('nama')->get();

        return view('Unit.TambahPengadaan', compact(
            'unitName',
            'units',
            'selectedUnitId',
            'selectedUnitName'
        ));
    }

    public function pengadaanStore(Request $request)
    {
        $unitId = auth()->user()->unit_id;
        if (!$unitId) {
            abort(403, 'Akun unit belum terhubung ke unit_id.');
        }

        $data = $request->validate([
            'unit_id' => 'nullable|integer',

            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'nama_pekerjaan' => 'nullable|string|max:255',
            'id_rup' => 'nullable|string|max:255',
            'jenis_pengadaan' => 'required|string|max:100',
            'status_pekerjaan' => 'required|string|max:100',
            'status_arsip' => 'required|in:Publik,Privat',

            'pagu_anggaran' => 'nullable|string|max:50',
            'hps' => 'nullable|string|max:50',
            'nilai_kontrak' => 'nullable|string|max:50',
            'nama_rekanan' => 'nullable|string|max:255',

            'dokumen_tidak_dipersyaratkan' => 'nullable|array',
            'dokumen_tidak_dipersyaratkan_json' => 'nullable|string',
        ]);

        $toInt = function ($v) {
            if ($v === null) return null;
            $num = preg_replace('/[^0-9]/', '', (string)$v);
            return $num === '' ? null : (int)$num;
        };

        $data['pagu_anggaran'] = $toInt($data['pagu_anggaran'] ?? null);
        $data['hps'] = $toInt($data['hps'] ?? null);
        $data['nilai_kontrak'] = $toInt($data['nilai_kontrak'] ?? null);

        $docTidak = [];
        if (!empty($data['dokumen_tidak_dipersyaratkan']) && is_array($data['dokumen_tidak_dipersyaratkan'])) {
            $docTidak = $data['dokumen_tidak_dipersyaratkan'];
        } elseif (!empty($data['dokumen_tidak_dipersyaratkan_json'])) {
            $decoded = json_decode($data['dokumen_tidak_dipersyaratkan_json'], true);
            if (is_array($decoded)) $docTidak = $decoded;
        }
        $data['dokumen_tidak_dipersyaratkan'] = array_values($docTidak);
        unset($data['dokumen_tidak_dipersyaratkan_json']);

        $data['unit_id'] = (int)$unitId;
        $data['created_by'] = auth()->id();

        $pengadaan = null;

        try {
            $pengadaan = Pengadaan::create($data);

            $fileFields = array_keys($this->dokumenFieldLabels());

            foreach ($fileFields as $field) {
                if (!$request->hasFile($field)) continue;

                $uploaded = $request->file($field);
                $files = is_array($uploaded) ? $uploaded : [$uploaded];

                $paths = [];

                foreach ($files as $file) {
                    if (!$file || !$file->isValid()) continue;

                    $original = $file->getClientOriginalName();
                    $ext = strtolower($file->getClientOriginalExtension());
                    $base = pathinfo($original, PATHINFO_FILENAME);

                    $safeBase = Str::slug($base);
                    if ($safeBase === '') $safeBase = 'dokumen';

                    $filename = $safeBase . '_' . date('Ymd_His') . '_' . Str::random(6) . '.' . $ext;

                    $stored = $file->storeAs("pengadaan/{$pengadaan->id}/{$field}", $filename, 'public');
                    if ($stored) $paths[] = $stored;
                }

                if (count($paths) > 0) {
                    $pengadaan->{$field} = $paths;
                }
            }

            $pengadaan->save();

            return redirect()
                ->route('unit.arsip')
                ->with('success', 'Pengadaan berhasil disimpan.');
        } catch (\Throwable $e) {
            if ($pengadaan instanceof Pengadaan) {
                try { Storage::disk('public')->deleteDirectory("pengadaan/{$pengadaan->id}"); } catch (\Throwable $ex) {}
                try { $pengadaan->delete(); } catch (\Throwable $ex) {}
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['upload' => 'Gagal menyimpan pengadaan/dokumen. Silakan coba lagi.']);
        }
    }

    /**
     * ✅ LIHAT FILE (INLINE) - BUKAN DOWNLOAD
     * Route: /unit/arsip/{id}/dokumen/{field}/{file}
     */
    public function showDokumen($id, $field, $file)
    {
        $unitId = auth()->user()->unit_id;
        if (!$unitId) abort(403, 'Akun unit belum terhubung ke unit_id.');

        $allowed = $this->dokumenFieldLabels();
        if (!array_key_exists($field, $allowed)) abort(404);

        $pengadaan = Pengadaan::where('id', $id)->where('unit_id', $unitId)->firstOrFail();
        $arr = $this->normalizeArray($pengadaan->{$field});

        $matchPath = null;
        foreach ($arr as $p) {
            $p = ltrim((string)$p, '/');
            if (basename($p) === $file) { $matchPath = $p; break; }
        }

        if (!$matchPath || !Storage::disk('public')->exists($matchPath)) abort(404);

        /**
         * ✅ FIX UTAMA:
         * redirect ke file-viewer pakai /storage/... saja (bukan URL /unit/arsip/...),
         * supaya tidak double wrap /file-viewer?file=http://.../file-viewer...
         */
        $publicUrl = '/storage/' . ltrim($matchPath, '/');
        return redirect()->route('file.viewer', ['file' => $publicUrl]);
    }

    /**
     * ✅ FILE VIEWER (ANTI DOUBLE-WRAP + IZINKAN localhost/127.0.0.1)
     * Route: GET /file-viewer?file=...
     */
    public function fileViewer(Request $request)
    {
        $raw = (string)$request->query('file', '');

        // decode berulang (maks 2x) biar handle nested encoding
        $file = $raw;
        for ($i = 0; $i < 2; $i++) {
            $dec = urldecode($file);
            if ($dec === $file) break;
            $file = $dec;
        }

        // kalau ternyata file= berisi URL file-viewer lagi → ambil inner file=
        for ($i = 0; $i < 2; $i++) {
            $parts = parse_url($file);
            if (!is_array($parts)) break;

            $path = $parts['path'] ?? '';
            if (str_contains($path, 'file-viewer') && !empty($parts['query'])) {
                parse_str($parts['query'], $q);
                if (!empty($q['file'])) {
                    $file = (string)$q['file'];
                    continue;
                }
            }
            break;
        }

        // normalize lagi
        $file = trim($file);
        for ($i = 0; $i < 2; $i++) {
            $dec = urldecode($file);
            if ($dec === $file) break;
            $file = $dec;
        }

        // ✅ VALIDASI: hanya boleh /storage/... (atau absolute URL host sama & path /storage)
        $ok = false;
        $finalUrl = $file;

        if (str_starts_with($file, '/storage/')) {
            $ok = true;
            $finalUrl = $file;
        } else {
            $u = parse_url($file);
            if (is_array($u) && !empty($u['host'])) {
                $host = strtolower($u['host']);
                $curHost = strtolower($request->getHost());
                $path = $u['path'] ?? '';

                $allowedHosts = array_unique(array_filter([$curHost, 'localhost', '127.0.0.1']));
                if (in_array($host, $allowedHosts, true) && str_starts_with($path, '/storage/')) {
                    $ok = true;
                    $finalUrl = $path . (isset($u['query']) ? ('?' . $u['query']) : '');
                }
            }
        }

        if (!$ok) {
            abort(403, 'FILE TIDAK DIIZINKAN.');
        }

        // OPTIONAL: pastikan file fisik memang ada (mapping /storage/* -> disk public)
        $publicPath = ltrim($finalUrl, '/');                 // storage/...
        $diskPath   = preg_replace('#^storage/#', '', $publicPath); // pengadaan/...

        if (!$diskPath || !Storage::disk('public')->exists($diskPath)) {
            abort(404);
        }

        return view('Viewer.FileViewer', ['file' => $finalUrl]);
    }

    public function downloadDokumen($id, Request $request)
    {
        $unitId = auth()->user()->unit_id;
        if (!$unitId) abort(403, 'Akun unit belum terhubung ke unit_id.');

        $request->validate([
            'field' => 'required|string|max:100',
            'path'  => 'required|string',
        ]);

        $field = $request->query('field');
        $path  = ltrim($request->query('path'), '/');

        $allowed = $this->dokumenFieldLabels();
        if (!array_key_exists($field, $allowed)) abort(404);

        $pengadaan = Pengadaan::where('id', $id)->where('unit_id', $unitId)->firstOrFail();
        $arr = $this->normalizeArray($pengadaan->{$field});

        $ok = false;
        foreach ($arr as $p) {
            if (ltrim((string)$p, '/') === $path) { $ok = true; break; }
        }

        if (!$ok || !Storage::disk('public')->exists($path)) abort(404);

        return Storage::disk('public')->download($path, basename($path));
    }

    public function hapusDokumenFile(Request $request, $id)
    {
        $unitId = auth()->user()->unit_id;
        if (!$unitId) abort(403, 'Akun unit belum terhubung ke unit_id.');

        $request->validate([
            'field' => 'required|string|max:100',
            'path'  => 'required|string',
        ]);

        $pengadaan = Pengadaan::where('id', $id)->where('unit_id', $unitId)->firstOrFail();

        $field = $request->input('field');
        $path  = ltrim($request->input('path'), '/');

        $allowed = $this->dokumenFieldLabels();
        if (!array_key_exists($field, $allowed)) {
            return response()->json(['message' => 'Field dokumen tidak valid.'], 422);
        }

        $arr = $this->normalizeArray($pengadaan->{$field});
        $arr = array_values(array_filter($arr, fn($p) => (string)$p !== (string)$path));

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $pengadaan->{$field} = $arr;
        $pengadaan->save();

        return response()->json([
            'message' => 'File berhasil dihapus.',
            'field' => $field,
            'remaining' => $arr,
        ]);
    }

    public function kelolaAkun()
    {
        $unitName = auth()->user()->name ?? 'Unit Kerja';
        return view('Unit.KelolaAkun', compact('unitName'));
    }

    public function updateAkun(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->back()->withErrors(['auth' => 'Kamu belum login. Silakan login dulu.'])->withInput();
        }

        $wantsPasswordChange = $request->filled('password') || $request->filled('password_confirmation');

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required','email','max:255', Rule::unique('users', 'email')->ignore($user->id)],
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
                return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah.'])->withInput();
            }
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('unit.kelola.akun')->with('success', 'Akun berhasil diperbarui.');
    }

    private function buildDokumenList(Pengadaan $p): array
    {
        $labels = $this->dokumenFieldLabels();

        $out = [];
        foreach ($labels as $field => $label) {
            $files = $this->normalizeArray($p->{$field});
            if (count($files) === 0) continue;

            $items = [];
            foreach ($files as $path) {
                if (!$path) continue;

                $path = ltrim((string)$path, '/');
                if (!Storage::disk('public')->exists($path)) continue;

                $file = basename($path);

                $items[] = [
                    'label' => $label,
                    'name'  => $file,
                    'path'  => $path,
                    // ✅ URL PREVIEW (controller showDokumen -> redirect file.viewer)
                    'url'   => route('unit.arsip.dokumen.show', [
                        'id' => $p->id,
                        'field' => $field,
                        'file' => $file,
                    ]),
                ];
            }

            if (count($items)) $out[$field] = $items;
        }

        return $out;
    }

    private function dokumenFieldLabels(): array
    {
        return [
            'dokumen_kak' => 'Kerangka Acuan Kerja (KAK)',
            'dokumen_hps' => 'Harga Perkiraan Sendiri (HPS)',
            'dokumen_spesifikasi_teknis' => 'Spesifikasi Teknis',
            'dokumen_rancangan_kontrak' => 'Rancangan Kontrak',
            'dokumen_lembar_data_kualifikasi' => 'Lembar Data Kualifikasi',
            'dokumen_lembar_data_pemilihan' => 'Lembar Data Pemilihan',
            'dokumen_daftar_kuantitas_harga' => 'Daftar Kuantitas dan Harga',
            'dokumen_jadwal_lokasi_pekerjaan' => 'Jadwal & Lokasi Pekerjaan',
            'dokumen_gambar_rancangan_pekerjaan' => 'Gambar Rancangan Pekerjaan',
            'dokumen_amdal' => 'Dokumen AMDAL',
            'dokumen_penawaran' => 'Dokumen Penawaran',
            'surat_penawaran' => 'Surat Penawaran',
            'dokumen_kemenkumham' => 'Kemenkumham',
            'ba_pemberian_penjelasan' => 'BA Pemberian Penjelasan',
            'ba_pengumuman_negosiasi' => 'BA Pengumuman Negosiasi',
            'ba_sanggah_banding' => 'BA Sanggah / Sanggah Banding',
            'ba_penetapan' => 'BA Penetapan',
            'laporan_hasil_pemilihan' => 'Laporan Hasil Pemilihan',
            'dokumen_sppbj' => 'SPPBJ',
            'surat_perjanjian_kemitraan' => 'Perjanjian Kemitraan',
            'surat_perjanjian_swakelola' => 'Perjanjian Swakelola',
            'surat_penugasan_tim_swakelola' => 'Penugasan Tim Swakelola',
            'dokumen_mou' => 'MoU',
            'dokumen_kontrak' => 'Dokumen Kontrak',
            'ringkasan_kontrak' => 'Ringkasan Kontrak',
            'jaminan_pelaksanaan' => 'Jaminan Pelaksanaan',
            'jaminan_uang_muka' => 'Jaminan Uang Muka',
            'jaminan_pemeliharaan' => 'Jaminan Pemeliharaan',
            'surat_tagihan' => 'Surat Tagihan',
            'surat_pesanan_epurchasing' => 'Surat Pesanan E-Purchasing',
            'dokumen_spmk' => 'SPMK',
            'dokumen_sppd' => 'SPPD',
            'laporan_pelaksanaan_pekerjaan' => 'Laporan Hasil Pelaksanaan',
            'laporan_penyelesaian_pekerjaan' => 'Laporan Penyelesaian',
            'bap' => 'BAP',
            'bast_sementara' => 'BAST Sementara',
            'bast_akhir' => 'BAST Akhir',
            'dokumen_pendukung_lainya' => 'Dokumen Pendukung Lainnya',
        ];
    }

    private function normalizeArray($value): array
    {
        if ($value === null) return [];
        if (is_array($value)) {
            return array_values(array_filter($value, fn($v) => $v !== null && $v !== ''));
        }
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return array_values(array_filter($decoded, fn($v) => $v !== null && $v !== ''));
            }
            return $value !== '' ? [$value] : [];
        }
        return [];
    }

    private function formatRupiah($value): string
    {
        if ($value === null || $value === '') return '-';
        $num = (int)$value;
        return 'Rp ' . number_format($num, 0, ',', '.');
    }
}
