<?php

namespace Database\Seeders;

use App\Models\ReguProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * 1 Admin, 4 Juri, 8 Regu sesuai proposal LT-I Spencerone Camp 2026.
     * Password default: password (untuk development).
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1 Akun Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Spencerone',
                'email' => 'admin@lt1-spencerone.local',
                'password' => $password,
                'role' => User::ROLE_ADMIN,
            ]
        );

        // 4 Akun Juri (sesuai proposal)
        $juri = [
            ['username' => 'murad_naser', 'name' => 'Murad Naser'],
            ['username' => 'benjamin_shah', 'name' => 'Benjamin Shah'],
            ['username' => 'helena_paquet', 'name' => 'Helena Paquet'],
            ['username' => 'itsuki_takahashi', 'name' => 'Itsuki Takahashi'],
            ['username' => 'adrian_lim', 'name' => 'Adrian Lim'],
        ];

        foreach ($juri as $i => $j) {
            User::updateOrCreate(
                ['username' => $j['username']],
                [
                    'name' => $j['name'],
                    'email' => $j['username'] . '@lt1-spencerone.local',
                    'password' => $password,
                    'role' => User::ROLE_JURI,
                ]
            );
        }

        // 8 Akun Regu (4 Putra + 4 Putri) dengan ReguProfile
        $reguList = [
            ['username' => 'regu_elang', 'nama_regu' => 'Regu Elang', 'jenis' => 'putra', 'nomor_regu' => 1],
            ['username' => 'regu_harimau', 'nama_regu' => 'Regu Harimau', 'jenis' => 'putra', 'nomor_regu' => 2],
            ['username' => 'regu_rajawali', 'nama_regu' => 'Regu Rajawali', 'jenis' => 'putra', 'nomor_regu' => 3],
            ['username' => 'regu_garuda', 'nama_regu' => 'Regu Garuda', 'jenis' => 'putra', 'nomor_regu' => 4],
            ['username' => 'regu_melati', 'nama_regu' => 'Regu Melati', 'jenis' => 'putri', 'nomor_regu' => 1],
            ['username' => 'regu_merpati', 'nama_regu' => 'Regu Merpati', 'jenis' => 'putri', 'nomor_regu' => 2],
            ['username' => 'regu_kijang', 'nama_regu' => 'Regu Kijang', 'jenis' => 'putri', 'nomor_regu' => 3],
            ['username' => 'regu_macan', 'nama_regu' => 'Regu Macan', 'jenis' => 'putri', 'nomor_regu' => 4],
        ];

        foreach ($reguList as $r) {
            $user = User::updateOrCreate(
                ['username' => $r['username']],
                [
                    'name' => $r['nama_regu'],
                    'email' => $r['username'] . '@lt1-spencerone.local',
                    'password' => $password,
                    'role' => User::ROLE_REGU,
                ]
            );

            ReguProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_regu' => $r['nama_regu'],
                    'jenis' => $r['jenis'],
                    'nomor_regu' => $r['nomor_regu'],
                ]
            );
        }
    }
}
