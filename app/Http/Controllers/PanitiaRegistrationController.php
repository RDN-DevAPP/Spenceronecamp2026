<?php

namespace App\Http\Controllers;

use App\Models\PanitiaMember;
use App\Models\Season;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PanitiaRegistrationController extends Controller
{
    public function create(): View
    {
        $activeSeason = Season::where('is_active', true)->first();

        $jabatanPresets = [
            'Ketua Panitia',
            'Wakil Ketua Panitia',
            'Sekretaris',
            'Bendahara',
            'Koordinator Lomba',
            'Sie Acara',
            'Sie Perlengkapan',
            'Sie Konsumsi',
            'Sie Dokumentasi & IT',
            'Sie Keamanan & P3K',
            'Dewan Juri',
            'Pembina Pramuka',
            'Lainnya',
        ];

        return view('panitia.formulir', compact('activeSeason', 'jabatanPresets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|min:2|max:150',
            'jabatan' => 'required|string|max:100',
            'jabatan_custom' => 'nullable|string|max:100',
            'divisi' => 'nullable|string|max:100',
            'asal_instansi' => 'nullable|string|max:150',
            'no_hp' => 'nullable|string|max:30',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $jabatan = trim($request->input('jabatan'));
        if ($jabatan === 'Lainnya') {
            $custom = trim((string) $request->input('jabatan_custom', ''));
            if ($custom === '') {
                return back()->withErrors(['jabatan_custom' => 'Silakan isi nama jabatan kepanitiaan Anda.'])->withInput();
            }
            $jabatan = $custom;
        }

        $activeSeason = Season::where('is_active', true)->first();

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('panitia-fotos', 'public');
        }

        $member = PanitiaMember::create([
            'season_id' => $activeSeason?->id,
            'nama' => trim(preg_replace('/\s+/', ' ', $request->input('nama'))),
            'jabatan' => $jabatan,
            'divisi' => $request->filled('divisi') ? trim($request->input('divisi')) : null,
            'asal_instansi' => $request->filled('asal_instansi') ? trim($request->input('asal_instansi')) : 'SMP Negeri 1 Cerbon',
            'no_hp' => $request->filled('no_hp') ? trim($request->input('no_hp')) : null,
            'foto_path' => $fotoPath,
        ]);

        return redirect()
            ->route('panitia.preview', $member->id)
            ->with('success', 'Data ID Card & Sertifikat Panitia atas nama ' . $member->nama . ' berhasil disimpan!');
    }

    public function preview(PanitiaMember $panitia): View
    {
        $verifyUrl = route('verifikasi-panitia', $panitia->id);
        $qrCode = QrCodeService::generateDataUri($verifyUrl, 5);

        return view('panitia.preview', compact('panitia', 'qrCode', 'verifyUrl'));
    }

    public function downloadPdf(PanitiaMember $panitia)
    {
        $verifyUrl = route('verifikasi-panitia', $panitia->id);
        $qrCode = QrCodeService::generateDataUri($verifyUrl, 5);

        $logoDataUri = $this->fileToDataUri(public_path('images/season2/logo-season2.jpg'));
        $mascotDataUri = $this->fileToDataUri(public_path('images/season2/mascot-duo.png'));
        $fotoDataUri = $panitia->foto_path
            ? $this->fileToDataUri(storage_path('app/public/' . $panitia->foto_path))
            : null;

        $pdf = Pdf::loadView('pdf.id-card-panitia', [
            'panitia' => $panitia,
            'qrCode' => $qrCode,
            'verifyUrl' => $verifyUrl,
            'logoDataUri' => $logoDataUri,
            'mascotDataUri' => $mascotDataUri,
            'fotoDataUri' => $fotoDataUri,
        ])->setPaper('a4', 'portrait');

        $filename = 'ID-Card-Panitia-' . Str::slug($panitia->nama) . '.pdf';

        return $pdf->download($filename);
    }

    public function printSingle(PanitiaMember $panitia): View
    {
        $verificationUrl = route('verifikasi-panitia', $panitia->id);
        $cards = collect([[
            'panitia' => $panitia,
            'qr_code' => QrCodeService::generateDataUri($verificationUrl, 4),
            'verify_url' => $verificationUrl,
        ]]);

        return view('admin.id-cards.print-panitia', [
            'cards' => $cards,
            'title' => 'ID Card Panitia - ' . $panitia->nama,
            'backUrl' => route('panitia.preview', $panitia->id),
        ]);
    }

    private function fileToDataUri(?string $path): ?string
    {
        if (!$path || !is_file($path)) {
            return null;
        }
        $mime = mime_content_type($path) ?: 'image/png';
        $data = base64_encode((string) file_get_contents($path));

        return 'data:' . $mime . ';base64,' . $data;
    }

    public function verify(int $id): View
    {
        $panitia = PanitiaMember::findOrFail($id);
        return view('verifikasi-panitia', compact('panitia'));
    }
}
