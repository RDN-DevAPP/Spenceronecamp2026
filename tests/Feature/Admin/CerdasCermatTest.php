<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\MataLomba;
use App\Models\CerdasCermatQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CerdasCermatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create user and MataLomba if not exists (mocking or ensuring presence)
        // For this environment avoiding complex setup, assuming DB has data or we create temp
    }

    public function test_admin_can_view_cerdas_cermat_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.cerdas-cermat.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.cerdas-cermat.index');
    }

    public function test_admin_can_create_question()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $mataLomba = MataLomba::firstOrCreate(
            ['slug' => 'cerdas-cermat'],
            ['nama' => 'Cerdas Cermat']
        );

        $response = $this->actingAs($admin)
            ->post(route('admin.cerdas-cermat.store'), [
                'type' => 'Pilihan Ganda',
                'question' => 'Apa ibu kota Indonesia?',
                'options' => ['a' => 'Jakarta', 'b' => 'Bandung', 'c' => 'Surabaya', 'd' => 'Medan', 'e' => 'Makassar'],
                'correct_answer' => 'a',
                'score' => 10,
            ]);

        $response->assertRedirect(route('admin.cerdas-cermat.index', ['tab' => 'Pilihan Ganda']));
        $this->assertDatabaseHas('cerdas_cermat_questions', [
            'question' => 'Apa ibu kota Indonesia?',
            'correct_answer' => 'a',
        ]);
    }

    public function test_admin_can_import_csv()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $mataLomba = MataLomba::firstOrCreate(
            ['slug' => 'cerdas-cermat'],
            ['nama' => 'Cerdas Cermat']
        );

        $header = "No,Soal,Pilihan 1,Pilihan 2,Pilihan 3,Pilihan 4,Pilihan 5,Jawaban Benar,Nilai";
        $row1 = "1,Soal Test CSV,A,B,C,D,E,A,100";
        $content = $header . "\n" . $row1;

        $file = UploadedFile::fake()->createWithContent('questions.csv', $content);

        $response = $this->actingAs($admin)
            ->post(route('admin.cerdas-cermat.import'), [
                'file' => $file,
                'type' => 'Pilihan Ganda',
            ]);

        $response->assertRedirect(route('admin.cerdas-cermat.index', ['tab' => 'Pilihan Ganda']));
        $this->assertDatabaseHas('cerdas_cermat_questions', [
            'question' => 'Soal Test CSV',
            'score' => 100,
            'type' => 'Pilihan Ganda',
        ]);
    }

    public function test_admin_can_import_csv_short_answer()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $mataLomba = MataLomba::firstOrCreate(
            ['slug' => 'cerdas-cermat'],
            ['nama' => 'Cerdas Cermat']
        );

        $header = "No,Soal,Nilai Maksimal";
        $row1 = "1,Soal Isian Singkat CSV,50";
        $content = $header . "\n" . $row1;

        $file = UploadedFile::fake()->createWithContent('questions_short.csv', $content);

        $response = $this->actingAs($admin)
            ->post(route('admin.cerdas-cermat.import'), [
                'file' => $file,
                'type' => 'Isian Singkat',
            ]);

        $response->assertRedirect(route('admin.cerdas-cermat.index', ['tab' => 'Isian Singkat']));
        $this->assertDatabaseHas('cerdas_cermat_questions', [
            'question' => 'Soal Isian Singkat CSV',
            'score' => 50,
            'type' => 'Isian Singkat',
        ]);
    }

    public function test_admin_can_delete_all_questions_by_type()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $mataLomba = MataLomba::firstOrCreate(
            ['slug' => 'cerdas-cermat'],
            ['nama' => 'Cerdas Cermat']
        );

        CerdasCermatQuestion::create([
            'mata_lomba_id' => $mataLomba->id,
            'type' => 'Pilihan Ganda',
            'question' => 'Q1',
            'correct_answer' => 'a',
            'score' => 10
        ]);

        CerdasCermatQuestion::create([
            'mata_lomba_id' => $mataLomba->id,
            'type' => 'Pilihan Ganda',
            'question' => 'Q2',
            'correct_answer' => 'b',
            'score' => 10
        ]);

        // Create Uraian question (should not be deleted)
        CerdasCermatQuestion::create([
            'mata_lomba_id' => $mataLomba->id,
            'type' => 'Uraian',
            'question' => 'U1',
            'correct_answer' => 'answer',
            'score' => 20
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.cerdas-cermat.destroyAll'), [
                'type' => 'Pilihan Ganda',
            ]);

        $response->assertRedirect(route('admin.cerdas-cermat.index', ['tab' => 'Pilihan Ganda']));

        $this->assertDatabaseMissing('cerdas_cermat_questions', ['question' => 'Q1']);
        $this->assertDatabaseMissing('cerdas_cermat_questions', ['question' => 'Q2']);
        $this->assertDatabaseHas('cerdas_cermat_questions', ['question' => 'U1']);
    }

    public function test_admin_can_edit_question()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $mataLomba = MataLomba::firstOrCreate(
            ['slug' => 'cerdas-cermat'],
            ['nama' => 'Cerdas Cermat']
        );

        $question = CerdasCermatQuestion::create([
            'mata_lomba_id' => $mataLomba->id,
            'type' => 'Pilihan Ganda',
            'question' => 'Edit Me',
            'correct_answer' => 'a',
            'score' => 10
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.cerdas-cermat.edit', $question));

        $response->assertStatus(200);
        $response->assertSee('Edit Me');
    }
}
