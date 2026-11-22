<?php

namespace App\Http\Controllers\Admin;

use App\Events\CoinRoyaleUpdated;
use App\Http\Controllers\Controller;
use App\Models\CerdasCermatQuestion;
use App\Models\CerdasCermatSession;
use App\Models\CerdasCermatSetting;
use App\Models\MataLomba;
use App\Models\QuizCipherGrid;
use App\Models\QuizCipherWord;
use App\Models\QuizCoinRoyaleBet;
use App\Models\QuizCoinRoyaleRound;
use App\Models\QuizQrAttempt;
use App\Models\QuizQrQuestion;
use App\Services\CipherGridGeneratorService;
use App\Services\CyberQuizService;
use App\Services\QrCodeService;
use App\Services\SandiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminCerdasCermatController extends Controller
{
    public function index(Request $request, CyberQuizService $quizService)
    {
        $activeTab = $request->query('tab', 'qr-hunt');

        // Legacy questions (kept for backward compatibility)
        $legacyTabs = ['Pilihan Ganda', 'Isian Singkat', 'Uraian'];
        $legacyTab = in_array($activeTab, $legacyTabs, true) ? $activeTab : 'Pilihan Ganda';
        $questions = CerdasCermatQuestion::where('type', $legacyTab)->latest()->get();

        $settings = [
            'round_1_duration' => (int) CerdasCermatSetting::getValue('round_1_duration', 60),
            'round_2_duration' => (int) CerdasCermatSetting::getValue('round_2_duration', 35),
            'round_3_duration' => (int) CerdasCermatSetting::getValue('round_3_duration', 30),
            'min_anggota_regu' => (int) CerdasCermatSetting::getValue('min_anggota_regu', 1),
        ];

        $qrQuestions = QuizQrQuestion::with('claimedByRegu')
            ->orderBy('id')
            ->get()
            ->map(function (QuizQrQuestion $q) {
                return [
                    'id' => $q->id,
                    'code_label' => $q->code_label,
                    'qr_token' => $q->qr_token,
                    'question' => $q->question,
                    'image_url' => $q->image_path ? asset('storage/' . $q->image_path) : null,
                    'accepted_answers' => $q->accepted_answers ?? [],
                    'points' => $q->points,
                    'status' => $q->status,
                    'claimed_by' => $q->claimedByRegu?->nama_regu,
                    'claimed_answer' => $q->claimed_answer,
                    'claimed_at' => $q->claimed_at?->toIso8601String(),
                    'qr_svg_uri' => QrCodeService::generateDataUri($q->qr_token, 4),
                ];
            });

        $cipherGrids = QuizCipherGrid::with(['words.claimedByRegu'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (QuizCipherGrid $g) {
                return [
                    'id' => $g->id,
                    'title' => $g->title,
                    'cipher_type' => $g->cipher_type,
                    'grid_size' => $g->grid_size,
                    'grid_matrix' => $g->grid_matrix,
                    'word_placements' => $g->word_placements,
                    'default_points' => $g->default_points,
                    'is_active' => $g->is_active,
                    'words' => $g->words->map(fn(QuizCipherWord $w) => [
                        'id' => $w->id,
                        'word' => $w->word,
                        'hint' => $w->hint,
                        'points' => $w->points,
                        'is_claimed' => $w->is_claimed,
                        'claimed_by' => $w->claimedByRegu?->nama_regu,
                        'claimed_at' => $w->claimed_at?->toIso8601String(),
                    ])->values(),
                ];
            });

        QuizCoinRoyaleRound::ensureNineRoundsExist();
        $coinRoyaleRounds = QuizCoinRoyaleRound::orderBy('round_number')->get();

        $roundStatuses = $quizService->getRoundStatuses();

        return view('admin.cerdas-cermat.index', compact(
            'questions',
            'activeTab',
            'settings',
            'qrQuestions',
            'cipherGrids',
            'coinRoyaleRounds',
            'roundStatuses'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // BABAK 1: QR-HUNT TRIVIA CRUD & PRINT PDF
    // ──────────────────────────────────────────────────────────────────────────

    public function storeQrQuestion(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code_label' => 'nullable|string|max:50',
            'question' => 'required|string',
            'accepted_answers' => 'required',
            'points' => 'required|integer|min:1|max:10000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $answers = $this->parseAcceptedAnswers($validated['accepted_answers']);
        if (empty($answers)) {
            return response()->json(['success' => false, 'message' => 'Minimal satu variasi kunci jawaban harus diisi.'], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('cerdas-cermat/qr', 'public');
        }

        $mataLomba = MataLomba::where('slug', 'cerdas-cermat')->first();

        $qr = QuizQrQuestion::create([
            'mata_lomba_id' => $mataLomba?->id,
            'code_label' => !empty($validated['code_label']) ? $validated['code_label'] : null,
            'qr_token' => (string) Str::uuid(),
            'question' => $validated['question'],
            'image_path' => $imagePath,
            'accepted_answers' => $answers,
            'points' => (int) $validated['points'],
            'status' => 'available',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Soal QR-Hunt berhasil ditambahkan.',
            'data' => $this->formatQrQuestion($qr),
        ], 201);
    }

    public function updateQrQuestion(Request $request, QuizQrQuestion $qrQuestion): JsonResponse
    {
        $validated = $request->validate([
            'code_label' => 'nullable|string|max:50',
            'question' => 'required|string',
            'accepted_answers' => 'required',
            'points' => 'required|integer|min:1|max:10000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $answers = $this->parseAcceptedAnswers($validated['accepted_answers']);
        if (empty($answers)) {
            return response()->json(['success' => false, 'message' => 'Minimal satu variasi kunci jawaban harus diisi.'], 422);
        }

        $imagePath = $qrQuestion->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('cerdas-cermat/qr', 'public');
        }

        $qrQuestion->update([
            'code_label' => !empty($validated['code_label']) ? $validated['code_label'] : $qrQuestion->code_label,
            'question' => $validated['question'],
            'image_path' => $imagePath,
            'accepted_answers' => $answers,
            'points' => (int) $validated['points'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Soal QR-Hunt berhasil diperbarui.',
            'data' => $this->formatQrQuestion($qrQuestion->fresh('claimedByRegu')),
        ]);
    }

    public function destroyQrQuestion(QuizQrQuestion $qrQuestion): JsonResponse
    {
        if ($qrQuestion->image_path && Storage::disk('public')->exists($qrQuestion->image_path)) {
            Storage::disk('public')->delete($qrQuestion->image_path);
        }
        $qrQuestion->delete();

        return response()->json(['success' => true, 'message' => 'Soal QR-Hunt berhasil dihapus.']);
    }

    public function resetQrQuestion(QuizQrQuestion $qrQuestion): JsonResponse
    {
        $qrQuestion->update([
            'status' => 'available',
            'claimed_by_regu_id' => null,
            'claimed_by_session_id' => null,
            'claimed_answer' => null,
            'claimed_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status soal QR berhasil direset menjadi Tersedia.',
            'data' => $this->formatQrQuestion($qrQuestion->fresh()),
        ]);
    }

    public function printQrPdf()
    {
        $qrQuestions = QuizQrQuestion::orderBy('id')->get()->map(function (QuizQrQuestion $q) {
            return [
                'id' => $q->id,
                'code_label' => $q->code_label,
                'qr_token' => $q->qr_token,
                'points' => $q->points,
                'question_preview' => Str::limit($q->question, 75),
                'qr_data_uri' => QrCodeService::generateDataUri($q->qr_token, 6),
            ];
        });

        $pdf = Pdf::loadView('pdf.quiz-qr-cards', [
            'qrQuestions' => $qrQuestions,
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('lembar-qr-hunt-spencerone-camp.pdf');
    }

    public function downloadQrTemplateCsv()
    {
        $headers = ['code_label', 'question', 'accepted_answers', 'points'];
        $rows = [
            ['QR-001', 'Siapakah Bapak Pandu Sedunia?', 'Baden Powell|Lord Baden Powell|Robert Baden Powell', 100],
            ['QR-002', 'Apa semboyan resmi Gerakan Pramuka Indonesia?', 'Satyaku Kudarmakan Darmaku Kubaktikan', 150],
            ['QR-003', 'Tanggal berapa Hari Pramuka Indonesia diperingati setiap tahun?', '14 Agustus|14-08|Empat Belas Agustus', 100],
        ];

        return $this->streamCsvDownload('template-babak1-qr-hunt.csv', $headers, $rows);
    }

    public function importQrCsv(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $rows = $this->parseCsvFile($request->file('file'));
        if (empty($rows)) {
            return response()->json(['success' => false, 'message' => 'File CSV kosong atau format tidak terbaca.'], 422);
        }

        $mataLomba = MataLomba::where('slug', 'cerdas-cermat')->first();
        $created = [];

        DB::transaction(function () use ($rows, $mataLomba, &$created) {
            foreach ($rows as $row) {
                $question = trim((string) ($row['question'] ?? $row['pertanyaan'] ?? $row[1] ?? ''));
                $answersRaw = trim((string) ($row['accepted_answers'] ?? $row['kunci_jawaban'] ?? $row['jawaban'] ?? $row[2] ?? ''));
                if ($question === '' || $answersRaw === '') {
                    continue;
                }

                $codeLabel = trim((string) ($row['code_label'] ?? $row['kode_pos'] ?? $row['kode'] ?? $row[0] ?? ''));
                $points = (int) ($row['points'] ?? $row['poin'] ?? $row[3] ?? 100);
                $points = max(1, $points);

                $answers = $this->parseAcceptedAnswers($answersRaw);
                if (empty($answers)) {
                    continue;
                }

                $qr = QuizQrQuestion::create([
                    'mata_lomba_id' => $mataLomba?->id,
                    'code_label' => $codeLabel !== '' ? $codeLabel : null,
                    'qr_token' => (string) Str::uuid(),
                    'question' => $question,
                    'accepted_answers' => $answers,
                    'points' => $points,
                    'status' => 'available',
                ]);

                $created[] = $this->formatQrQuestion($qr);
            }
        });

        if (empty($created)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada baris valid yang diimpor. Pastikan kolom: code_label, question, accepted_answers, points.',
            ], 422);
        }

        if ($mataLomba) {
            $this->syncNilaiMaksimal($mataLomba);
        }

        $allItems = QuizQrQuestion::with('claimedByRegu')
            ->orderBy('id')
            ->get()
            ->map(fn($q) => $this->formatQrQuestion($q))
            ->values();

        return response()->json([
            'success' => true,
            'message' => count($created) . ' soal QR-Hunt berhasil diimpor dari CSV.',
            'items' => $allItems,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // BABAK 2: CIPHER WORD-SEARCH DYNAMIC BUILDER & PRINT A4 PDF
    // ──────────────────────────────────────────────────────────────────────────

    public function previewCipherGrid(Request $request, CipherGridGeneratorService $generator): JsonResponse
    {
        $validated = $request->validate([
            'grid_size' => 'required|integer|min:12|max:20',
            'words' => 'required|array|min:1',
        ]);

        $rawWords = collect($validated['words'])
            ->map(fn($item) => is_array($item) ? ($item['word'] ?? '') : (string) $item)
            ->filter()
            ->values()
            ->all();

        try {
            $result = $generator->generate((int) $validated['grid_size'], $rawWords);
            return response()->json([
                'success' => true,
                'matrix' => $result['matrix'],
                'placements' => $result['placements'],
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function storeCipherGrid(Request $request, CipherGridGeneratorService $generator): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'cipher_type' => ['required', Rule::in(['kotak_1', 'kotak_2', 'angka'])],
            'grid_size' => 'required|integer|min:12|max:20',
            'default_points' => 'nullable|integer|min:1|max:5000',
            'words' => 'required|array|min:1',
            'words.*.word' => 'required|string|max:64',
            'words.*.hint' => 'nullable|string|max:255',
            'words.*.points' => 'nullable|integer|min:1|max:5000',
            'matrix' => 'nullable|array',
            'placements' => 'nullable|array',
        ]);

        $defaultPoints = (int) ($validated['default_points'] ?? 50);
        $cleanWords = [];
        foreach ($validated['words'] as $w) {
            $norm = strtoupper(preg_replace('/[^A-Za-z]/', '', (string) $w['word']));
            if ($norm === '') {
                continue;
            }
            $cleanWords[$norm] = [
                'word' => $norm,
                'hint' => $w['hint'] ?? null,
                'points' => (int) ($w['points'] ?? $defaultPoints),
            ];
        }

        if (empty($cleanWords)) {
            return response()->json(['success' => false, 'message' => 'Daftar kata target valid (A-Z) tidak boleh kosong.'], 422);
        }

        try {
            $generated = !empty($validated['matrix']) && !empty($validated['placements'])
                ? ['matrix' => $validated['matrix'], 'placements' => $validated['placements']]
                : $generator->generate((int) $validated['grid_size'], array_keys($cleanWords));
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        $title = trim((string) ($validated['title'] ?? ''));
        if ($title === '') {
            $title = SandiService::typeLabel($validated['cipher_type']) . ' #' . (QuizCipherGrid::count() + 1);
        }

        $grid = DB::transaction(function () use ($title, $validated, $defaultPoints, $generated, $cleanWords) {
            $grid = QuizCipherGrid::create([
                'title' => $title,
                'cipher_type' => $validated['cipher_type'],
                'grid_size' => (int) $validated['grid_size'],
                'grid_matrix' => $generated['matrix'],
                'word_placements' => $generated['placements'],
                'default_points' => $defaultPoints,
                'is_active' => true,
                'sort_order' => (QuizCipherGrid::max('sort_order') ?? 0) + 1,
            ]);

            foreach ($cleanWords as $item) {
                $grid->words()->create($item);
            }

            return $grid->load('words.claimedByRegu');
        });

        return response()->json([
            'success' => true,
            'message' => 'Kategori Cipher Word-Search berhasil dibuat.',
            'data' => $this->formatCipherGrid($grid),
        ], 201);
    }

    public function updateCipherGrid(Request $request, QuizCipherGrid $cipherGrid, CipherGridGeneratorService $generator): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'cipher_type' => ['required', Rule::in(['kotak_1', 'kotak_2', 'angka'])],
            'grid_size' => 'required|integer|min:12|max:20',
            'default_points' => 'nullable|integer|min:1|max:5000',
            'is_active' => 'nullable|boolean',
            'words' => 'required|array|min:1',
            'words.*.word' => 'required|string|max:64',
            'words.*.hint' => 'nullable|string|max:255',
            'words.*.points' => 'nullable|integer|min:1|max:5000',
            'regenerate' => 'nullable|boolean',
        ]);

        $defaultPoints = (int) ($validated['default_points'] ?? $cipherGrid->default_points);
        $cleanWords = [];
        foreach ($validated['words'] as $w) {
            $norm = strtoupper(preg_replace('/[^A-Za-z]/', '', (string) $w['word']));
            if ($norm === '') {
                continue;
            }
            $cleanWords[$norm] = [
                'word' => $norm,
                'hint' => $w['hint'] ?? null,
                'points' => (int) ($w['points'] ?? $defaultPoints),
            ];
        }

        if (empty($cleanWords)) {
            return response()->json(['success' => false, 'message' => 'Daftar kata target valid tidak boleh kosong.'], 422);
        }

        try {
            $generated = $generator->generate((int) $validated['grid_size'], array_keys($cleanWords));
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        $title = trim((string) ($validated['title'] ?? ''));
        if ($title === '') {
            $title = $cipherGrid->title ?: (SandiService::typeLabel($validated['cipher_type']) . ' #' . $cipherGrid->id);
        }

        DB::transaction(function () use ($cipherGrid, $title, $validated, $defaultPoints, $generated, $cleanWords) {
            $cipherGrid->update([
                'title' => $title,
                'cipher_type' => $validated['cipher_type'],
                'grid_size' => (int) $validated['grid_size'],
                'grid_matrix' => $generated['matrix'],
                'word_placements' => $generated['placements'],
                'default_points' => $defaultPoints,
                'is_active' => $validated['is_active'] ?? $cipherGrid->is_active,
            ]);

            $cipherGrid->words()->delete();
            foreach ($cleanWords as $item) {
                $cipherGrid->words()->create($item);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Kategori Cipher Word-Search berhasil diperbarui.',
            'data' => $this->formatCipherGrid($cipherGrid->fresh('words.claimedByRegu')),
        ]);
    }

    public function destroyCipherGrid(QuizCipherGrid $cipherGrid): JsonResponse
    {
        $cipherGrid->delete();
        return response()->json(['success' => true, 'message' => 'Kategori Cipher Word-Search berhasil dihapus.']);
    }

    public function resetCipherGridWords(QuizCipherGrid $cipherGrid): JsonResponse
    {
        $cipherGrid->words()->update([
            'is_claimed' => false,
            'claimed_by_regu_id' => null,
            'claimed_by_session_id' => null,
            'claimed_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua kata pada kategori ini telah direset menjadi Tersedia.',
            'data' => $this->formatCipherGrid($cipherGrid->fresh('words.claimedByRegu')),
        ]);
    }

    public function printCipherPdf(Request $request, ?QuizCipherGrid $cipherGrid = null)
    {
        $grids = $cipherGrid
            ? collect([$cipherGrid->load('words')])
            : QuizCipherGrid::with('words')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

        $withKey = $request->boolean('with_key', false);

        // Pre-compute PDF-safe Data URIs for A-Z for each grid type used
        $preparedGrids = $grids->map(function (QuizCipherGrid $g) {
            $symbolUris = [];
            foreach (range('A', 'Z') as $ch) {
                $symbolUris[$ch] = SandiService::getPdfDataUri($g->cipher_type, $ch, '#0F172A');
            }

            $highlightSet = [];
            foreach ($g->word_placements ?? [] as $p) {
                foreach ($p['coords'] ?? [] as $coord) {
                    $highlightSet[$coord[0] . '-' . $coord[1]] = true;
                }
            }

            return [
                'id' => $g->id,
                'title' => $g->title,
                'cipher_type' => $g->cipher_type,
                'cipher_label' => SandiService::typeLabel($g->cipher_type),
                'grid_size' => $g->grid_size,
                'matrix' => $g->grid_matrix,
                'words' => $g->words,
                'symbol_uris' => $symbolUris,
                'highlight_set' => $highlightSet,
            ];
        });

        $pdf = Pdf::loadView('pdf.quiz-cipher-sheet', [
            'grids' => $preparedGrids,
            'withKey' => $withKey,
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
        ])->setPaper('a4', 'portrait');

        $filename = $cipherGrid
            ? 'lembar-sandi-' . Str::slug($cipherGrid->title) . '.pdf'
            : 'lembar-sandi-word-search-a4.pdf';

        return $pdf->stream($filename);
    }

    public function downloadCipherTemplateCsv()
    {
        $headers = ['word', 'points'];
        $rows = [
            ['PRAMUKA', 50],
            ['TUNASKELAPA', 75],
            ['DASADARMA', 100],
            ['TRISATYA', 80],
            ['MORSE', 60],
        ];

        return $this->streamCsvDownload('template-babak2-kata-sandi.csv', $headers, $rows);
    }

    public function importCipherWordsCsv(Request $request, CipherGridGeneratorService $generator): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
            'cipher_grid_id' => 'nullable|integer|exists:quiz_cipher_grids,id',
            'default_points' => 'nullable|integer|min:1|max:5000',
        ]);

        $rows = $this->parseCsvFile($request->file('file'));
        if (empty($rows)) {
            return response()->json(['success' => false, 'message' => 'File CSV kata kosong atau tidak terbaca.'], 422);
        }

        $defaultPoints = (int) ($request->input('default_points', 50));
        $parsedWords = [];

        foreach ($rows as $row) {
            $rawWord = (string) ($row['word'] ?? $row['kata'] ?? $row[0] ?? '');
            $norm = strtoupper(preg_replace('/[^A-Za-z]/', '', $rawWord));
            if ($norm === '') {
                continue;
            }
            $ptsRaw = $row['points'] ?? $row['poin'] ?? (isset($row[1]) && is_numeric($row[1]) ? $row[1] : ($row[2] ?? $defaultPoints));
            $pts = (int) $ptsRaw;
            $parsedWords[$norm] = [
                'word' => $norm,
                'hint' => null,
                'points' => max(1, $pts > 0 ? $pts : $defaultPoints),
            ];
        }

        if (empty($parsedWords)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada kata A-Z valid di CSV. Gunakan kolom: word, points.',
            ], 422);
        }

        $wordsList = array_values($parsedWords);

        // If a specific existing grid ID is given, replace/update its words and regenerate matrix
        if ($request->filled('cipher_grid_id')) {
            $grid = QuizCipherGrid::findOrFail($request->input('cipher_grid_id'));
            try {
                $generated = $generator->generate((int) $grid->grid_size, array_column($wordsList, 'word'));
            } catch (\InvalidArgumentException $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            DB::transaction(function () use ($grid, $generated, $wordsList) {
                $grid->update([
                    'grid_matrix' => $generated['matrix'],
                    'word_placements' => $generated['placements'],
                ]);
                $grid->words()->delete();
                foreach ($wordsList as $item) {
                    $grid->words()->create($item);
                }
            });

            $mataLomba = MataLomba::where('slug', 'cerdas-cermat')->first();
            if ($mataLomba) {
                $this->syncNilaiMaksimal($mataLomba);
            }

            return response()->json([
                'success' => true,
                'message' => count($wordsList) . ' kata berhasil diimpor dan matriks diperbarui.',
                'data' => $this->formatCipherGrid($grid->fresh('words.claimedByRegu')),
                'words' => $wordsList,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => count($wordsList) . ' kata siap digunakan di Dynamic Grid Builder.',
            'words' => $wordsList,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // BABAK 3: COIN ROYALE ROUND QUESTIONS SETUP & CSV
    // ──────────────────────────────────────────────────────────────────────────

    public function downloadCoinRoyaleTemplateCsv()
    {
        $headers = ['round_number', 'question_text', 'correct_answer'];
        $rows = [];
        for ($i = 1; $i <= 9; $i++) {
            $typeLabel = in_array($i, [3, 6, 9], true) ? 'Spesial' : 'Normal';
            $rows[] = [
                $i,
                "Contoh pertanyaan Ronde {$i} ({$typeLabel}) — Ganti dengan soal resmi",
                "Kunci jawaban Ronde {$i}",
            ];
        }

        return $this->streamCsvDownload('template-babak3-coin-royale.csv', $headers, $rows);
    }

    public function importCoinRoyaleCsv(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $rows = $this->parseCsvFile($request->file('file'));
        if (empty($rows)) {
            return response()->json(['success' => false, 'message' => 'File CSV kosong atau tidak terbaca.'], 422);
        }

        QuizCoinRoyaleRound::ensureNineRoundsExist();
        $updatedCount = 0;

        foreach ($rows as $row) {
            $roundNum = (int) ($row['round_number'] ?? $row['ronde'] ?? $row[0] ?? 0);
            if ($roundNum < 1 || $roundNum > 9) {
                continue;
            }
            $questionText = trim((string) ($row['question_text'] ?? $row['pertanyaan'] ?? $row['soal'] ?? $row[1] ?? ''));
            $correctAnswer = trim((string) ($row['correct_answer'] ?? $row['kunci_jawaban'] ?? $row['jawaban'] ?? $row[2] ?? ''));

            QuizCoinRoyaleRound::updateOrCreate(
                ['round_number' => $roundNum],
                [
                    'is_special' => in_array($roundNum, [3, 6, 9], true),
                    'question_text' => $questionText !== '' ? $questionText : null,
                    'correct_answer' => $correctAnswer !== '' ? $correctAnswer : null,
                ]
            );
            $updatedCount++;
        }

        if ($updatedCount === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada baris valid (round_number 1-9) yang ditemukan di CSV.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} ronde Coin Royale berhasil diperbarui dari CSV.",
            'rounds' => QuizCoinRoyaleRound::orderBy('round_number')->get(),
        ]);
    }

    public function saveCoinRoyaleRounds(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rounds' => 'required|array|min:1',
            'rounds.*.round_number' => 'required|integer|min:1|max:9',
            'rounds.*.question_text' => 'nullable|string',
            'rounds.*.correct_answer' => 'nullable|string',
        ]);

        foreach ($validated['rounds'] as $item) {
            QuizCoinRoyaleRound::updateOrCreate(
                ['round_number' => (int) $item['round_number']],
                [
                    'is_special' => in_array((int) $item['round_number'], [3, 6, 9], true),
                    'question_text' => $item['question_text'] ?? null,
                    'correct_answer' => $item['correct_answer'] ?? null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Bank soal 9 Ronde Coin Royale berhasil disimpan.',
            'rounds' => QuizCoinRoyaleRound::orderBy('round_number')->get(),
        ]);
    }

    public function uploadCoinRoyaleImage(Request $request, int $roundNumber, CyberQuizService $quizService): JsonResponse
    {
        if ($roundNumber < 1 || $roundNumber > 9) {
            return response()->json(['success' => false, 'message' => 'Nomor ronde harus 1-9.'], 422);
        }

        $request->validate([
            'image' => 'required|image|max:4096',
        ]);

        QuizCoinRoyaleRound::ensureNineRoundsExist();
        $round = QuizCoinRoyaleRound::where('round_number', $roundNumber)->firstOrFail();

        if ($round->question_image_path) {
            Storage::disk('public')->delete($round->question_image_path);
        }

        $path = $request->file('image')->store('quiz-coin-royale', 'public');
        $round->update(['question_image_path' => $path]);

        if ($round->phase === QuizCoinRoyaleRound::PHASE_QUESTION_OPEN) {
            broadcast(new CoinRoyaleUpdated($quizService->getCoinRoyaleState(false)));
        }

        return response()->json([
            'success' => true,
            'message' => "Gambar soal Ronde {$roundNumber} berhasil diunggah.",
            'rounds' => QuizCoinRoyaleRound::orderBy('round_number')->get(),
        ]);
    }

    public function deleteCoinRoyaleImage(int $roundNumber, CyberQuizService $quizService): JsonResponse
    {
        if ($roundNumber < 1 || $roundNumber > 9) {
            return response()->json(['success' => false, 'message' => 'Nomor ronde harus 1-9.'], 422);
        }

        QuizCoinRoyaleRound::ensureNineRoundsExist();
        $round = QuizCoinRoyaleRound::where('round_number', $roundNumber)->firstOrFail();

        if ($round->question_image_path) {
            Storage::disk('public')->delete($round->question_image_path);
            $round->update(['question_image_path' => null]);

            if ($round->phase === QuizCoinRoyaleRound::PHASE_QUESTION_OPEN) {
                broadcast(new CoinRoyaleUpdated($quizService->getCoinRoyaleState(false)));
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Gambar soal Ronde {$roundNumber} berhasil dihapus.",
            'rounds' => QuizCoinRoyaleRound::orderBy('round_number')->get(),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // LEGACY CRUD & SESSION MANAGEMENT (Kept intact for existing tests & tools)
    // ──────────────────────────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.cerdas-cermat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['Pilihan Ganda', 'Isian Singkat', 'Uraian'])],
            'question' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'correct_answer' => 'required|string',
            'score' => 'required|integer|min:0',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('cerdas-cermat/soal', 'public');
        }

        $mataLomba = MataLomba::where('slug', 'cerdas-cermat')->firstOrFail();

        CerdasCermatQuestion::create([
            'mata_lomba_id' => $mataLomba->id,
            'type' => $validated['type'],
            'question' => $validated['question'],
            'image_path' => $imagePath,
            'correct_answer' => $validated['correct_answer'],
            'score' => $validated['score'],
            'options' => $validated['type'] === 'Pilihan Ganda' ? $validated['options'] : null,
        ]);

        $this->syncNilaiMaksimal($mataLomba);

        return redirect()->route('admin.cerdas-cermat.index', ['tab' => $validated['type']])
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(CerdasCermatQuestion $question)
    {
        return view('admin.cerdas-cermat.edit', compact('question'));
    }

    public function update(Request $request, CerdasCermatQuestion $question)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['Pilihan Ganda', 'Isian Singkat', 'Uraian'])],
            'question' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'correct_answer' => 'required|string',
            'score' => 'required|integer|min:0',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
        ]);

        $imagePath = $question->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('cerdas-cermat/soal', 'public');
        }

        $question->update([
            'type' => $validated['type'],
            'question' => $validated['question'],
            'image_path' => $imagePath,
            'correct_answer' => $validated['correct_answer'],
            'score' => $validated['score'],
            'options' => $validated['type'] === 'Pilihan Ganda' ? $validated['options'] : null,
        ]);

        $mataLomba = MataLomba::where('slug', 'cerdas-cermat')->firstOrFail();
        $this->syncNilaiMaksimal($mataLomba);

        return redirect()->route('admin.cerdas-cermat.index', ['tab' => $validated['type']])
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(CerdasCermatQuestion $question)
    {
        $type = $question->type;
        $question->delete();

        $mataLomba = MataLomba::where('slug', 'cerdas-cermat')->firstOrFail();
        $this->syncNilaiMaksimal($mataLomba);

        return redirect()->route('admin.cerdas-cermat.index', ['tab' => $type])
            ->with('success', 'Soal berhasil dihapus.');
    }

    public function destroyAll(Request $request)
    {
        $type = $request->input('type');

        if ($type) {
            CerdasCermatQuestion::where('type', $type)->delete();

            $mataLomba = MataLomba::where('slug', 'cerdas-cermat')->firstOrFail();
            $this->syncNilaiMaksimal($mataLomba);

            return redirect()->route('admin.cerdas-cermat.index', ['tab' => $type])
                ->with('success', "Semua soal $type berhasil dihapus.");
        }

        return back()->with('error', 'Tipe soal tidak valid.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'type' => ['required', Rule::in(['Pilihan Ganda', 'Isian Singkat', 'Uraian'])],
        ]);

        $file = $request->file('file');
        $type = $request->input('type');
        $mataLomba = MataLomba::where('slug', 'cerdas-cermat')->firstOrFail();

        DB::beginTransaction();
        try {
            if (($handle = fopen($file->getPathname(), 'r')) !== false) {
                fgetcsv($handle, 1000, ',');

                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (empty($row[1])) {
                        continue;
                    }

                    $questionText = $row[1];
                    $options = null;
                    $correctAnswer = '';
                    $score = 0;

                    if ($type === 'Pilihan Ganda') {
                        if (count($row) < 9) {
                            continue;
                        }
                        $options = [
                            'a' => $row[2] ?? '',
                            'b' => $row[3] ?? '',
                            'c' => $row[4] ?? '',
                            'd' => $row[5] ?? '',
                            'e' => $row[6] ?? '',
                        ];
                        $correctAnswer = $row[7] ?? '';
                        $score = (int) ($row[8] ?? 0);
                    } else {
                        if (count($row) < 4) {
                            continue;
                        }
                        $correctAnswer = $row[2] ?? '-';
                        $score = (int) ($row[3] ?? 0);
                    }

                    CerdasCermatQuestion::create([
                        'mata_lomba_id' => $mataLomba->id,
                        'type' => $type,
                        'question' => $questionText,
                        'correct_answer' => $correctAnswer,
                        'score' => $score,
                        'options' => $options,
                    ]);
                }
                fclose($handle);
            }
            DB::commit();

            $this->syncNilaiMaksimal($mataLomba);

            return redirect()->route('admin.cerdas-cermat.index', ['tab' => $type])
                ->with('success', 'Soal berhasil diimport (CSV).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function sessions(Request $request)
    {
        $sessions = CerdasCermatSession::with('reguProfile')->latest()->get();
        return view('admin.cerdas-cermat.sessions', compact('sessions'));
    }

    public function resetSession($id)
    {
        $session = CerdasCermatSession::findOrFail($id);

        \App\Models\ExamViolation::where('session_id', $session->id)->delete();
        $session->answers()->delete();
        QuizQrAttempt::where('session_id', $session->id)->delete();
        QuizQrQuestion::where('claimed_by_session_id', $session->id)->update([
            'status' => 'available',
            'claimed_by_regu_id' => null,
            'claimed_by_session_id' => null,
            'claimed_answer' => null,
            'claimed_at' => null,
        ]);
        QuizCipherWord::where('claimed_by_session_id', $session->id)->update([
            'is_claimed' => false,
            'claimed_by_regu_id' => null,
            'claimed_by_session_id' => null,
            'claimed_at' => null,
        ]);
        QuizCoinRoyaleBet::where('session_id', $session->id)->delete();

        $session->delete();

        return redirect()->route('admin.cerdas-cermat.sessions')->with('success', 'Sesi peserta berhasil direset. Peserta dapat memulai ulang lomba.');
    }

    public function destroyAllSessions()
    {
        try {
            Schema::disableForeignKeyConstraints();
            \App\Models\CerdasCermatAnswer::truncate();
            \App\Models\ExamViolation::truncate();
            QuizQrAttempt::truncate();
            QuizCoinRoyaleBet::truncate();
            CerdasCermatSession::truncate();
            Schema::enableForeignKeyConstraints();
        } catch (\Throwable $e) {
            Schema::enableForeignKeyConstraints();
            \App\Models\CerdasCermatAnswer::query()->delete();
            \App\Models\ExamViolation::query()->delete();
            QuizQrAttempt::query()->delete();
            QuizCoinRoyaleBet::query()->delete();
            CerdasCermatSession::query()->delete();
        }

        QuizQrQuestion::query()->update([
            'status' => 'available',
            'claimed_by_regu_id' => null,
            'claimed_by_session_id' => null,
            'claimed_answer' => null,
            'claimed_at' => null,
        ]);

        QuizCipherWord::query()->update([
            'is_claimed' => false,
            'claimed_by_regu_id' => null,
            'claimed_by_session_id' => null,
            'claimed_at' => null,
        ]);

        QuizCoinRoyaleRound::query()->update([
            'phase' => QuizCoinRoyaleRound::PHASE_PENDING,
            'opened_at' => null,
            'locked_at' => null,
            'graded_at' => null,
        ]);

        $keysToReset = [
            'round_1_started', 'round_1_start_at', 'round_1_status',
            'round_2_started', 'round_2_start_at', 'round_2_status',
            'round_3_started', 'round_3_start_at', 'round_3_status',
            'exam_unlock_code',
        ];
        CerdasCermatSetting::whereIn('key', $keysToReset)->delete();

        return redirect()->back()->with('success', 'Seluruh data kompetisi (sesi, QR klaim, kata sandi, taruhan Coin Royale, dan kontrol babak) berhasil direset total.');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'round_1_duration' => 'required|integer|min:1',
            'round_2_duration' => 'required|integer|min:1',
            'round_3_duration' => 'required|integer|min:1',
            'min_anggota_regu' => 'nullable|integer|min:1|max:10',
        ]);

        foreach (['round_1_duration', 'round_2_duration', 'round_3_duration'] as $k) {
            CerdasCermatSetting::updateOrCreate(['key' => $k], ['value' => $request->input($k)]);
        }

        if ($request->filled('min_anggota_regu')) {
            CerdasCermatSetting::updateOrCreate(
                ['key' => 'min_anggota_regu'],
                ['value' => (int) $request->input('min_anggota_regu')]
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pengaturan berhasil disimpan.']);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    private function parseCsvFile(\Illuminate\Http\UploadedFile $file): array
    {
        $content = file_get_contents($file->getPathname());
        if ($content === false) {
            return [];
        }

        // Strip UTF-8 BOM if present
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content) ?? $content;
        $lines = preg_split('/\r\n|\r|\n/', trim($content)) ?: [];
        if (empty($lines)) {
            return [];
        }

        // Auto-detect delimiter from first non-empty line (supports comma, semicolon, tab)
        $firstLine = $lines[0];
        $delimiter = ',';
        if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
            $delimiter = ';';
        } elseif (substr_count($firstLine, "\t") > substr_count($firstLine, ',')) {
            $delimiter = "\t";
        }

        $rows = [];
        $headers = null;
        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            $cols = str_getcsv($line, $delimiter);
            if ($headers === null) {
                $firstCol = strtolower(trim((string) ($cols[0] ?? '')));
                $looksLikeHeader = in_array($firstCol, [
                    'code_label', 'kode_pos', 'kode', 'question', 'pertanyaan',
                    'word', 'kata', 'round_number', 'ronde', 'no',
                ], true);

                if ($looksLikeHeader) {
                    $headers = array_map(fn($h) => strtolower(trim((string) $h)), $cols);
                    continue;
                }
                $headers = [];
            }

            if (!empty($headers)) {
                $assoc = [];
                foreach ($headers as $idx => $key) {
                    $assoc[$key] = $cols[$idx] ?? null;
                }
                $rows[] = $assoc;
            } else {
                $rows[] = $cols;
            }
        }

        return $rows;
    }

    private function streamCsvDownload(string $filename, array $headers, array $rows)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM so Microsoft Excel detects UTF-8 properly
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function parseAcceptedAnswers(mixed $raw): array
    {
        if (is_array($raw)) {
            return array_values(array_unique(array_filter(array_map(fn($v) => trim((string) $v), $raw))));
        }

        $parts = preg_split('/[\r\n,|]+/', (string) $raw) ?: [];
        return array_values(array_unique(array_filter(array_map('trim', $parts))));
    }

    private function formatQrQuestion(QuizQrQuestion $q): array
    {
        return [
            'id' => $q->id,
            'code_label' => $q->code_label,
            'qr_token' => $q->qr_token,
            'question' => $q->question,
            'image_url' => $q->image_path ? asset('storage/' . $q->image_path) : null,
            'accepted_answers' => $q->accepted_answers ?? [],
            'points' => $q->points,
            'status' => $q->status,
            'claimed_by' => $q->claimedByRegu?->nama_regu,
            'claimed_answer' => $q->claimed_answer,
            'claimed_at' => $q->claimed_at?->toIso8601String(),
            'qr_svg_uri' => QrCodeService::generateDataUri($q->qr_token, 4),
        ];
    }

    private function formatCipherGrid(QuizCipherGrid $g): array
    {
        return [
            'id' => $g->id,
            'title' => $g->title,
            'cipher_type' => $g->cipher_type,
            'grid_size' => $g->grid_size,
            'grid_matrix' => $g->grid_matrix,
            'word_placements' => $g->word_placements,
            'default_points' => $g->default_points,
            'is_active' => $g->is_active,
            'words' => $g->words->map(fn(QuizCipherWord $w) => [
                'id' => $w->id,
                'word' => $w->word,
                'hint' => $w->hint,
                'points' => $w->points,
                'is_claimed' => $w->is_claimed,
                'claimed_by' => $w->claimedByRegu?->nama_regu,
                'claimed_at' => $w->claimed_at?->toIso8601String(),
            ])->values(),
        ];
    }

    private function syncNilaiMaksimal(MataLomba $mataLomba): void
    {
        $totalScore = CerdasCermatQuestion::sum('score') + QuizQrQuestion::sum('points') + QuizCipherWord::sum('points');
        $mataLomba->update(['nilai_maksimal' => $totalScore]);
    }
}
