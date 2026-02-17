<?php

namespace Database\Seeders;

use App\Models\ReguProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReguProfileSeeder extends Seeder
{
    /**
     * Seed regu-regu peserta LT-I 2026.
     */
    public function run(): void
    {
        // Data Regu Putra
        $reguPutra = [
            ['nama_regu' => 'Regu Elang', 'jenis' => 'putra', 'nomor_regu' => 1],
            ['nama_regu' => 'Regu Harimau', 'jenis' => 'putra', 'nomor_regu' => 2],
            ['nama_regu' => 'Regu Kobra', 'jenis' => 'putra', 'nomor_regu' => 3],
            ['nama_regu' => 'Regu Kalajengking', 'jenis' => 'putra', 'nomor_regu' => 4],
        ];

        // Data Regu Putri
        $reguPutri = [
            ['nama_regu' => 'Regu Melati', 'jenis' => 'putri', 'nomor_regu' => 1],
            ['nama_regu' => 'Regu Mawar', 'jenis' => 'putri', 'nomor_regu' => 2],
            ['nama_regu' => 'Regu Anggrek', 'jenis' => 'putri', 'nomor_regu' => 3],
            ['nama_regu' => 'Regu Tulip', 'jenis' => 'putri', 'nomor_regu' => 4],
        ];

        // Gabungkan semua regu
        $allRegu = array_merge($reguPutra, $reguPutri);

        foreach ($allRegu as $index => $reguData) {
            // Buat user untuk setiap regu
            $user = User::updateOrCreate(
                ['username' => strtolower(str_replace(' ', '', $reguData['nama_regu']))],
                [
                    'name' => $reguData['nama_regu'],
                    'email' => strtolower(str_replace(' ', '', $reguData['nama_regu'])) . '@smpn1cerbon.sch.id',
                    'password' => bcrypt('password123'),
                    'role' => 'regu',
                ]
            );

            // Buat profil regu
            ReguProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_regu' => $reguData['nama_regu'],
                    'jenis' => $reguData['jenis'],
                    'nomor_regu' => $reguData['nomor_regu'],
                    'poster_path' => null,
                ]
            );
        }
    }
}
