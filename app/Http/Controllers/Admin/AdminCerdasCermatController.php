<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CerdasCermatQuestion;
use App\Models\MataLomba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminCerdasCermatController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'Pilihan Ganda');
        $questions = CerdasCermatQuestion::where('type', $activeTab)->latest()->get();

        $settings = [
            'round_1_duration' => \App\Models\CerdasCermatSetting::getValue('round_1_duration', 60),
            'round_2_duration' => \App\Models\CerdasCermatSetting::getValue('round_2_duration', 35),
            'round_3_duration' => \App\Models\CerdasCermatSetting::getValue('round_3_duration', 30),
            // Default 0 if unused or maybe just use the 3 above
        ];

        return view('admin.cerdas-cermat.index', compact('questions', 'activeTab', 'settings'));
    }

    public function create()
    {
        return view('admin.cerdas-cermat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['Pilihan Ganda', 'Isian Singkat', 'Uraian'])],
            'question' => 'required|string',
            'correct_answer' => 'required|string',
            'score' => 'required|integer|min:0',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
        ]);

        $mataLomba = MataLomba::where('slug', 'cerdas-cermat')->firstOrFail();

        CerdasCermatQuestion::create([
            'mata_lomba_id' => $mataLomba->id,
            'type' => $validated['type'],
            'question' => $validated['question'],
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
            'correct_answer' => 'required|string',
            'score' => 'required|integer|min:0',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
        ]);

        $question->update([
            'type' => $validated['type'],
            'question' => $validated['question'],
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
        // ... (existing import logic) ...
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'type' => ['required', Rule::in(['Pilihan Ganda', 'Isian Singkat', 'Uraian'])],
        ]);

        $file = $request->file('file');
        $type = $request->input('type');

        $mataLomba = MataLomba::where('slug', 'cerdas-cermat')->firstOrFail();

        DB::beginTransaction();
        try {
            if (($handle = fopen($file->getPathname(), "r")) !== FALSE) {
                // Skip header row
                fgetcsv($handle, 1000, ",");

                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (empty($row[1]))
                        continue; // Skip if question is empty

                    $questionText = $row[1];
                    $options = null;
                    $correctAnswer = '';
                    $score = 0;

                    if ($type === 'Pilihan Ganda') {
                        // Expected format: No, Soal, Pilihan 1, Pilihan 2, Pilihan 3, Pilihan 4, Pilihan 5, Jawaban Benar, Nilai
                        if (count($row) < 9)
                            continue;

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
                        // Expected format: No, Soal, Jawaban, Nilai
                        // Index: 0, 1, 2, 3
                        if (count($row) < 4)
                            continue;

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
        $sessions = \App\Models\CerdasCermatSession::with('reguProfile')->latest()->get();
        return view('admin.cerdas-cermat.sessions', compact('sessions'));
    }

    public function resetSession($id)
    {
        $session = \App\Models\CerdasCermatSession::findOrFail($id);

        // Delete all answers associated with this session
        $session->answers()->delete();

        // Delete the session itself
        $session->delete();

        return redirect()->route('admin.cerdas-cermat.sessions')->with('success', 'Sesi peserta berhasil direset. Peserta dapat memulai ulang lomba.');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'round_1_duration' => 'required|integer|min:1',
            'round_2_duration' => 'required|integer|min:1',
            'round_3_duration' => 'required|integer|min:1',
        ]);

        \App\Models\CerdasCermatSetting::updateOrCreate(
            ['key' => 'round_1_duration'],
            ['value' => $request->round_1_duration]
        );

        \App\Models\CerdasCermatSetting::updateOrCreate(
            ['key' => 'round_2_duration'],
            ['value' => $request->round_2_duration]
        );

        \App\Models\CerdasCermatSetting::updateOrCreate(
            ['key' => 'round_3_duration'],
            ['value' => $request->round_3_duration]
        );

        return redirect()->back()->with('success', 'Pengaturan waktu berhasil diperbarui.');
    }

    private function syncNilaiMaksimal(MataLomba $mataLomba)
    {
        $totalScore = CerdasCermatQuestion::sum('score');
        $mataLomba->update(['nilai_maksimal' => $totalScore]);
    }
}
