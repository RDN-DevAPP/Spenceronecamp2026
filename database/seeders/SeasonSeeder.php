<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Season I (Historical)
        Season::updateOrCreate(
            ['slug' => 'season-1-2026'],
            [
                'name' => 'Season I (2026)',
                'slug' => 'season-1-2026',
                'is_active' => false,
                'theme' => 'Tunas Muda Spencerone: Tangguh, Kreatif, Berkarakter',
                'tagline' => 'Spencerone Camp Season I',
                'start_date' => '2026-04-24',
                'end_date' => '2026-04-25',
                'location' => 'Bumi Perkemahan SMPN 1 Cerbon',
            ]
        );

        // Season II (Active)
        Season::updateOrCreate(
            ['slug' => 'season-2-2026'],
            [
                'name' => 'Season II (2026)',
                'slug' => 'season-2-2026',
                'is_active' => true,
                'theme' => 'Satu Janji, Sejuta Bakti, Pandu Sejati Mengabdi',
                'tagline' => 'Spencerone Camp Season II',
                'start_date' => '2026-09-15',
                'end_date' => '2026-09-17',
                'location' => 'Bumi Perkemahan Gudep 03.059-03.060 SMPN 1 Cerbon, Barito Kuala',
            ]
        );
    }
}
