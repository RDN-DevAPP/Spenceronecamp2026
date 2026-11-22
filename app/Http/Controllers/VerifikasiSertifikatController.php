<?php

namespace App\Http\Controllers;

use App\Models\PanitiaMember;
use App\Models\SertifikatJuara;
use App\Models\SertifikatPeserta;
use App\Models\AnggotaRegu;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class VerifikasiSertifikatController extends Controller
{
    /**
     * Halaman Utama Verifikasi Sertifikat (Pencarian & Scan Kamera QR)
     */
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));
        
        if (!empty($q)) {
            return $this->show($q, $request);
        }

        return view('verifikasi-sertifikat', [
            'isSearch' => true,
            'isValid' => null,
            'sertifikat' => null,
            'anggota' => null,
            'regu' => null,
            'token' => null,
        ]);
    }

    /**
     * Halaman Publik Verifikasi Sertifikat Peserta (Bisa diakses tanpa login via scan QR)
     */
    public function show($token, Request $request = null)
    {
        $rawInput = trim($token);
        $cleanToken = $rawInput;

        // Bersihkan token jika scanner membaca full URL
        if (preg_match('~verifikasi-sertifikat/([^/?#]+)~i', $rawInput, $m)) {
            $cleanToken = $m[1];
        } elseif (preg_match('~verifikasi-anggota/(\d+)~i', $rawInput, $m)) {
            $cleanToken = $m[1];
        }

        $sertifikat = null;
        $anggota = null;
        $regu = null;
        $panitia = null;
        $juara = null;
        $certType = 'peserta';
        $isValid = false;

        // 0a. Cek token Sertifikat Panitia (PAN-...)
        if (Schema::hasTable('panitia_members')) {
            $panitia = PanitiaMember::where('verify_token', $cleanToken)
                ->orWhere('nomor_sertifikat', urldecode($cleanToken))
                ->first();
            if ($panitia) {
                $isValid = true;
                $certType = 'panitia';
                $sertifikat = $panitia;
            }
        }

        // 0b. Cek token Sertifikat Juara Lomba (JUA-...)
        if (!$isValid && Schema::hasTable('sertifikat_juaras')) {
            $juara = SertifikatJuara::with('reguProfile')
                ->where('verify_token', $cleanToken)
                ->orWhere('nomor_sertifikat', urldecode($cleanToken))
                ->first();
            if ($juara) {
                $isValid = true;
                $certType = 'juara';
                $regu = $juara->reguProfile;
                $sertifikat = $juara;
            }
        }

        // 1. Cari berdasarkan verify_token di database (Peserta)
        if (!$isValid && Schema::hasTable('sertifikat_peserta')) {
            $sertifikat = SertifikatPeserta::with(['anggotaRegu.reguProfile'])
                ->where('verify_token', $cleanToken)
                ->first();
        }

        // 2. Cek apakah token berformat SC2-{anggota_id}-{random}
        if (!$sertifikat && preg_match('/^SC2-(\d+)-/i', $cleanToken, $m)) {
            $anggota = AnggotaRegu::with('reguProfile')->find($m[1]);
            if ($anggota && Schema::hasTable('sertifikat_peserta')) {
                $sertifikat = SertifikatPeserta::with(['anggotaRegu.reguProfile'])
                    ->where('anggota_regu_id', $anggota->id)
                    ->first();
            }
        }

        // 3. Fallback pencarian nomor sertifikat persis atau LIKE
        if (!$sertifikat && Schema::hasTable('sertifikat_peserta')) {
            $decoded = urldecode($cleanToken);
            $sertifikat = SertifikatPeserta::with(['anggotaRegu.reguProfile'])
                ->where('nomor_sertifikat', $decoded)
                ->orWhere('nomor_sertifikat', 'LIKE', '%' . $decoded . '%')
                ->orWhere('id', $cleanToken)
                ->first();
        }

        // 4. Jika ditemukan dari sertifikat peserta
        if ($certType === 'peserta' && $sertifikat && $sertifikat->anggotaRegu) {
            $anggota = $sertifikat->anggotaRegu;
            $regu = $anggota->reguProfile;
            $isValid = true;
        }

        // 5. Fallback ke ID anggota atau NISN
        if (!$isValid && is_numeric($cleanToken)) {
            $anggota = AnggotaRegu::with('reguProfile')
                ->where('id', $cleanToken)
                ->orWhere('nisn', $cleanToken)
                ->first();

            if ($anggota) {
                $regu = $anggota->reguProfile;
                $isValid = true;
                if (Schema::hasTable('sertifikat_peserta')) {
                    $sertifikat = SertifikatPeserta::with(['anggotaRegu.reguProfile'])
                        ->where('anggota_regu_id', $anggota->id)
                        ->first();
                }
            }
        }

        // 6. Jika valid tetapi record tabel sertifikat belum tersimpan
        if ($isValid && $anggota && !$sertifikat) {
            $counter = $anggota->urutan ?? $anggota->id;
            $format = Setting::where('key', 'cert_number_format_peserta')->value('value') ?? '{NOMOR}/SC-II/SERTIFIKAT/{BULAN}/{TAHUN}';
            
            $bulanRomawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $bulan = $bulanRomawi[date('n') - 1];
            $tahun = date('Y');
            $nomorStr = str_pad($counter, 3, '0', STR_PAD_LEFT);
            $reguCode = $regu ? ($regu->nomor_regu ?? 'REGU') : 'REGU';

            $genNomor = str_replace(
                ['{NOMOR}', '{BULAN}', '{TAHUN}', '{REGU}'],
                [$nomorStr, $bulan, $tahun, $reguCode],
                $format
            );

            $sertifikat = (object)[
                'nomor_sertifikat' => $genNomor,
                'diterbitkan_pada' => now()->toDateString(),
                'verify_token' => $cleanToken,
            ];
        }

        // Jika request via AJAX / JSON
        if ($request && ($request->wantsJson() || $request->has('json'))) {
            if ($isValid && ($anggota || $panitia || $juara)) {
                return response()->json([
                    'success' => true,
                    'is_valid' => true,
                    'type' => $certType,
                    'data' => [
                        'nama' => $panitia?->nama ?? $juara?->nama_penerima ?? $anggota?->nama ?? '-',
                        'jabatan' => $panitia?->jabatan ?? ($juara ? trim($juara->peringkat . ' • ' . $juara->nama_lomba) : strtoupper($anggota?->jabatan ?? 'ANGGOTA')),
                        'nomor_sertifikat' => $sertifikat->nomor_sertifikat ?? '-',
                        'regu' => $regu ? $regu->nama_regu : '-',
                    ]
                ]);
            }
            return response()->json([
                'success' => false,
                'is_valid' => false,
                'message' => 'Sertifikat tidak ditemukan atau kode tidak valid.'
            ], 404);
        }

        return view('verifikasi-sertifikat', [
            'isSearch' => false,
            'isValid' => $isValid,
            'certType' => $certType,
            'sertifikat' => $sertifikat,
            'anggota' => $anggota,
            'regu' => $regu,
            'panitia' => $panitia,
            'juara' => $juara,
            'token' => $cleanToken,
        ]);
    }
}
