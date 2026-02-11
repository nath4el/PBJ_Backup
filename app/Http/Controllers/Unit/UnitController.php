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
use Illuminate\Support\Facades\Validator;

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

                // ✅ dokumen siap untuk modal detail (punya url showDokumen)
                'dokumen' => $this->buildDokumenList($p),

                // ✅ kolom E (array)
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

        $selectedUnitId = $unitId;
        $selectedUnitName = $unitId ? Unit::find($unitId)?->nama : null;
        $units = Unit::orderBy('nama')->get();

        $pengadaan->dokumen_tidak_dipersyaratkan = $this->normalizeArray($pengadaan->dokumen_tidak_dipersyaratkan);
        $dokumenExisting = $this->buildDokumenList($pengadaan);

        return view('Unit.EditArsip', compact(
            'unitName',
            'pengadaan',
            'units',
            'selectedUnitId',
            'selectedUnitName',
            'dokumenExisting'
        ));
    }

    public function arsipUpdate(Request $request, $id)
    {
        $unitId = auth()->user()->unit_id;
        if (!$unitId) {
            abort(403, 'Akun unit belum terhubung ke unit_id.');
        }

        $pengadaan = Pengadaan::where('id', $id)
            ->where('unit_id', $unitId)
            ->firstOrFail();

        // ✅ ambil & normalisasi input (biar A/B/C/E juga “nyambung” walau nama input beda)
        $payload = $this->normalizedPengadaanPayload($request, (int)$unitId);

        Validator::make($payload, $this->rulesPengadaan())->validate();

        DB::beginTransaction();
        try {
            // A–C–E (NON FILE)
            $pengadaan->unit_id          = (int)$unitId; // lock
            $pengadaan->tahun            = (int)$payload['tahun'];
            $pengadaan->nama_pekerjaan   = $payload['nama_pekerjaan'];
            $pengadaan->id_rup           = $payload['id_rup'];
            $pengadaan->jenis_pengadaan  = $payload['jenis_pengadaan'];
            $pengadaan->status_pekerjaan = $payload['status_pekerjaan'];
            $pengadaan->status_arsip     = $payload['status_arsip'];

            $pengadaan->pagu_anggaran    = $payload['pagu_anggaran'];
            $pengadaan->hps              = $payload['hps'];
            $pengadaan->nilai_kontrak    = $payload['nilai_kontrak'];
            $pengadaan->nama_rekanan     = $payload['nama_rekanan'];

            $pengadaan->dokumen_tidak_dipersyaratkan = $payload['dokumen_tidak_dipersyaratkan'];

            // D (FILE): append ke existing
            $this->handleUploadDokumenToModel($request, $pengadaan, true);

            $pengadaan->save();
            DB::commit();

            return redirect()
                ->route('unit.arsip')
                ->with('success', 'Arsip berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['update' => 'Gagal memperbarui arsip. Silakan coba lagi.']);
        }
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

        // ✅ ambil & normalisasi input (A/B/C/E)
        $payload = $this->normalizedPengadaanPayload($request, (int)$unitId);
        $payload['created_by'] = auth()->id();

        Validator::make($payload, $this->rulesPengadaan())->validate();

        DB::beginTransaction();
        try {
            $pengadaan = Pengadaan::create($payload);

            // D (FILE)
            $this->handleUploadDokumenToModel($request, $pengadaan, false);
            $pengadaan->save();

            DB::commit();

            return redirect()
                ->route('unit.arsip')
                ->with('success', 'Pengadaan berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            // cleanup folder bila sempat tercipta
            if (isset($pengadaan) && $pengadaan instanceof Pengadaan) {
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

        // ✅ redirect ke file-viewer pakai /storage/...
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

        $file = $raw;
        for ($i = 0; $i < 2; $i++) {
            $dec = urldecode($file);
            if ($dec === $file) break;
            $file = $dec;
        }

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

        $file = trim($file);
        for ($i = 0; $i < 2; $i++) {
            $dec = urldecode($file);
            if ($dec === $file) break;
            $file = $dec;
        }

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

    // =========================
    // HELPERS (PENTING)
    // =========================

    /**
     * ✅ Rules untuk A/B/C/E (bagian “selain D”)
     */
    private function rulesPengadaan(): array
    {
        return [
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'nama_pekerjaan' => 'nullable|string|max:255',
            'id_rup' => 'nullable|string|max:255',
            'jenis_pengadaan' => 'required|string|max:100',
            'status_pekerjaan' => 'required|string|max:100',
            'status_arsip' => 'required|in:Publik,Privat',

            'pagu_anggaran' => 'nullable|integer|min:0',
            'hps' => 'nullable|integer|min:0',
            'nilai_kontrak' => 'nullable|integer|min:0',
            'nama_rekanan' => 'nullable|string|max:255',

            'dokumen_tidak_dipersyaratkan' => 'nullable|array',
        ];
    }

    /**
     * ✅ Ini kunci biar A/B/C/E nyambung walau nama input di blade beda-beda.
     * Kamu boleh punya input: pekerjaan/judul/nama_pekerjaan, idrup/id_rup, pagu/pagu_anggaran, dll.
     */
    private function normalizedPengadaanPayload(Request $request, int $unitId): array
    {
        $pick = function(array $keys, $default = null) use ($request) {
            foreach ($keys as $k) {
                $v = $request->input($k);
                if ($v !== null && $v !== '') return $v;
            }
            return $default;
        };

        $toIntMoney = function($v) {
            if ($v === null) return null;
            $num = preg_replace('/[^0-9]/', '', (string)$v);
            return $num === '' ? null : (int)$num;
        };

        // Kolom E bisa datang dari: array checkbox/tag input atau hidden JSON
        $docTidak = [];
        $arrE = $request->input('dokumen_tidak_dipersyaratkan');
        if (is_array($arrE)) {
            $docTidak = $arrE;
        } else {
            $jsonE = $request->input('dokumen_tidak_dipersyaratkan_json');
            if (is_string($jsonE) && trim($jsonE) !== '') {
                $decoded = json_decode($jsonE, true);
                if (is_array($decoded)) $docTidak = $decoded;
            } else {
                // fallback: kalau kamu punya textarea/field lain untuk note E
                $fallbackText = $pick(['doc_note','dokumen_e','kolom_e'], '');
                if (is_string($fallbackText) && trim($fallbackText) !== '') {
                    $docTidak = [trim($fallbackText)];
                }
            }
        }
        $docTidak = array_values(array_filter(array_map(function($x){
            $s = is_string($x) ? trim($x) : $x;
            return ($s === '' || $s === null) ? null : $s;
        }, $docTidak)));

        // Status arsip kadang dikirim "Private" di UI -> samakan ke "Privat"
        $statusArsip = (string)$pick(['status_arsip','akses','statusAkses'], 'Privat');
        if (strtolower($statusArsip) === 'private') $statusArsip = 'Privat';

        return [
            'unit_id' => (int)$unitId, // lock
            'tahun' => (int)$pick(['tahun','year'], (int)date('Y')),

            // A
            'nama_pekerjaan' => $pick(['nama_pekerjaan','pekerjaan','judul','namaPekerjaan'], null),
            'id_rup' => $pick(['id_rup','idrup','id_rup_paket','idRup'], null),
            'jenis_pengadaan' => $pick(['jenis_pengadaan','metode_pbj','metode','jenis_pbj'], null),
            'status_pekerjaan' => $pick(['status_pekerjaan','status','statusPekerjaan'], null),

            // B
            'status_arsip' => $statusArsip,

            // C
            'pagu_anggaran' => $toIntMoney($pick(['pagu_anggaran','pagu'], null)),
            'hps' => $toIntMoney($pick(['hps'], null)),
            'nilai_kontrak' => $toIntMoney($pick(['nilai_kontrak','kontrak','nilai'], null)),
            'nama_rekanan' => $pick(['nama_rekanan','rekanan'], null),

            // E
            'dokumen_tidak_dipersyaratkan' => $docTidak,
        ];
    }

    /**
     * Upload dokumen (D) ke field json array pada model.
     * $append=true untuk arsipUpdate (gabung dengan existing), false untuk store (replace).
     */
    private function handleUploadDokumenToModel(Request $request, Pengadaan $pengadaan, bool $append = true): void
    {
        $fileFields = array_keys($this->dokumenFieldLabels());

        // (opsional tapi bagus) validasi file
        // note: kalau kamu pakai multiple, name="dokumen_kak[]" dsb, Laravel tetap detect array
        foreach ($fileFields as $field) {
            if (!$request->hasFile($field)) continue;

            $uploaded = $request->file($field);
            $files = is_array($uploaded) ? $uploaded : [$uploaded];

            $paths = $append ? $this->normalizeArray($pengadaan->{$field}) : [];

            foreach ($files as $file) {
                if (!$file || !$file->isValid()) continue;

                $ext = strtolower($file->getClientOriginalExtension());
                $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $safeBase = Str::slug($base);
                if ($safeBase === '') $safeBase = 'dokumen';

                $filename = $safeBase . '_' . date('Ymd_His') . '_' . Str::random(6) . '.' . $ext;

                $stored = $file->storeAs("pengadaan/{$pengadaan->id}/{$field}", $filename, 'public');
                if ($stored) $paths[] = $stored;
            }

            $pengadaan->{$field} = array_values($paths);
        }
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
