<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaRegu;
use App\Models\ReguProfile;
use App\Models\SertifikatPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminVerifikasiQrController extends Controller
{
    /**
     * Halaman Scanner & Verifikasi QR ID Card Admin
     */
    public function index()
    {
        $recentVerifications = AnggotaRegu::whereNotNull('verified_at')
            ->with('reguProfile')
            ->orderByDesc('verified_at')
            ->take(8)
            ->get();

        $stats = [
            'total_anggota' => AnggotaRegu::count(),
            'total_verified' => AnggotaRegu::whereNotNull('verified_at')->count(),
        ];

        return view('admin.verifikasi-qr.index', compact('recentVerifications', 'stats'));
    }

    /**
     * Endpoint API lookup data anggota dari hasil scan QR dengan fitur OTOMATIS CHECK-IN
     */
    public function lookup(Request $request)
    {
        $input = trim($request->input('code', ''));

        if (empty($input)) {
            return response()->json(['success' => false, 'message' => 'Kode atau URL scan tidak boleh kosong.'], 422);
        }

        $anggota = null;

        // 1. Cek jika input adalah URL verifikasi-anggota/{id}
        if (preg_match('~verifikasi-anggota/(\d+)~i', $input, $matches)) {
            $anggota = AnggotaRegu::with('reguProfile')->find($matches[1]);
        }
        // 2. Cek jika input adalah URL verifikasi-sertifikat/{token}
        elseif (preg_match('~verifikasi-sertifikat/([^/?#]+)~i', $input, $matches)) {
            $sToken = $matches[1];
            if (preg_match('/^SC2-(\d+)-/i', $sToken, $sMatches)) {
                $anggota = AnggotaRegu::with('reguProfile')->find($sMatches[1]);
            } elseif (Schema::hasTable('sertifikat_peserta')) {
                $cert = SertifikatPeserta::where('verify_token', $sToken)
                    ->orWhere('nomor_sertifikat', urldecode($sToken))
                    ->first();
                if ($cert) {
                    $anggota = AnggotaRegu::with('reguProfile')->find($cert->anggota_regu_id);
                }
            }
        }
        // 3. Cek jika input adalah URL verifikasi-regu/{id}
        elseif (preg_match('~verifikasi-regu/(\d+)~i', $input, $matches)) {
            $regu = ReguProfile::with('anggotaRegu')->find($matches[1]);
            if ($regu && $regu->anggotaRegu->isNotEmpty()) {
                $anggota = $regu->anggotaRegu->first();
            }
        }
        // 4. Cek jika input berformat token sertifikat langsung (misal SC2-5-...)
        elseif (preg_match('/^SC2-(\d+)-/i', $input, $matches)) {
            $anggota = AnggotaRegu::with('reguProfile')->find($matches[1]);
        }
        // 5. Cek jika input adalah ID numerik langsung
        elseif (is_numeric($input)) {
            $anggota = AnggotaRegu::with('reguProfile')->find($input);
        }
        // 6. Cek jika input adalah NISN
        if (!$anggota) {
            $anggota = AnggotaRegu::with('reguProfile')->where('nisn', $input)->first();
        }
        // 7. Cek jika input adalah nama
        if (!$anggota) {
            $anggota = AnggotaRegu::with('reguProfile')->where('nama', 'like', "%{$input}%")->first();
        }

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Data peserta tidak ditemukan dalam sistem resmi Spencerone Camp.',
            ], 404);
        }

        // OTOMATIS CHECK-IN SAAT DI-SCAN:
        // Jika belum check-in, langsung tandai hadir otomatis!
        $justCheckedIn = false;
        if (is_null($anggota->verified_at)) {
            $anggota->verified_at = now();
            $anggota->save();
            $justCheckedIn = true;
            $message = 'Peserta berhasil check-in otomatis!';
        } else {
            $message = 'Peserta sudah terverifikasi hadir sebelumnya.';
        }

        $regu = $anggota->reguProfile;

        // Ambil riwayat terbaru & stats untuk auto-refresh
        $recentVerifications = AnggotaRegu::whereNotNull('verified_at')
            ->with('reguProfile')
            ->orderByDesc('verified_at')
            ->take(8)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'jabatan' => strtoupper($item->jabatan ?? 'ANGGOTA'),
                    'nama_regu' => $item->reguProfile->nama_regu ?? '-',
                    'foto_url' => $item->foto_path ? asset('storage/' . $item->foto_path) : null,
                    'verified_time' => $item->verified_at ? $item->verified_at->format('H:i') : '',
                ];
            });

        $stats = [
            'total_anggota' => AnggotaRegu::count(),
            'total_verified' => AnggotaRegu::whereNotNull('verified_at')->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => $message,
            'just_checked_in' => $justCheckedIn,
            'data' => [
                'id' => $anggota->id,
                'nama' => $anggota->nama,
                'nisn' => $anggota->nisn ?? '-',
                'kelas' => $anggota->kelas ? 'Kelas ' . $anggota->kelas : '-',
                'tku' => $anggota->tingkatan_tku ? strtoupper($anggota->tingkatan_tku) : '-',
                'jabatan' => strtoupper($anggota->jabatan ?? 'ANGGOTA'),
                'foto_url' => $anggota->foto_path ? asset('storage/' . $anggota->foto_path) : null,
                'is_verified' => !is_null($anggota->verified_at),
                'verified_at' => $anggota->verified_at ? $anggota->verified_at->translatedFormat('d F Y, H:i') : null,
                'regu' => [
                    'id' => $regu ? $regu->id : null,
                    'nama_regu' => $regu ? $regu->nama_regu : '-',
                    'jenis' => $regu ? ucfirst($regu->jenis) : '-',
                    'gudep' => $regu && $regu->jenis === 'putri' ? 'Gudep 03.060' : 'Gudep 03.059',
                    'sekolah' => $regu ? ($regu->sekolah ?? 'SMPN 1 Cerbon') : '-',
                    'nomor_regu' => $regu ? $regu->nomor_regu : '-',
                    'nomor_kavling' => $regu ? ($regu->nomor_kavling ?? 'Kavling Tenda') : '-',
                ],
            ],
            'stats' => $stats,
            'recent' => $recentVerifications,
        ]);
    }

    /**
     * Batalkan check-in peserta (jika salah scan atau dibatalkan admin)
     */
    public function cancelCheckIn($id)
    {
        $anggota = AnggotaRegu::with('reguProfile')->findOrFail($id);
        $anggota->verified_at = null;
        $anggota->save();

        $stats = [
            'total_anggota' => AnggotaRegu::count(),
            'total_verified' => AnggotaRegu::whereNotNull('verified_at')->count(),
        ];

        $recentVerifications = AnggotaRegu::whereNotNull('verified_at')
            ->with('reguProfile')
            ->orderByDesc('verified_at')
            ->take(8)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'jabatan' => strtoupper($item->jabatan ?? 'ANGGOTA'),
                    'nama_regu' => $item->reguProfile->nama_regu ?? '-',
                    'foto_url' => $item->foto_path ? asset('storage/' . $item->foto_path) : null,
                    'verified_time' => $item->verified_at ? $item->verified_at->format('H:i') : '',
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Check-in peserta berhasil dibatalkan.',
            'is_verified' => false,
            'verified_at' => null,
            'stats' => $stats,
            'recent' => $recentVerifications,
        ]);
    }

    /**
     * Tandai / ulangi check-in manual
     */
    public function toggleCheckIn($id)
    {
        $anggota = AnggotaRegu::with('reguProfile')->findOrFail($id);

        if ($anggota->verified_at) {
            $anggota->verified_at = null;
            $statusText = 'Verifikasi dibatalkan';
        } else {
            $anggota->verified_at = now();
            $statusText = 'Peserta berhasil diverifikasi & check-in';
        }

        $anggota->save();

        $stats = [
            'total_anggota' => AnggotaRegu::count(),
            'total_verified' => AnggotaRegu::whereNotNull('verified_at')->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => $statusText,
            'is_verified' => !is_null($anggota->verified_at),
            'verified_at' => $anggota->verified_at ? $anggota->verified_at->translatedFormat('d F Y, H:i') : null,
            'stats' => $stats,
        ]);
    }
}
