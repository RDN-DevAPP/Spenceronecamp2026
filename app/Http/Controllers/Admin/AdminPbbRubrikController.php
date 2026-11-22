<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\ReguProfile;
use App\Models\Score;
use App\Models\ScoreDetail;
use App\Models\ScoringCriteria;
use App\Models\User;
use App\Services\QrCodeService;
use App\Services\ScoreAuditService;
use App\Support\PbbRubrikDefault;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPbbRubrikController extends Controller
{
    /**
     * Editor Lembar Penilaian PBB (matriks poin x level nilai).
     */
    public function index()
    {
        $lomba = PbbRubrikDefault::findLomba();
        if (!$lomba) {
            return redirect()->route('admin.informasi-lomba.index')
                ->with('error', 'Mata lomba PBB (LKBB) belum ada. Tambahkan dahulu di menu Kelola Mata Lomba.');
        }

        $groups = $this->groupedCriteria($lomba->id);
        $labels = PbbRubrikDefault::LEVEL_LABELS;
        $totalMax = $groups->flatten(1)->sum('nilai_max');

        return view('admin.pbb-rubrik.index', compact('lomba', 'groups', 'labels', 'totalMax'));
    }

    /**
     * Simpan seluruh lembar: ubah / tambah / hapus poin sekaligus.
     */
    public function update(Request $request)
    {
        $lomba = PbbRubrikDefault::findLomba();
        abort_unless($lomba, 404);

        $data = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.id' => ['nullable', 'integer'],
            'rows.*.kelompok' => ['required', 'string', 'max:255'],
            'rows.*.nama_kriteria' => ['required', 'string', 'max:255'],
            'rows.*.rubrik' => ['required', 'array', 'size:' . count(PbbRubrikDefault::LEVEL_LABELS)],
            'rows.*.rubrik.*' => ['required', 'numeric', 'min:0', 'max:1000'],
        ], [
            'rows.required' => 'Lembar penilaian tidak boleh kosong.',
            'rows.*.nama_kriteria.required' => 'Nama materi penilaian wajib diisi pada setiap baris.',
            'rows.*.kelompok.required' => 'Nama bagian wajib diisi.',
            'rows.*.rubrik.*.required' => 'Semua kolom nilai level wajib diisi (isi 0 bila tidak dipakai).',
            'rows.*.rubrik.*.numeric' => 'Nilai level harus berupa angka.',
        ]);

        $blocked = [];

        DB::transaction(function () use ($data, $lomba, &$blocked) {
            $existing = ScoringCriteria::where('mata_lomba_id', $lomba->id)->get()->keyBy('id');
            $keptIds = [];

            // Geser urutan lama agar tidak bentrok dengan unique (mata_lomba_id, urutan)
            ScoringCriteria::where('mata_lomba_id', $lomba->id)->increment('urutan', 100000);

            $urutan = 1;
            foreach ($data['rows'] as $row) {
                $rubrik = array_map(fn ($v) => $v == (int) $v ? (int) $v : (float) $v, array_values($row['rubrik']));
                $payload = [
                    'mata_lomba_id' => $lomba->id,
                    'nama_kriteria' => trim($row['nama_kriteria']),
                    'kelompok' => trim($row['kelompok']),
                    'rubrik' => $rubrik,
                    'nilai_min' => min($rubrik),
                    'nilai_max' => max($rubrik),
                    'urutan' => $urutan++,
                ];

                $id = $row['id'] ?? null;
                if ($id && $existing->has((int) $id)) {
                    $existing[(int) $id]->update($payload);
                    $keptIds[] = (int) $id;
                } else {
                    $keptIds[] = ScoringCriteria::create($payload)->id;
                }
            }

            // Hapus poin yang dibuang dari lembar (kecuali yang sudah punya nilai juri)
            foreach ($existing as $id => $criteria) {
                if (in_array($id, $keptIds, true)) {
                    continue;
                }
                if ($criteria->scoreDetails()->exists()) {
                    $blocked[] = $criteria->nama_kriteria;
                    $criteria->update(['urutan' => $urutan++]);
                    continue;
                }
                $criteria->delete();
            }

            // Selaraskan nilai maksimal lomba dengan total skor lembar
            $lomba->update(['nilai_maksimal' => ScoringCriteria::where('mata_lomba_id', $lomba->id)->sum('nilai_max')]);
        });

        $msg = 'Lembar Penilaian PBB berhasil disimpan.';
        if ($blocked) {
            return redirect()->route('admin.pbb-rubrik.index')
                ->with('success', $msg)
                ->with('error', 'Poin berikut tidak dapat dihapus karena sudah memiliki nilai juri: ' . implode(', ', $blocked));
        }

        return redirect()->route('admin.pbb-rubrik.index')->with('success', $msg);
    }

    /**
     * Muat ulang template bawaan (dokumen FORBASI). Hanya jika belum ada nilai juri.
     */
    public function loadDefault()
    {
        $lomba = PbbRubrikDefault::findLomba();
        abort_unless($lomba, 404);

        if (ScoringCriteria::where('mata_lomba_id', $lomba->id)->whereHas('scoreDetails')->exists()) {
            return redirect()->route('admin.pbb-rubrik.index')
                ->with('error', 'Template tidak dapat dimuat ulang karena sudah ada nilai juri yang tersimpan. Edit poin secara manual.');
        }

        DB::transaction(function () use ($lomba) {
            ScoringCriteria::where('mata_lomba_id', $lomba->id)->delete();

            foreach (PbbRubrikDefault::items() as $i => $item) {
                ScoringCriteria::create([
                    'mata_lomba_id' => $lomba->id,
                    'nama_kriteria' => $item['nama'],
                    'kelompok' => $item['kelompok'],
                    'rubrik' => $item['rubrik'],
                    'nilai_min' => min($item['rubrik']),
                    'nilai_max' => max($item['rubrik']),
                    'urutan' => $i + 1,
                ]);
            }

            $lomba->update(['nilai_maksimal' => ScoringCriteria::where('mata_lomba_id', $lomba->id)->sum('nilai_max')]);
        });

        return redirect()->route('admin.pbb-rubrik.index')->with('success', 'Template bawaan Lembar Penilaian PBB berhasil dimuat.');
    }

    /**
     * Lembar formulir penilaian PBB siap cetak (1 lembar untuk 1 peserta/regu dengan 4 kotak hitam di sudut & OMR grid).
     */
    public function print(Request $request)
    {
        $lomba = PbbRubrikDefault::findLomba();
        abort_unless($lomba, 404);

        $criteria = ScoringCriteria::where('mata_lomba_id', $lomba->id)
            ->orderBy('urutan')
            ->get();

        // 56 kriteria dibagi simetris 2 kolom (28 baris di kolom kiri, 28 baris di kolom kanan)
        $half = (int) ceil($criteria->count() / 2);
        $col1 = $criteria->slice(0, $half)->values();
        $col2 = $criteria->slice($half)->values();

        $regus = ReguProfile::with('user')->orderBy('nomor_regu')->get();
        $selectedReguId = $request->query('regu_id');

        $sheets = [];
        if ($request->has('all')) {
            // Cetak seluruh regu sekaligus (masing-masing 1 lembar)
            foreach ($regus as $regu) {
                $sheets[] = [
                    'regu' => $regu,
                    'qr' => QrCodeService::generateDataUri('PBB:REGU:' . $regu->id, 4),
                    'code' => 'PBB-REGU-' . $regu->id,
                ];
            }
        } elseif ($selectedReguId) {
            $regu = $regus->firstWhere('id', (int) $selectedReguId) ?? $regus->first();
            if ($regu) {
                $sheets[] = [
                    'regu' => $regu,
                    'qr' => QrCodeService::generateDataUri('PBB:REGU:' . $regu->id, 4),
                    'code' => 'PBB-REGU-' . $regu->id,
                ];
            }
        } else {
            // Lembar default
            if ($regus->isNotEmpty()) {
                $first = $regus->first();
                $sheets[] = [
                    'regu' => $first,
                    'qr' => QrCodeService::generateDataUri('PBB:REGU:' . $first->id, 4),
                    'code' => 'PBB-REGU-' . $first->id,
                ];
            } else {
                $sheets[] = [
                    'regu' => null,
                    'qr' => QrCodeService::generateDataUri('PBB:REGU:BLANK', 4),
                    'code' => 'PBB-REGU-BLANK',
                ];
            }
        }

        return view('pdf.lembar-pbb', [
            'lomba' => $lomba,
            'col1' => $col1,
            'col2' => $col2,
            'labels' => PbbRubrikDefault::LEVEL_LABELS,
            'regus' => $regus,
            'sheets' => $sheets,
            'selectedReguId' => $selectedReguId,
        ]);
    }

    /**
     * Halaman Scanner Lembar Penilaian PBB (Kamera / Unggah Foto & Pembacaan Nilai Otomatis).
     */
    public function scan(Request $request)
    {
        $lomba = PbbRubrikDefault::findLomba();
        abort_unless($lomba, 404);

        $criteria = ScoringCriteria::where('mata_lomba_id', $lomba->id)
            ->orderBy('urutan')
            ->get();

        $half = (int) ceil($criteria->count() / 2);
        $col1 = $criteria->slice(0, $half)->values();
        $col2 = $criteria->slice($half)->values();

        $regus = ReguProfile::with('user')->orderBy('nomor_regu')->get();
        $juris = User::where('role', 'juri')->get();
        $selectedReguId = $request->query('regu_id');

        return view('admin.pbb-rubrik.scan', [
            'lomba' => $lomba,
            'criteria' => $criteria,
            'col1' => $col1,
            'col2' => $col2,
            'labels' => PbbRubrikDefault::LEVEL_LABELS,
            'regus' => $regus,
            'juris' => $juris,
            'selectedReguId' => $selectedReguId,
        ]);
    }

    /**
     * Simpan nilai yang terbaca dari pemindaian lembar formulir ke database.
     */
    public function saveScannedScore(Request $request)
    {
        $validated = $request->validate([
            'regu_profile_id' => ['required', 'exists:regu_profiles,id'],
            'mata_lomba_id' => ['required', 'exists:mata_lomba,id'],
            'juri_id' => ['required', 'exists:users,id'],
            'scores' => ['required', 'array', 'min:1'],
            'scores.*.criteria_id' => ['required', 'exists:scoring_criteria,id'],
            'scores.*.nilai' => ['required', 'numeric', 'min:0'],
            'scanned_image' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $regu = ReguProfile::findOrFail($validated['regu_profile_id']);
        $mataLomba = MataLomba::findOrFail($validated['mata_lomba_id']);
        $juri = User::findOrFail($validated['juri_id']);

        $existingScore = Score::where('regu_profile_id', $regu->id)
            ->where('mata_lomba_id', $mataLomba->id)
            ->where('juri_id', $juri->id)
            ->first();

        if ($existingScore && $existingScore->isLocked()) {
            return response()->json([
                'success' => false,
                'message' => 'Nilai untuk regu ini telah dikunci/diverifikasi dan tidak dapat ditimpa.',
            ], 422);
        }

        // Simpan file bukti scan jika ada
        $imagePath = null;
        if (!empty($validated['scanned_image']) && str_starts_with($validated['scanned_image'], 'data:image')) {
            try {
                $dir = storage_path('app/public/scans/pbb');
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                $parts = explode(',', $validated['scanned_image']);
                $decoded = base64_decode(end($parts));
                $filename = 'pbb-scan-regu-' . $regu->id . '-juri-' . $juri->id . '-' . time() . '.jpg';
                file_put_contents($dir . '/' . $filename, $decoded);
                $imagePath = 'scans/pbb/' . $filename;
            } catch (\Throwable $e) {
                // Ignore failure
            }
        }

        $totalNilai = collect($validated['scores'])->sum('nilai');
        $oldScoreVal = $existingScore?->nilai;
        $oldStatusVal = $existingScore?->status ?? Score::STATUS_DRAFT;

        $score = DB::transaction(function () use ($validated, $regu, $mataLomba, $juri, $totalNilai, $existingScore, $oldScoreVal, $oldStatusVal, $request, $imagePath) {
            $catatanText = trim(($validated['catatan'] ?? '') . ($imagePath ? " [Bukti Scan: {$imagePath}]" : ''));
            if (empty($catatanText)) {
                $catatanText = 'Diinput melalui OMR Scanner Lembar Penilaian PBB';
            }

            $score = Score::updateOrCreate(
                [
                    'regu_profile_id' => $regu->id,
                    'mata_lomba_id' => $mataLomba->id,
                    'juri_id' => $juri->id,
                ],
                [
                    'season_id' => $regu->season_id ?? 1,
                    'nilai' => $totalNilai,
                    'status' => Score::STATUS_DRAFT,
                    'catatan' => $catatanText,
                ]
            );

            // Simpan detail nilai kriteria
            $score->scoreDetails()->delete();
            foreach ($validated['scores'] as $item) {
                ScoreDetail::create([
                    'score_id' => $score->id,
                    'scoring_criteria_id' => $item['criteria_id'],
                    'nilai' => $item['nilai'],
                ]);
            }

            // Catat ke log audit
            ScoreAuditService::log(
                $score,
                'omr_scanned_sheet_saved',
                $oldScoreVal,
                $totalNilai,
                $oldStatusVal,
                Score::STATUS_DRAFT,
                'Nilai dipindai otomatis dari formulir cetak OMR untuk ' . $regu->nama_regu,
                $request
            );

            return $score;
        });

        return response()->json([
            'success' => true,
            'message' => 'Nilai dari scan formulir untuk ' . $regu->nama_regu . ' berhasil disimpan!',
            'score_id' => $score->id,
            'total_nilai' => $totalNilai,
            'regu' => [
                'id' => $regu->id,
                'nama_regu' => $regu->nama_regu,
                'nomor_regu' => $regu->nomor_regu,
            ],
        ]);
    }

    private function groupedCriteria(int $lombaId)
    {
        return ScoringCriteria::where('mata_lomba_id', $lombaId)
            ->orderBy('urutan')
            ->get()
            ->groupBy(fn ($c) => $c->kelompok ?: 'Penilaian');
    }
}
