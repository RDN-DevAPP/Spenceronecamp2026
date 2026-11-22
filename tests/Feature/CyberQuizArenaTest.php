<?php

namespace Tests\Feature;

use App\Models\AnggotaRegu;
use App\Models\CerdasCermatSession;
use App\Models\MataLomba;
use App\Models\QuizCipherGrid;
use App\Models\QuizCoinRoyaleRound;
use App\Models\QuizQrQuestion;
use App\Models\ReguProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CyberQuizArenaTest extends TestCase
{
    use RefreshDatabase;

    private function createRegu(string $name, int $nomor, int $memberCount = 1): array
    {
        $user = User::factory()->create(['role' => User::ROLE_REGU, 'name' => $name]);
        $regu = ReguProfile::create([
            'user_id' => $user->id,
            'nama_regu' => $name,
            'jenis' => 'putra',
            'nomor_regu' => $nomor,
        ]);

        for ($i = 1; $i <= $memberCount; $i++) {
            AnggotaRegu::create([
                'regu_profile_id' => $regu->id,
                'nama' => "{$name} Member {$i}",
                'kelas' => 7,
                'tingkatan_tku' => 'ramu',
                'jabatan' => $i === 1 ? 'pinru' : 'anggota',
                'urutan' => $i,
            ]);
        }

        return [$user, $regu];
    }

    public function test_eligibility_check_blocks_regu_without_members_and_opens_when_registered(): void
    {
        [$userNoMembers] = $this->createRegu('Regu Kosong', 1, 0);
        $this->actingAs($userNoMembers)
            ->get(route('peserta.cerdas-cermat.index'))
            ->assertRedirect(route('peserta.dashboard'));

        [$userWithMembers, $reguElang] = $this->createRegu('Regu Elang', 3, 3);
        $this->actingAs($userWithMembers)
            ->get(route('peserta.cerdas-cermat.index'))
            ->assertStatus(200)
            ->assertSee('PesertaRoadmapView');

        // Register 3 representatives on User Regu Cerdas Cermat page (reject duplicates, accept distinct)
        $this->actingAs($userWithMembers)->postJson(route('peserta.cerdas-cermat.register'), [
            'name_1' => 'Regu Elang Member 1',
            'name_2' => 'Regu Elang Member 1',
            'name_3' => 'Regu Elang Member 3',
        ])->assertStatus(422);

        $this->actingAs($userWithMembers)->postJson(route('peserta.cerdas-cermat.register'), [
            'name_1' => 'Regu Elang Member 1',
            'name_2' => 'Regu Elang Member 2',
            'name_3' => 'Regu Elang Member 3',
        ])->assertStatus(200)->assertJsonPath('session.name_2', 'Regu Elang Member 2');

        $this->assertDatabaseHas('cerdas_cermat_sessions', [
            'regu_id' => $reguElang->id,
            'name_1' => 'Regu Elang Member 1',
            'name_2' => 'Regu Elang Member 2',
            'name_3' => 'Regu Elang Member 3',
        ]);
    }

    public function test_round_1_qr_hunt_atomic_locking_and_pdf_export(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $juri = User::factory()->create(['role' => User::ROLE_JURI]);
        MataLomba::firstOrCreate(['slug' => 'cerdas-cermat'], ['nama' => 'Cerdas Cermat']);

        [$userA, $reguA] = $this->createRegu('Regu Rajawali', 1, 1);
        [$userB, $reguB] = $this->createRegu('Regu Harimau', 3, 1);

        // Admin creates QR question
        $res = $this->actingAs($admin)->postJson(route('admin.cerdas-cermat.qr.store'), [
            'question' => 'Siapa Bapak Pandu Dunia?',
            'accepted_answers' => 'Baden Powell, Lord Baden Powell',
            'points' => 150,
        ])->assertStatus(201);

        $token = $res->json('data.qr_token');

        // Admin exports Mass QR PDF
        $pdfRes = $this->actingAs($admin)->get(route('admin.cerdas-cermat.qr.print-pdf'));
        $pdfRes->assertStatus(200);
        $this->assertStringContainsString('application/pdf', (string) $pdfRes->headers->get('content-type'));

        // Juri activates Round 1
        $this->actingAs($juri)->postJson(route('juri.cerdas-cermat.round-status', 1), ['status' => 'ACTIVE'])
            ->assertStatus(200);

        // Regu A & B both scan while available
        $this->actingAs($userA)->postJson(route('peserta.cerdas-cermat.round-1.scan'), ['qr_token' => $token])->assertStatus(200);
        $this->actingAs($userB)->postJson(route('peserta.cerdas-cermat.round-1.scan'), ['qr_token' => $token])->assertStatus(200);

        // Regu A submits wrong answer -> 422
        $this->actingAs($userA)->postJson(route('peserta.cerdas-cermat.round-1.answer'), [
            'qr_token' => $token,
            'answer' => 'Salah',
        ])->assertStatus(422);

        // Regu A submits correct case-insensitive variation -> 200
        $this->actingAs($userA)->postJson(route('peserta.cerdas-cermat.round-1.answer'), [
            'qr_token' => $token,
            'answer' => '  lord baden powell ',
        ])->assertStatus(200)->assertJsonPath('points_awarded', 150);

        // Regu B tries to claim/scan -> 409 conflict
        $this->actingAs($userB)->postJson(route('peserta.cerdas-cermat.round-1.scan'), ['qr_token' => $token])
            ->assertStatus(409)
            ->assertJsonFragment(['message' => 'Soal ini sudah diklaim oleh Regu Regu Rajawali!']);

        $this->assertEquals(150, CerdasCermatSession::where('regu_id', $reguA->id)->value('score_round_1'));
        $this->assertEquals(0, CerdasCermatSession::where('regu_id', $reguB->id)->value('score_round_1'));
    }

    public function test_round_2_cipher_word_search_single_claim_and_a4_pdf(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $juri = User::factory()->create(['role' => User::ROLE_JURI]);
        [$userA, $reguA] = $this->createRegu('Regu Garuda', 1, 1);
        [$userB, $reguB] = $this->createRegu('Regu Komodo', 3, 1);

        $createRes = $this->actingAs($admin)->postJson(route('admin.cerdas-cermat.cipher.store'), [
            'title' => 'Kategori Sandi Kotak 1',
            'cipher_type' => 'kotak_1',
            'grid_size' => 12,
            'words' => [
                ['word' => 'PRAMUKA', 'hint' => 'Praja Muda Karana', 'points' => 80],
                ['word' => 'SANDI', 'hint' => 'Kode Rahasia', 'points' => 60],
            ],
        ])->assertStatus(201);

        $gridId = $createRes->json('data.id');

        // Admin exports A4 PDF
        $pdfRes = $this->actingAs($admin)->get(route('admin.cerdas-cermat.cipher.print-pdf', $gridId));
        $pdfRes->assertStatus(200);
        $this->assertStringContainsString('application/pdf', (string) $pdfRes->headers->get('content-type'));

        // Juri activates Round 2
        $this->actingAs($juri)->postJson(route('juri.cerdas-cermat.round-status', 2), ['status' => 'ACTIVE'])
            ->assertStatus(200);

        // Regu A claims PRAMUKA
        $this->actingAs($userA)->postJson(route('peserta.cerdas-cermat.round-2.claim'), [
            'cipher_grid_id' => $gridId,
            'word' => 'pramuka',
        ])->assertStatus(200)->assertJsonPath('points_awarded', 80);

        // Regu B cannot claim PRAMUKA again
        $this->actingAs($userB)->postJson(route('peserta.cerdas-cermat.round-2.claim'), [
            'cipher_grid_id' => $gridId,
            'word' => 'PRAMUKA',
        ])->assertStatus(409)->assertJsonPath('already_claimed', true);

        $this->assertEquals(80, CerdasCermatSession::where('regu_id', $reguA->id)->value('score_round_2'));
        $this->assertEquals(0, CerdasCermatSession::where('regu_id', $reguB->id)->value('score_round_2'));
    }

    public function test_round_3_coin_royale_top5_and_normal_special_math(): void
    {
        $juri = User::factory()->create(['role' => User::ROLE_JURI]);

        $teams = [];
        for ($i = 1; $i <= 6; $i++) {
            [$user, $regu] = $this->createRegu("Regu {$i}", $i, 1);
            $session = CerdasCermatSession::create([
                'regu_id' => $regu->id,
                'score_round_1' => $i * 50,
                'score_round_2' => $i * 50, // Total: 100, 200, 300, 400, 500, 600
                'status' => 'round_2_done',
            ]);
            $teams[$i] = ['user' => $user, 'regu' => $regu, 'session' => $session];
        }

        // Juri initializes Top 5 & activates Round 3
        $this->actingAs($juri)->postJson(route('juri.cerdas-cermat.round-status', 3), ['status' => 'ACTIVE'])
            ->assertStatus(200);

        // Regu 1 (100 pts) should NOT be in Top 5; Regu 2..6 (200..600) ARE Top 5
        $this->assertFalse((bool) $teams[1]['session']->fresh()->is_verified_round_3);
        $this->assertTrue((bool) $teams[6]['session']->fresh()->is_verified_round_3);
        $this->assertEquals(600, $teams[6]['session']->fresh()->coin_balance);

        // Round 1 (Normal, mandatory)
        $this->actingAs($juri)->postJson(route('juri.cerdas-cermat.coin-royale.open-betting', 1))->assertStatus(200);

        // Passing on Normal round is rejected
        $this->actingAs($teams[6]['user'])->postJson(route('peserta.cerdas-cermat.round-3.bet'), [
            'round_number' => 1,
            'action' => 'PASS',
            'bet_amount' => 0,
        ])->assertStatus(422);

        // Regu 6 bets 100, Regu 5 (500) bets 100
        $this->actingAs($teams[6]['user'])->postJson(route('peserta.cerdas-cermat.round-3.bet'), [
            'round_number' => 1, 'action' => 'BET', 'bet_amount' => 100,
        ])->assertStatus(200);
        $this->actingAs($teams[5]['user'])->postJson(route('peserta.cerdas-cermat.round-3.bet'), [
            'round_number' => 1, 'action' => 'BET', 'bet_amount' => 100,
        ])->assertStatus(200);

        $this->actingAs($juri)->postJson(route('juri.cerdas-cermat.coin-royale.open-question', 1))->assertStatus(200);
        $this->actingAs($juri)->postJson(route('juri.cerdas-cermat.coin-royale.grade', 1), [
            'results' => [
                $teams[6]['session']->id => 'CORRECT', // (600 - 100) + 200 = 700
                $teams[5]['session']->id => 'WRONG',   // 500 - 100 = 400
                $teams[4]['session']->id => 'WRONG',
                $teams[3]['session']->id => 'WRONG',
                $teams[2]['session']->id => 'WRONG',
            ],
        ])->assertStatus(200);

        $this->assertEquals(700, $teams[6]['session']->fresh()->coin_balance);
        $this->assertEquals(400, $teams[5]['session']->fresh()->coin_balance);

        // Round 3 (Special, optional, max 50% bet, 3x win / -2x loss)
        $this->actingAs($juri)->postJson(route('juri.cerdas-cermat.coin-royale.open-betting', 3))->assertStatus(200);

        // Over 50% bet rejected (700 * 50% = 350 max)
        $this->actingAs($teams[6]['user'])->postJson(route('peserta.cerdas-cermat.round-3.bet'), [
            'round_number' => 3, 'action' => 'BET', 'bet_amount' => 400,
        ])->assertStatus(422);

        // Regu 6 bets 200, Regu 5 passes, Regu 4 (400) bets 100
        $this->actingAs($teams[6]['user'])->postJson(route('peserta.cerdas-cermat.round-3.bet'), [
            'round_number' => 3, 'action' => 'BET', 'bet_amount' => 200,
        ])->assertStatus(200);
        $this->actingAs($teams[5]['user'])->postJson(route('peserta.cerdas-cermat.round-3.bet'), [
            'round_number' => 3, 'action' => 'PASS', 'bet_amount' => 0,
        ])->assertStatus(200);
        $this->actingAs($teams[4]['user'])->postJson(route('peserta.cerdas-cermat.round-3.bet'), [
            'round_number' => 3, 'action' => 'BET', 'bet_amount' => 100,
        ])->assertStatus(200);

        $this->actingAs($juri)->postJson(route('juri.cerdas-cermat.coin-royale.grade', 3), [
            'results' => [
                $teams[6]['session']->id => 'CORRECT', // (700 - 200) + (200 * 3) = 1100
                $teams[4]['session']->id => 'WRONG',   // 400 - (100 * 2) = 200
                $teams[3]['session']->id => 'WRONG',
                $teams[2]['session']->id => 'WRONG',
            ],
        ])->assertStatus(200);

        $this->assertEquals(1100, $teams[6]['session']->fresh()->coin_balance);
        $this->assertEquals(400, $teams[5]['session']->fresh()->coin_balance); // PASS stayed safe
        $this->assertEquals(200, $teams[4]['session']->fresh()->coin_balance);

        // Public /live-display returns 200
        $this->get(route('live-display', ['tab' => 'quiz']))->assertStatus(200);
    }

    public function test_csv_templates_and_imports_for_all_rounds_and_larger_cipher_pdf(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        // 1. Template CSV downloads
        foreach ([
            route('admin.cerdas-cermat.qr.template-csv') => 'code_label,question,accepted_answers,points',
            route('admin.cerdas-cermat.cipher.template-csv') => 'word,points',
            route('admin.cerdas-cermat.coin-royale.template-csv') => 'round_number,question_text,correct_answer',
        ] as $url => $expectedHeader) {
            $res = $this->actingAs($admin)->get($url);
            $res->assertStatus(200);
            $this->assertStringContainsString('text/csv', (string) $res->headers->get('content-type'));
            $this->assertStringContainsString($expectedHeader, $res->streamedContent());
        }

        // 2. Import Babak 1 QR CSV
        $qrCsv = UploadedFile::fake()->createWithContent('qr.csv', implode("\n", [
            'code_label,question,accepted_answers,points',
            'QR-CSV-01,"Siapa penemu sandi Morse?","Samuel Morse|Morse",120',
            'QR-CSV-02,"Berapa jumlah Dasa Dharma?","10|Sepuluh",100',
        ]));

        $this->actingAs($admin)
            ->post(route('admin.cerdas-cermat.qr.import-csv'), ['file' => $qrCsv], ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->assertDatabaseHas('quiz_qr_questions', ['code_label' => 'QR-CSV-01', 'points' => 120]);
        $this->assertDatabaseHas('quiz_qr_questions', ['code_label' => 'QR-CSV-02', 'points' => 100]);

        // 3. Import Babak 2 Words CSV (standalone preview + onto existing grid with ';' delimiter)
        $wordsCsv = UploadedFile::fake()->createWithContent('words.csv', implode("\n", [
            'word;points',
            'TRISATYA;75',
            'DASADHARMA;90',
        ]));

        $previewCsvRes = $this->actingAs($admin)
            ->post(route('admin.cerdas-cermat.cipher.import-words-csv'), ['file' => $wordsCsv], ['Accept' => 'application/json'])
            ->assertStatus(200);
        $this->assertCount(2, $previewCsvRes->json('words'));

        $gridRes = $this->actingAs($admin)->postJson(route('admin.cerdas-cermat.cipher.store'), [
            'title' => 'Grid CSV Test',
            'cipher_type' => 'kotak_2',
            'grid_size' => 14,
            'words' => [['word' => 'AWAL', 'points' => 50]],
        ])->assertStatus(201);
        $gridId = $gridRes->json('data.id');

        $wordsCsv2 = UploadedFile::fake()->createWithContent('words2.csv', implode("\n", [
            'word;points',
            'TRISATYA;75',
            'KOMPAS;60',
        ]));

        $this->actingAs($admin)
            ->post(route('admin.cerdas-cermat.cipher.import-words-csv'), [
                'file' => $wordsCsv2,
                'cipher_grid_id' => $gridId,
            ], ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->assertDatabaseHas('quiz_cipher_words', ['cipher_grid_id' => $gridId, 'word' => 'TRISATYA', 'points' => 75]);
        $this->assertDatabaseHas('quiz_cipher_words', ['cipher_grid_id' => $gridId, 'word' => 'KOMPAS', 'points' => 60]);

        // 4. Import Babak 3 Coin Royale CSV
        $crCsv = UploadedFile::fake()->createWithContent('coin.csv', implode("\n", [
            'round_number,question_text,correct_answer',
            '1,"Kepanjangan dari WOSM?","World Organization of the Scout Movement"',
            '3,"Tahun berdirinya Pramuka Indonesia?","1961"',
        ]));

        $this->actingAs($admin)
            ->post(route('admin.cerdas-cermat.coin-royale.import-csv'), ['file' => $crCsv], ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->assertDatabaseHas('quiz_coin_royale_rounds', ['round_number' => 1, 'correct_answer' => 'World Organization of the Scout Movement']);
        $this->assertDatabaseHas('quiz_coin_royale_rounds', ['round_number' => 3, 'correct_answer' => '1961']);

        // 5. Babak 3 round image upload & reveal control
        \Illuminate\Support\Facades\Storage::fake('public');
        $imgFile = UploadedFile::fake()->image('soal-ronde-2.png', 200, 200);
        $this->actingAs($admin)
            ->post(route('admin.cerdas-cermat.coin-royale.upload-image', 2), ['image' => $imgFile], ['Accept' => 'application/json'])
            ->assertStatus(200);

        $round2 = QuizCoinRoyaleRound::where('round_number', 2)->first();
        $this->assertNotNull($round2->question_image_path);

        // Hidden for public before QUESTION_OPEN, visible when QUESTION_OPEN
        $quizService = app(\App\Services\CyberQuizService::class);
        $publicBefore = collect($quizService->getCoinRoyaleState(false)['rounds'])->firstWhere('round_number', 2);
        $this->assertNull($publicBefore['question_image_url']);

        $round2->update(['phase' => QuizCoinRoyaleRound::PHASE_QUESTION_OPEN]);
        $publicAfter = collect($quizService->getCoinRoyaleState(false)['rounds'])->firstWhere('round_number', 2);
        $this->assertNotNull($publicAfter['question_image_url']);

        $this->actingAs($admin)
            ->deleteJson(route('admin.cerdas-cermat.coin-royale.delete-image', 2))
            ->assertStatus(200);
        $this->assertNull($round2->fresh()->question_image_path);
    }
}
