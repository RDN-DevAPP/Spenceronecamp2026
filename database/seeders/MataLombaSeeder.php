<?php

namespace Database\Seeders;

use App\Models\MataLomba;
use Illuminate\Database\Seeder;

class MataLombaSeeder extends Seeder
{
    /**
     * 6 mata lomba LT-I Spencerone Camp 2026.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Tapak Kemah', 'slug' => 'tapak-kemah', 'urutan' => 1],
            ['nama' => 'Masak Konvensional', 'slug' => 'masak-konvensional', 'urutan' => 2],
            ['nama' => 'Upcycle Art', 'slug' => 'upcycle-art', 'urutan' => 3],
            ['nama' => 'LKBB Tongkat', 'slug' => 'lkbb-tongkat', 'urutan' => 4],
            ['nama' => 'Cerdas Cermat', 'slug' => 'cerdas-cermat', 'urutan' => 5],
            ['nama' => 'Desain Poster Digital', 'slug' => 'desain-poster-digital', 'urutan' => 6],
        ];

        foreach ($data as $item) {
            MataLomba::updateOrCreate(
                ['slug' => $item['slug']],
                array_merge($item, ['nilai_maksimal' => 100])
            );
        }
    }
}
