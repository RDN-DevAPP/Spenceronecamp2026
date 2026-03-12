<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\ReguProfile;
use App\Models\AnggotaRegu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminRandomizeReguController extends Controller
{
    public function index(): View
    {
        $countPutraPerKelas = [
            7 => Siswa::where('jenis_kelamin', 'L')->where('kelas', 7)->count(),
            8 => Siswa::where('jenis_kelamin', 'L')->where('kelas', 8)->count(),
            9 => Siswa::where('jenis_kelamin', 'L')->where('kelas', 9)->count(),
        ];
        $countPutriPerKelas = [
            7 => Siswa::where('jenis_kelamin', 'P')->where('kelas', 7)->count(),
            8 => Siswa::where('jenis_kelamin', 'P')->where('kelas', 8)->count(),
            9 => Siswa::where('jenis_kelamin', 'P')->where('kelas', 9)->count(),
        ];

        $totalPutra = array_sum($countPutraPerKelas);
        $totalPutri = array_sum($countPutriPerKelas);

        // Existing randomized regus (if any)
        $reguPutra = ReguProfile::where('jenis', 'putra')
            ->with('anggotaRegu')
            ->orderBy('nomor_regu')
            ->get();

        $reguPutri = ReguProfile::where('jenis', 'putri')
            ->with('anggotaRegu')
            ->orderBy('nomor_regu')
            ->get();

        // Check if randomization has been done
        $isRandomized = Siswa::whereNotNull('regu_profile_id')->exists();

        return view('admin.randomize-regu.index', compact(
            'countPutraPerKelas',
            'countPutriPerKelas',
            'totalPutra',
            'totalPutri',
            'reguPutra',
            'reguPutri',
            'isRandomized'
        ));
    }

    public function randomize(Request $request): RedirectResponse
    {
        $request->validate([
            'jumlah_regu_putra' => 'required|integer|min:1',
            'jumlah_regu_putri' => 'required|integer|min:1',
        ]);

        $jumlahReguPutra = (int) $request->jumlah_regu_putra;
        $jumlahReguPutri = (int) $request->jumlah_regu_putri;

        // Validate we have enough students
        $siswaPutra = Siswa::where('jenis_kelamin', 'L')->get();
        $siswaPutri = Siswa::where('jenis_kelamin', 'P')->get();

        // Check minimum per regu (7) and maximum (10)
        if ($siswaPutra->count() < $jumlahReguPutra * 7) {
            return back()->with('error', "Jumlah siswa putra ({$siswaPutra->count()}) tidak cukup untuk {$jumlahReguPutra} regu (minimal " . ($jumlahReguPutra * 7) . " siswa).")->withInput();
        }
        if ($siswaPutra->count() > $jumlahReguPutra * 10) {
            return back()->with('error', "Jumlah siswa putra ({$siswaPutra->count()}) terlalu banyak untuk {$jumlahReguPutra} regu (maksimal " . ($jumlahReguPutra * 10) . " siswa).")->withInput();
        }
        if ($siswaPutri->count() < $jumlahReguPutri * 7) {
            return back()->with('error', "Jumlah siswa putri ({$siswaPutri->count()}) tidak cukup untuk {$jumlahReguPutri} regu (minimal " . ($jumlahReguPutri * 7) . " siswa).")->withInput();
        }
        if ($siswaPutri->count() > $jumlahReguPutri * 10) {
            return back()->with('error', "Jumlah siswa putri ({$siswaPutri->count()}) terlalu banyak untuk {$jumlahReguPutri} regu (maksimal " . ($jumlahReguPutri * 10) . " siswa).")->withInput();
        }

        // Check we have students from each class
        foreach ([7, 8, 9] as $kelas) {
            if ($siswaPutra->where('kelas', $kelas)->count() < $jumlahReguPutra) {
                return back()->with('error', "Jumlah siswa putra kelas {$kelas} tidak cukup. Minimal {$jumlahReguPutra} siswa (1 per regu).")->withInput();
            }
            if ($siswaPutri->where('kelas', $kelas)->count() < $jumlahReguPutri) {
                return back()->with('error', "Jumlah siswa putri kelas {$kelas} tidak cukup. Minimal {$jumlahReguPutri} siswa (1 per regu).")->withInput();
            }
        }

        try {
            DB::beginTransaction();

            // Clear existing randomization
            $this->clearRandomization();

            // Randomize putra
            $this->assignStudentsToRegus($siswaPutra, $jumlahReguPutra, 'putra');

            // Randomize putri
            $this->assignStudentsToRegus($siswaPutri, $jumlahReguPutri, 'putri');

            DB::commit();

            return back()->with('success', "Pengacakan regu berhasil! {$jumlahReguPutra} regu putra dan {$jumlahReguPutri} regu putri telah dibuat.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    private function assignStudentsToRegus($siswaCollection, int $jumlahRegu, string $jenis): void
    {
        // Group by kelas and shuffle each group
        $perKelas = [];
        foreach ([9, 8, 7] as $kelas) {
            $perKelas[$kelas] = $siswaCollection->where('kelas', $kelas)->shuffle()->values();
        }

        // Create regu profiles
        $regus = [];
        for ($i = 1; $i <= $jumlahRegu; $i++) {
            $nomorRegu = ($jenis === 'putra') ? (2 * $i - 1) : (2 * $i);
            $regu = ReguProfile::create([
                'user_id' => User::where('role', 'admin')->first()->id, // temporary owner
                'nama_regu' => 'Regu ' . ucfirst($jenis) . ' ' . $i,
                'jenis' => $jenis,
                'nomor_regu' => $nomorRegu,
            ]);
            $regus[$i] = [
                'profile' => $regu,
                'members' => [],
                'kelas_count' => [7 => 0, 8 => 0, 9 => 0],
            ];
        }

        // Phase 1: Distribute round-robin per kelas to ensure each regu has at least one from each class
        $reguIndex = 0;
        foreach ([9, 8, 7] as $kelas) {
            $students = $perKelas[$kelas];

            foreach ($students as $siswa) {
                $reguNumber = ($reguIndex % $jumlahRegu) + 1;
                $regus[$reguNumber]['members'][] = $siswa;
                $regus[$reguNumber]['kelas_count'][$kelas]++;
                $reguIndex++;
            }
        }

        // Phase 2: Save to database
        foreach ($regus as $reguData) {
            $urutan = 1;
            foreach ($reguData['members'] as $siswa) {
                $jabatan = 'anggota';
                if ($urutan === 1) {
                    $jabatan = 'pinru';
                } elseif ($urutan === 2) {
                    $jabatan = 'wapinru';
                }

                AnggotaRegu::create([
                    'regu_profile_id' => $reguData['profile']->id,
                    'nama' => $siswa->nama,
                    'kelas' => $siswa->kelas,
                    'urutan' => $urutan,
                    'jabatan' => $jabatan,
                ]);

                // Update siswa reference
                $siswa->update(['regu_profile_id' => $reguData['profile']->id]);

                $urutan++;
            }
        }
    }

    private function clearRandomization(): void
    {
        // Reset all siswa regu_profile_id
        Siswa::whereNotNull('regu_profile_id')->update(['regu_profile_id' => null]);

        // Delete all AnggotaRegu
        AnggotaRegu::truncate();

        // Delete all ReguProfile
        ReguProfile::truncate();
    }

    public function reset(): RedirectResponse
    {
        DB::transaction(function () {
            Siswa::whereNotNull('regu_profile_id')->update(['regu_profile_id' => null]);
            AnggotaRegu::truncate();
            ReguProfile::truncate();
        });

        return redirect()->back()->with('success', 'Semua data pengacakan regu telah direset.');
    }

    public function getTeamMembers($id)
    {
        $regu = ReguProfile::with([
            'anggotaRegu' => function ($query) {
                $query->orderBy('urutan');
            }
        ])->findOrFail($id);

        return response()->json([
            'nama_regu' => $regu->nama_regu,
            'members' => $regu->anggotaRegu
        ]);
    }
}
