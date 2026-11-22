<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaRegu;
use App\Models\CerdasCermatSession;
use App\Models\MataLomba;
use App\Models\PanitiaMember;
use App\Models\ReguProfile;
use App\Models\Score;
use App\Models\Season;
use App\Models\SertifikatJuara;
use App\Models\Setting;
use App\Models\SertifikatPeserta;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminSertifikatController extends Controller
{
    /**
     * Halaman Default: Arahkan langsung ke Sertifikat Peserta
     */
    public function index(Request $request)
    {
        return redirect()->route('admin.sertifikat.peserta');
    }

    /**
     * Halaman Manajemen & Penerbitan Sertifikat (Peserta, Panitia, Juara Lomba)
     */
    public function peserta(Request $request)
    {
        $activeSeason = Season::where('is_active', true)->first();
        
        $regus = ReguProfile::with(['anggotaRegu' => function($q) {
            $q->orderByRaw("CASE WHEN jabatan = 'pinru' THEN 1 WHEN jabatan = 'wapinru' THEN 2 ELSE 3 END")
              ->orderBy('urutan')
              ->with('sertifikatPeserta');
        }])
        ->when($activeSeason, fn($q) => $q->where('season_id', $activeSeason->id))
        ->orderBy('jenis')
        ->orderBy('nama_regu')
        ->get();

        $selectedRegu = null;
        if ($request->filled('regu_id')) {
            $selectedRegu = $regus->firstWhere('id', $request->regu_id);
        }

        $panitiaList = Schema::hasTable('panitia_members')
            ? PanitiaMember::when($activeSeason, fn($q) => $q->where(function ($sub) use ($activeSeason) {
                $sub->where('season_id', $activeSeason->id)->orWhereNull('season_id');
            }))->orderBy('nama')->get()
            : collect();

        $mataLombaList = MataLomba::when($activeSeason, fn($q) => $q->where('season_id', $activeSeason->id))
            ->orderBy('urutan')
            ->get();

        $selectedLomba = null;
        if ($request->filled('lomba_id')) {
            $selectedLomba = $mataLombaList->firstWhere('id', $request->lomba_id);
        } elseif ($mataLombaList->isNotEmpty()) {
            $selectedLomba = $mataLombaList->first();
        }

        $lombaWinners = $selectedLomba
            ? $this->getLombaWinnersData($selectedLomba, $regus)
            : ['putra' => [], 'putri' => []];

        $activeScope = in_array($request->input('scope'), ['peserta', 'panitia', 'juara'], true)
            ? $request->input('scope')
            : 'peserta';

        $settings = $this->getCertificateSettings($activeScope);

        return view('admin.sertifikat.peserta', compact(
            'regus',
            'selectedRegu',
            'panitiaList',
            'mataLombaList',
            'selectedLomba',
            'lombaWinners',
            'activeScope',
            'settings',
            'activeSeason'
        ));
    }

    /**
     * Simpan Pengaturan Template Sertifikat & Posisi Elemen per Jenis (peserta | panitia | juara)
     */
    public function updateSettings(Request $request)
    {
        $scope = in_array($request->input('scope'), ['peserta', 'panitia', 'juara'], true)
            ? $request->input('scope')
            : 'peserta';

        $request->validate([
            'scope' => 'nullable|in:peserta,panitia,juara',
            'template_peserta' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'delete_template_peserta' => 'nullable|boolean',
            'cert_number_format_peserta' => 'nullable|string|max:100',
            'cert_counter_peserta' => 'nullable|integer|min:1',
            'cert_ttd_count' => 'nullable|integer|min:0|max:4',
            'cert_show_ttd' => 'nullable|string|in:0,1',
            'cert_show_qr' => 'nullable|string|in:0,1',
            'signature_file' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'delete_signature' => 'nullable|boolean',
            'signature_file_1' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'signature_file_2' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'signature_file_3' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'signature_file_4' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'delete_signature_1' => 'nullable|boolean',
            'delete_signature_2' => 'nullable|boolean',
            'delete_signature_3' => 'nullable|boolean',
            'delete_signature_4' => 'nullable|boolean',
            'cert_ttd_label_1' => 'nullable|string|max:60',
            'cert_ttd_label_2' => 'nullable|string|max:60',
            'cert_ttd_label_3' => 'nullable|string|max:60',
            'cert_ttd_label_4' => 'nullable|string|max:60',
            'cert_pos_nomor_top' => 'nullable|numeric|between:0,100',
            'cert_pos_nomor_left' => 'nullable|numeric|between:0,100',
            'cert_pos_nomor_width' => 'nullable|numeric|between:5,100',
            'cert_pos_nomor_align' => 'nullable|string|in:left,center,right',
            'cert_pos_nomor_size' => 'nullable|numeric|between:6,40',
            'cert_pos_nama_top' => 'nullable|numeric|between:0,100',
            'cert_pos_nama_left' => 'nullable|numeric|between:0,100',
            'cert_pos_nama_width' => 'nullable|numeric|between:5,100',
            'cert_pos_nama_align' => 'nullable|string|in:left,center,right',
            'cert_pos_nama_size' => 'nullable|numeric|between:10,60',
            'cert_pos_ttd_top' => 'nullable|numeric|between:0,100',
            'cert_pos_ttd_left' => 'nullable|numeric|between:0,100',
            'cert_pos_ttd_width' => 'nullable|numeric|between:5,50',
            'cert_pos_ttd2_top' => 'nullable|numeric|between:0,100',
            'cert_pos_ttd2_left' => 'nullable|numeric|between:0,100',
            'cert_pos_ttd2_width' => 'nullable|numeric|between:5,50',
            'cert_pos_ttd3_top' => 'nullable|numeric|between:0,100',
            'cert_pos_ttd3_left' => 'nullable|numeric|between:0,100',
            'cert_pos_ttd3_width' => 'nullable|numeric|between:5,50',
            'cert_pos_ttd4_top' => 'nullable|numeric|between:0,100',
            'cert_pos_ttd4_left' => 'nullable|numeric|between:0,100',
            'cert_pos_ttd4_width' => 'nullable|numeric|between:5,50',
            'cert_pos_qr_top' => 'nullable|numeric|between:0,100',
            'cert_pos_qr_left' => 'nullable|numeric|between:0,100',
            'cert_pos_qr_width' => 'nullable|numeric|between:4,35',
            'cert_show_ket' => 'nullable|string|in:0,1',
            'cert_pos_ket_top' => 'nullable|numeric|between:0,100',
            'cert_pos_ket_left' => 'nullable|numeric|between:0,100',
            'cert_pos_ket_width' => 'nullable|numeric|between:5,100',
            'cert_pos_ket_align' => 'nullable|string|in:left,center,right',
            'cert_pos_ket_size' => 'nullable|numeric|between:8,36',
            'cert_show_lomba' => 'nullable|string|in:0,1',
            'cert_pos_lomba_top' => 'nullable|numeric|between:0,100',
            'cert_pos_lomba_left' => 'nullable|numeric|between:0,100',
            'cert_pos_lomba_width' => 'nullable|numeric|between:5,100',
            'cert_pos_lomba_align' => 'nullable|string|in:left,center,right',
            'cert_pos_lomba_size' => 'nullable|numeric|between:8,36',
        ]);

        if ($request->filled('cert_number_format_peserta')) {
            Setting::updateOrCreate(['key' => $this->scopeKey($scope, 'number_format')], ['value' => $request->cert_number_format_peserta]);
        }

        if ($request->filled('cert_counter_peserta')) {
            Setting::updateOrCreate(['key' => $this->scopeKey($scope, 'counter')], ['value' => $request->cert_counter_peserta]);
        }

        // Simpan posisi & tata letak elemen untuk scope ini
        $posMap = [
            'cert_pos_nomor_top' => 'pos_nomor_top',
            'cert_pos_nomor_left' => 'pos_nomor_left',
            'cert_pos_nomor_width' => 'pos_nomor_width',
            'cert_pos_nomor_align' => 'pos_nomor_align',
            'cert_pos_nomor_size' => 'pos_nomor_size',
            'cert_pos_nama_top' => 'pos_nama_top',
            'cert_pos_nama_left' => 'pos_nama_left',
            'cert_pos_nama_width' => 'pos_nama_width',
            'cert_pos_nama_align' => 'pos_nama_align',
            'cert_pos_nama_size' => 'pos_nama_size',
            'cert_pos_ttd_top' => 'pos_ttd_top',
            'cert_pos_ttd_left' => 'pos_ttd_left',
            'cert_pos_ttd_width' => 'pos_ttd_width',
            'cert_pos_ttd2_top' => 'pos_ttd2_top',
            'cert_pos_ttd2_left' => 'pos_ttd2_left',
            'cert_pos_ttd2_width' => 'pos_ttd2_width',
            'cert_pos_ttd3_top' => 'pos_ttd3_top',
            'cert_pos_ttd3_left' => 'pos_ttd3_left',
            'cert_pos_ttd3_width' => 'pos_ttd3_width',
            'cert_pos_ttd4_top' => 'pos_ttd4_top',
            'cert_pos_ttd4_left' => 'pos_ttd4_left',
            'cert_pos_ttd4_width' => 'pos_ttd4_width',
            'cert_pos_qr_top' => 'pos_qr_top',
            'cert_pos_qr_left' => 'pos_qr_left',
            'cert_pos_qr_width' => 'pos_qr_width',
            'cert_pos_ket_top' => 'pos_ket_top',
            'cert_pos_ket_left' => 'pos_ket_left',
            'cert_pos_ket_width' => 'pos_ket_width',
            'cert_pos_ket_align' => 'pos_ket_align',
            'cert_pos_ket_size' => 'pos_ket_size',
            'cert_pos_lomba_top' => 'pos_lomba_top',
            'cert_pos_lomba_left' => 'pos_lomba_left',
            'cert_pos_lomba_width' => 'pos_lomba_width',
            'cert_pos_lomba_align' => 'pos_lomba_align',
            'cert_pos_lomba_size' => 'pos_lomba_size',
        ];

        foreach ($posMap as $inputName => $logicalKey) {
            if ($request->filled($inputName)) {
                Setting::updateOrCreate(['key' => $this->scopeKey($scope, $logicalKey)], ['value' => $request->input($inputName)]);
            }
        }

        // Jumlah TTD (0-4) dan sinkronisasi flag show_ttd
        if ($request->has('cert_ttd_count')) {
            $ttdCount = max(0, min(4, (int) $request->input('cert_ttd_count', 1)));
        } else {
            $ttdCount = $request->input('cert_show_ttd', '0') == '1' ? 1 : 0;
        }
        Setting::updateOrCreate(['key' => $this->scopeKey($scope, 'ttd_count')], ['value' => (string) $ttdCount]);
        Setting::updateOrCreate(['key' => $this->scopeKey($scope, 'show_ttd')], ['value' => $ttdCount > 0 ? '1' : '0']);

        // Tampilkan / sembunyikan QR code, keterangan, dan nama lomba (juara)
        Setting::updateOrCreate(
            ['key' => $this->scopeKey($scope, 'show_qr')],
            ['value' => $request->input('cert_show_qr', '0') == '1' ? '1' : '0']
        );
        Setting::updateOrCreate(
            ['key' => $this->scopeKey($scope, 'show_ket')],
            ['value' => $request->input('cert_show_ket', '0') == '1' ? '1' : '0']
        );
        if ($scope === 'juara') {
            Setting::updateOrCreate(
                ['key' => $this->scopeKey($scope, 'show_lomba')],
                ['value' => $request->input('cert_show_lomba', '0') == '1' ? '1' : '0']
            );
        }

        // Simpan label TTD 1..4
        for ($i = 1; $i <= 4; $i++) {
            $labelInput = "cert_ttd_label_{$i}";
            if ($request->filled($labelInput)) {
                Setting::updateOrCreate(
                    ['key' => $this->scopeKey($scope, "ttd_label_{$i}")],
                    ['value' => trim((string) $request->input($labelInput))]
                );
            }
        }

        // Upload Template Baru untuk scope ini (PDF maupun PNG/JPG)
        $templateSettingKey = $this->scopeKey($scope, 'template');
        if ($request->hasFile('template_peserta')) {
            $path = $this->handleUploadedTemplateFile($request->file('template_peserta'), $scope);
            Setting::updateOrCreate(['key' => $templateSettingKey], ['value' => $path]);
        }

        // Hapus template scope ini jika diminta
        if ($request->filled('delete_template_peserta')) {
            Setting::updateOrCreate(['key' => $templateSettingKey], ['value' => null]);
        }

        // Simpan Tanda Tangan Digital untuk slot 1..4 (Canvas base64 atau file upload) pada scope ini
        for ($i = 1; $i <= 4; $i++) {
            $settingKey = $this->scopeKey($scope, "signature_{$i}");
            $dataInput = $i === 1
                ? ($request->input('signature_data_1') ?: $request->input('signature_data'))
                : $request->input("signature_data_{$i}");
            $fileInput = $request->file("signature_file_{$i}") ?: ($i === 1 ? $request->file('signature_file') : null);
            $deleteFlag = $request->boolean("delete_signature_{$i}") || ($i === 1 && $request->boolean('delete_signature'));

            if (!empty($dataInput) && preg_match('/^data:image\/(\w+);base64,/', $dataInput)) {
                $base64 = substr($dataInput, strpos($dataInput, ',') + 1);
                $decoded = base64_decode($base64);
                if ($decoded !== false) {
                    $sigDir = storage_path('app/public/certificates/signatures');
                    if (!file_exists($sigDir)) {
                        @mkdir($sigDir, 0755, true);
                    }
                    $filename = "signature-{$scope}-{$i}-" . time() . '.png';
                    file_put_contents($sigDir . '/' . $filename, $decoded);
                    Setting::updateOrCreate(['key' => $settingKey], ['value' => 'certificates/signatures/' . $filename]);
                }
            } elseif ($fileInput) {
                $filename = "signature-{$scope}-{$i}-" . time() . '.' . $fileInput->getClientOriginalExtension();
                $path = $fileInput->storeAs('certificates/signatures', $filename, 'public');
                Setting::updateOrCreate(['key' => $settingKey], ['value' => $path]);
            }

            if ($deleteFlag) {
                Setting::updateOrCreate(['key' => $settingKey], ['value' => null]);
            }
        }

        $scopeNames = ['peserta' => 'Sertifikat Peserta', 'panitia' => 'Sertifikat Panitia', 'juara' => 'Sertifikat Juara Lomba'];
        return redirect()
            ->route('admin.sertifikat.peserta', ['tab' => 'pengaturan', 'scope' => $scope])
            ->with('success', 'Pengaturan ' . ($scopeNames[$scope] ?? 'Sertifikat') . ' berhasil disimpan!');
    }

    /**
     * Memproses file template yang diunggah. Jika berupa PDF, dikonversi halaman pertamanya ke PNG resolusi tinggi.
     */
    private function handleUploadedTemplateFile($file, string $type): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $time = time();

        if ($ext === 'pdf') {
            $pdfFilename = "template-{$type}-{$time}.pdf";
            $pdfRelPath = $file->storeAs('certificates/templates', $pdfFilename, 'public');
            $pdfFullPath = storage_path('app/public/' . $pdfRelPath);

            $pngFilename = "template-{$type}-{$time}.png";
            $pngFullPath = storage_path('app/public/certificates/templates/' . $pngFilename);

            // Jalankan deteksi rumus otomatis [$nomor$], [$nama$]
            $scannerScript = app_path('Services/pdf_tag_scanner.py');
            if (file_exists($scannerScript)) {
                $pyCommands = ['python3', 'python'];
                $result = null;
                foreach ($pyCommands as $pyCmd) {
                    $cmd = "{$pyCmd} \"{$scannerScript}\" \"{$pdfFullPath}\" \"{$pngFullPath}\" 2>&1";
                    $out = [];
                    @exec($cmd, $out, $ret);
                    if (!empty($out)) {
                        $jsonStr = end($out);
                        $res = json_decode($jsonStr, true);
                        if (!empty($res['status']) && $res['status'] === 'success') {
                            $result = $res;
                            break;
                        }
                    }
                }

                if ($result && !empty($result['status']) && $result['status'] === 'success') {
                    if (file_exists($pngFullPath)) {
                        return 'certificates/templates/' . $pngFilename;
                    }
                }
            }

            // Fallback konversi PDF standar
            $converted = $this->convertPdfToPng($pdfFullPath, $pngFullPath);
            if ($converted && file_exists($pngFullPath)) {
                return 'certificates/templates/' . $pngFilename;
            }

            return $pdfRelPath;
        }

        $imgFilename = "template-{$type}-{$time}.{$ext}";
        return $file->storeAs('certificates/templates', $imgFilename, 'public');
    }

    /**
     * Konversi PDF ke PNG dengan multi-fallback
     */
    private function convertPdfToPng(string $pdfPath, string $pngPath): bool
    {
        $pngDir = dirname($pngPath);
        if (!file_exists($pngDir)) {
            @mkdir($pngDir, 0755, true);
        }

        // 1. Python PyMuPDF
        $cmdPython = "python3 -c \"import fitz; doc=fitz.open(r'{$pdfPath}'); page=doc[0]; pix=page.get_pixmap(dpi=200); pix.save(r'{$pngPath}')\" 2>&1";
        @exec($cmdPython, $outPy, $resPy);
        if ($resPy === 0 && file_exists($pngPath)) return true;

        $cmdPython2 = "python -c \"import fitz; doc=fitz.open(r'{$pdfPath}'); page=doc[0]; pix=page.get_pixmap(dpi=200); pix.save(r'{$pngPath}')\" 2>&1";
        @exec($cmdPython2, $outPy2, $resPy2);
        if ($resPy2 === 0 && file_exists($pngPath)) return true;

        // 2. pdftoppm (Linux cPanel standard)
        $outPrefix = preg_replace('/\.png$/i', '', $pngPath);
        $cmdPdftoppm = "pdftoppm -png -r 200 -f 1 -l 1 \"{$pdfPath}\" \"{$outPrefix}\" 2>&1";
        @exec($cmdPdftoppm, $outPpm, $resPpm);
        $generatedPpm = $outPrefix . '-1.png';
        if (file_exists($generatedPpm)) {
            @rename($generatedPpm, $pngPath);
            return true;
        }

        // 3. Ghostscript
        $cmdGs = "gs -dNOPAUSE -sDEVICE=png16m -r200 -dFirstPage=1 -dLastPage=1 -sOutputFile=\"{$pngPath}\" \"{$pdfPath}\" -c quit 2>&1";
        @exec($cmdGs, $outGs, $resGs);
        if ($resGs === 0 && file_exists($pngPath)) return true;

        // 4. ImageMagick convert CLI
        $cmdConvert = "convert -density 200 \"{$pdfPath}[0]\" \"{$pngPath}\" 2>&1";
        @exec($cmdConvert, $outConv, $resConv);
        if ($resConv === 0 && file_exists($pngPath)) return true;

        // 5. Imagick PHP Extension
        if (extension_loaded('imagick')) {
            try {
                $im = new \Imagick();
                $im->setResolution(200, 200);
                $im->readImage($pdfPath . '[0]');
                $im->setImageFormat('png');
                $im->writeImage($pngPath);
                $im->clear();
                $im->destroy();
                return true;
            } catch (\Exception $e) {}
        }

        return false;
    }

    /**
     * Cetak Sertifikat Peserta Tunggal
     */
    public function downloadSingle($anggotaId, Request $request)
    {
        $anggota = AnggotaRegu::with('reguProfile')->findOrFail($anggotaId);
        $regu = $anggota->reguProfile;
        
        $settings = $this->getCertificateSettings('peserta');
        $formatNomor = $settings['cert_number_format_peserta'];
        $currentCounter = (int)($settings['cert_counter_peserta'] ?? 1);

        $certData = $this->getOrCreateSertifikatPeserta($anggota, $formatNomor, $currentCounter);

        $pesertaTemplatePath = $this->resolveTemplatePath($settings);
        $signaturePath = $this->resolveSignaturePath($settings);
        $signatures = $this->resolveSignatures($settings);

        $ketPeserta = ($settings['cert_show_ket'] ?? '0') == '1'
            ? strtoupper(trim(($anggota->jabatan ?: 'Anggota') . ($regu ? ' • Regu ' . $regu->nama_regu : '')))
            : null;

        $data = [
            'certificates' => [
                [
                    'anggota' => $anggota,
                    'regu' => $regu,
                    'keterangan' => $ketPeserta,
                    'nomor' => $certData['nomor'],
                    'qrCode' => $certData['qrCode'],
                    'verifyUrl' => $certData['verifyUrl'],
                ]
            ],
            'pesertaTemplatePath' => $pesertaTemplatePath,
            'signaturePath' => $signaturePath,
            'signatures' => $signatures,
            'settings' => $settings,
        ];

        $pdf = Pdf::loadView('pdf.sertifikat', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'Sertifikat-Peserta-' . Str::slug($anggota->nama) . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Cetak Seluruh Sertifikat Peserta dari 1 Regu (PDF Massal)
     */
    public function downloadRegu($reguId, Request $request)
    {
        $regu = ReguProfile::with(['anggotaRegu' => function($q) {
            $q->orderByRaw("CASE WHEN jabatan = 'pinru' THEN 1 WHEN jabatan = 'wapinru' THEN 2 ELSE 3 END")->orderBy('urutan');
        }])->findOrFail($reguId);

        $settings = $this->getCertificateSettings('peserta');
        $formatNomor = $settings['cert_number_format_peserta'];
        $currentCounter = (int)($settings['cert_counter_peserta'] ?? 1);

        $certificates = [];
        foreach ($regu->anggotaRegu as $anggota) {
            $certData = $this->getOrCreateSertifikatPeserta($anggota, $formatNomor, $currentCounter);
            $ketPeserta = ($settings['cert_show_ket'] ?? '0') == '1'
                ? strtoupper(trim(($anggota->jabatan ?: 'Anggota') . ' • Regu ' . $regu->nama_regu))
                : null;
            $certificates[] = [
                'anggota' => $anggota,
                'regu' => $regu,
                'keterangan' => $ketPeserta,
                'nomor' => $certData['nomor'],
                'qrCode' => $certData['qrCode'],
                'verifyUrl' => $certData['verifyUrl'],
            ];
        }

        $pesertaTemplatePath = $this->resolveTemplatePath($settings);
        $signaturePath = $this->resolveSignaturePath($settings);
        $signatures = $this->resolveSignatures($settings);

        $data = [
            'certificates' => $certificates,
            'pesertaTemplatePath' => $pesertaTemplatePath,
            'signaturePath' => $signaturePath,
            'signatures' => $signatures,
            'settings' => $settings,
        ];

        $pdf = Pdf::loadView('pdf.sertifikat', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'Sertifikat-Peserta-Regu-' . Str::slug($regu->nama_regu) . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Cetak Sertifikat Panitia Tunggal (memakai pengaturan khusus Sertifikat Panitia)
     */
    public function downloadPanitiaSingle(PanitiaMember $panitia)
    {
        $settings = $this->getCertificateSettings('panitia');
        $formatNomor = $settings['cert_number_format_peserta'];
        $currentCounter = (int)($settings['cert_counter_peserta'] ?? 1);

        $certData = $this->getOrCreateSertifikatPanitia($panitia, $formatNomor, $currentCounter);

        $certificates = [[
            'nama' => $panitia->nama,
            'keterangan' => ($settings['cert_show_ket'] ?? '1') == '1'
                ? trim($panitia->jabatan . ($panitia->divisi ? ' • ' . $panitia->divisi : ''))
                : null,
            'nomor' => $certData['nomor'],
            'qrCode' => $certData['qrCode'],
            'verifyUrl' => $certData['verifyUrl'],
        ]];

        $pdf = Pdf::loadView('pdf.sertifikat', [
            'certificates' => $certificates,
            'pesertaTemplatePath' => $this->resolveTemplatePath($settings),
            'signaturePath' => $this->resolveSignaturePath($settings),
            'signatures' => $this->resolveSignatures($settings),
            'settings' => $settings,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat-Panitia-' . Str::slug($panitia->nama) . '.pdf');
    }

    /**
     * Cetak Seluruh Sertifikat Panitia dari Data Formulir (PDF Massal)
     */
    public function downloadPanitiaAll()
    {
        $activeSeason = Season::where('is_active', true)->first();
        $list = PanitiaMember::when($activeSeason, fn($q) => $q->where(function ($sub) use ($activeSeason) {
            $sub->where('season_id', $activeSeason->id)->orWhereNull('season_id');
        }))->orderBy('nama')->get();

        if ($list->isEmpty()) {
            return redirect()->route('admin.sertifikat.peserta', ['tab' => 'panitia'])
                ->with('error', 'Belum ada data panitia dari formulir untuk diterbitkan sertifikatnya.');
        }

        $settings = $this->getCertificateSettings('panitia');
        $formatNomor = $settings['cert_number_format_peserta'];
        $currentCounter = (int)($settings['cert_counter_peserta'] ?? 1);

        $certificates = [];
        foreach ($list as $panitia) {
            $certData = $this->getOrCreateSertifikatPanitia($panitia, $formatNomor, $currentCounter);
            $certificates[] = [
                'nama' => $panitia->nama,
                'keterangan' => ($settings['cert_show_ket'] ?? '1') == '1'
                    ? trim($panitia->jabatan . ($panitia->divisi ? ' • ' . $panitia->divisi : ''))
                    : null,
                'nomor' => $certData['nomor'],
                'qrCode' => $certData['qrCode'],
                'verifyUrl' => $certData['verifyUrl'],
            ];
        }

        $pdf = Pdf::loadView('pdf.sertifikat', [
            'certificates' => $certificates,
            'pesertaTemplatePath' => $this->resolveTemplatePath($settings),
            'signaturePath' => $this->resolveSignaturePath($settings),
            'signatures' => $this->resolveSignatures($settings),
            'settings' => $settings,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat-Seluruh-Panitia.pdf');
    }

    /**
     * Cetak Sertifikat Juara Lomba (memakai pengaturan khusus Sertifikat Juara)
     */
    public function downloadJuaraLomba(Request $request)
    {
        $request->validate([
            'mata_lomba_id' => 'nullable|exists:mata_lomba,id',
            'nama_lomba' => 'nullable|string|max:150',
            'regu_id' => 'nullable|exists:regu_profiles,id',
            'nama_penerima' => 'nullable|string|max:180',
            'kategori' => 'nullable|string|max:50',
            'peringkat' => 'required|string|max:80',
            'mode' => 'nullable|in:regu,anggota,manual',
        ]);

        $lomba = $request->filled('mata_lomba_id') ? MataLomba::find($request->mata_lomba_id) : null;
        $namaLomba = $lomba ? $lomba->nama : trim((string) $request->input('nama_lomba', 'Kejuaraan Lomba'));
        $regu = $request->filled('regu_id') ? ReguProfile::with(['anggotaRegu' => fn($q) => $q->orderBy('urutan')])->find($request->regu_id) : null;

        $peringkat = trim($request->input('peringkat'));
        $kategori = trim((string) $request->input('kategori', $regu ? ucfirst($regu->jenis) : ''));
        $mode = $request->input('mode', $regu ? 'regu' : 'manual');

        $settings = $this->getCertificateSettings('juara');
        $formatNomor = $settings['cert_number_format_peserta'];
        $currentCounter = (int)($settings['cert_counter_peserta'] ?? 1);

        $certificates = [];
        $peringkatText = trim($peringkat . ($kategori ? ' (' . $kategori . ')' : ''));
        $showKet = ($settings['cert_show_ket'] ?? '1') == '1';
        $showLomba = ($settings['cert_show_lomba'] ?? '1') == '1';

        if ($mode === 'anggota' && $regu && $regu->anggotaRegu->isNotEmpty()) {
            foreach ($regu->anggotaRegu as $anggota) {
                $certData = $this->getOrCreateSertifikatJuara([
                    'season_id' => $regu->season_id,
                    'mata_lomba_id' => $lomba?->id,
                    'regu_profile_id' => $regu->id,
                    'anggota_regu_id' => $anggota->id,
                    'nama_penerima' => $anggota->nama,
                    'nama_lomba' => $namaLomba,
                    'kategori' => $kategori,
                    'peringkat' => $peringkat,
                    'mode' => 'anggota',
                ], $regu, $formatNomor, $currentCounter);

                $certificates[] = [
                    'nama' => $anggota->nama,
                    'keterangan' => $showKet ? $peringkatText : null,
                    'lomba' => $showLomba ? "{$namaLomba} • Regu {$regu->nama_regu}" : null,
                    'nomor' => $certData['nomor'],
                    'qrCode' => $certData['qrCode'],
                    'verifyUrl' => $certData['verifyUrl'],
                ];
            }
        } else {
            $namaPenerima = $regu ? ('Regu ' . $regu->nama_regu) : trim((string) $request->input('nama_penerima', ''));
            if ($namaPenerima === '') {
                return back()->with('error', 'Pilih regu pemenang atau isi nama penerima sertifikat juara.');
            }

            $certData = $this->getOrCreateSertifikatJuara([
                'season_id' => $regu?->season_id,
                'mata_lomba_id' => $lomba?->id,
                'regu_profile_id' => $regu?->id,
                'anggota_regu_id' => null,
                'nama_penerima' => $namaPenerima,
                'nama_lomba' => $namaLomba,
                'kategori' => $kategori,
                'peringkat' => $peringkat,
                'mode' => $regu ? 'regu' : 'manual',
            ], $regu, $formatNomor, $currentCounter);

            $certificates[] = [
                'nama' => $namaPenerima,
                'keterangan' => $showKet ? $peringkatText : null,
                'lomba' => $showLomba ? $namaLomba : null,
                'nomor' => $certData['nomor'],
                'qrCode' => $certData['qrCode'],
                'verifyUrl' => $certData['verifyUrl'],
            ];
        }

        $pdf = Pdf::loadView('pdf.sertifikat', [
            'certificates' => $certificates,
            'pesertaTemplatePath' => $this->resolveTemplatePath($settings),
            'signaturePath' => $this->resolveSignaturePath($settings),
            'signatures' => $this->resolveSignatures($settings),
            'settings' => $settings,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat-Juara-' . Str::slug($namaLomba . '-' . $peringkat) . '.pdf');
    }

    /**
     * Cetak Seluruh Sertifikat Juara (1-3 Putra & Putri) untuk satu Mata Lomba
     */
    public function downloadJuaraLombaAll(MataLomba $mataLomba, Request $request)
    {
        $activeSeason = Season::where('is_active', true)->first();
        $regus = ReguProfile::with(['anggotaRegu' => fn($q) => $q->orderBy('urutan')])
            ->when($activeSeason, fn($q) => $q->where('season_id', $activeSeason->id))
            ->get();

        $winners = $this->getLombaWinnersData($mataLomba, $regus);
        $mode = $request->input('mode', 'regu') === 'anggota' ? 'anggota' : 'regu';

        $settings = $this->getCertificateSettings('juara');
        $formatNomor = $settings['cert_number_format_peserta'];
        $currentCounter = (int)($settings['cert_counter_peserta'] ?? 1);
        $showKet = ($settings['cert_show_ket'] ?? '1') == '1';
        $showLomba = ($settings['cert_show_lomba'] ?? '1') == '1';

        $certificates = [];
        foreach (['putra' => 'Putra', 'putri' => 'Putri'] as $gKey => $gLabel) {
            foreach ($winners[$gKey] as $entry) {
                $regu = $entry['regu'];
                $peringkat = $entry['peringkat'];
                $peringkatText = "{$peringkat} ({$gLabel})";

                if ($mode === 'anggota' && $regu->anggotaRegu->isNotEmpty()) {
                    foreach ($regu->anggotaRegu as $anggota) {
                        $certData = $this->getOrCreateSertifikatJuara([
                            'season_id' => $regu->season_id,
                            'mata_lomba_id' => $mataLomba->id,
                            'regu_profile_id' => $regu->id,
                            'anggota_regu_id' => $anggota->id,
                            'nama_penerima' => $anggota->nama,
                            'nama_lomba' => $mataLomba->nama,
                            'kategori' => $gLabel,
                            'peringkat' => $peringkat,
                            'mode' => 'anggota',
                        ], $regu, $formatNomor, $currentCounter);

                        $certificates[] = [
                            'nama' => $anggota->nama,
                            'keterangan' => $showKet ? $peringkatText : null,
                            'lomba' => $showLomba ? "{$mataLomba->nama} • Regu {$regu->nama_regu}" : null,
                            'nomor' => $certData['nomor'],
                            'qrCode' => $certData['qrCode'],
                            'verifyUrl' => $certData['verifyUrl'],
                        ];
                    }
                } else {
                    $namaPenerima = 'Regu ' . $regu->nama_regu;
                    $certData = $this->getOrCreateSertifikatJuara([
                        'season_id' => $regu->season_id,
                        'mata_lomba_id' => $mataLomba->id,
                        'regu_profile_id' => $regu->id,
                        'anggota_regu_id' => null,
                        'nama_penerima' => $namaPenerima,
                        'nama_lomba' => $mataLomba->nama,
                        'kategori' => $gLabel,
                        'peringkat' => $peringkat,
                        'mode' => 'regu',
                    ], $regu, $formatNomor, $currentCounter);

                    $certificates[] = [
                        'nama' => $namaPenerima,
                        'keterangan' => $showKet ? $peringkatText : null,
                        'lomba' => $showLomba ? $mataLomba->nama : null,
                        'nomor' => $certData['nomor'],
                        'qrCode' => $certData['qrCode'],
                        'verifyUrl' => $certData['verifyUrl'],
                    ];
                }
            }
        }

        if (empty($certificates)) {
            return redirect()->route('admin.sertifikat.peserta', ['tab' => 'juara', 'lomba_id' => $mataLomba->id])
                ->with('error', 'Belum ada nilai juri yang tercatat untuk menentukan juara pada lomba ini.');
        }

        $pdf = Pdf::loadView('pdf.sertifikat', [
            'certificates' => $certificates,
            'pesertaTemplatePath' => $this->resolveTemplatePath($settings),
            'signaturePath' => $this->resolveSignaturePath($settings),
            'signatures' => $this->resolveSignatures($settings),
            'settings' => $settings,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat-Juara-' . Str::slug($mataLomba->nama) . '.pdf');
    }

    /**
     * Hitung peringkat Juara 1-3 Putra & Putri per Mata Lomba dari tabel Score (atau CerdasCermatSession)
     */
    protected function getLombaWinnersData(MataLomba $lomba, $regus): array
    {
        $scores = Score::where('mata_lomba_id', $lomba->id)
            ->where('status', '!=', Score::STATUS_DRAFT)
            ->get();

        $totalsByRegu = [];
        foreach ($scores as $s) {
            $totalsByRegu[$s->regu_profile_id] = ($totalsByRegu[$s->regu_profile_id] ?? 0) + (float) $s->nilai;
        }

        // Fallback untuk Cerdas Cermat jika nilainya tersimpan di cerdas_cermat_sessions
        if (empty($totalsByRegu) && str_contains(strtolower($lomba->slug ?? $lomba->nama), 'cerdas-cermat')) {
            $sessions = CerdasCermatSession::whereNotNull('regu_id')->get();
            foreach ($sessions as $cs) {
                $total = (float) ($cs->score_round_1 ?? 0) + (float) ($cs->score_round_2 ?? 0) + (float) ($cs->score_round_3 ?? 0);
                if ($total > 0) {
                    $totalsByRegu[$cs->regu_id] = $total;
                }
            }
        }

        $putra = [];
        $putri = [];
        foreach ($regus as $regu) {
            $val = $totalsByRegu[$regu->id] ?? 0;
            if ($val <= 0) continue;
            $item = ['regu' => $regu, 'total_nilai' => round($val, 2)];
            if (strtolower($regu->jenis) === 'putri') {
                $putri[] = $item;
            } else {
                $putra[] = $item;
            }
        }

        $sortAndTag = function (array $list) {
            usort($list, fn($a, $b) => $b['total_nilai'] <=> $a['total_nilai']);
            $top = array_slice($list, 0, 3);
            foreach ($top as $idx => &$row) {
                $row['rank'] = $idx + 1;
                $row['peringkat'] = 'Juara ' . ($idx + 1);
            }
            return $top;
        };

        return [
            'putra' => $sortAndTag($putra),
            'putri' => $sortAndTag($putri),
        ];
    }

    /**
     * Download Berkas Contoh Template Peserta (PDF atau PNG)
     */
    public function downloadSample(Request $request)
    {
        $format = in_array(strtolower($request->input('format')), ['png', 'pdf']) ? strtolower($request->input('format')) : 'pdf';
        $filename = "contoh-template-peserta.{$format}";
        $path = public_path("images/certificates/{$filename}");

        if (!file_exists($path)) {
            // Fallback ke contoh sebelumnya jika belum disalin
            $fallback = public_path("images/certificates/contoh-template-peserta-rumus.{$format}");
            if (file_exists($fallback)) {
                $path = $fallback;
            } else {
                abort(404, 'Berkas contoh template peserta tidak ditemukan.');
            }
        }

        $downloadName = "Contoh-Template-Sertifikat-Peserta-Spencerone.{$format}";
        return response()->download($path, $downloadName);
    }

    /**
     * Mengambil atau membuat data sertifikat unik & QR verification token per anggota
     */
    protected function getOrCreateSertifikatPeserta(AnggotaRegu $anggota, string $formatNomor, int &$currentCounter): array
    {
        $nomor = null;
        $token = null;

        if (Schema::hasTable('sertifikat_peserta')) {
            $cert = SertifikatPeserta::where('anggota_regu_id', $anggota->id)->first();
            if ($cert) {
                $nomor = $cert->nomor_sertifikat;
                $token = $cert->verify_token;
            } else {
                $thisCounter = $currentCounter++;
                $nomor = $this->generateNomorSertifikat($formatNomor, $thisCounter, $anggota->reguProfile);
                $token = 'SC2-' . $anggota->id . '-' . Str::lower(Str::random(12));
                SertifikatPeserta::create([
                    'anggota_regu_id' => $anggota->id,
                    'nomor_sertifikat' => $nomor,
                    'nomor_urut' => $thisCounter,
                    'verify_token' => $token,
                    'diterbitkan_pada' => now()->toDateString(),
                ]);
                Setting::updateOrCreate(['key' => 'cert_counter_peserta'], ['value' => $currentCounter]);
            }
        } else {
            $thisCounter = $currentCounter++;
            $nomor = $this->generateNomorSertifikat($formatNomor, $thisCounter, $anggota->reguProfile);
            $token = 'SC2-' . $anggota->id . '-' . Str::lower(Str::random(12));
        }

        $verifyUrl = url('/verifikasi-sertifikat/' . $token);
        $qrCode = QrCodeService::generateDataUri($verifyUrl);

        return [
            'nomor' => $nomor,
            'verifyUrl' => $verifyUrl,
            'qrCode' => $qrCode,
            'token' => $token,
        ];
    }

    /**
     * Menemukan path template background yang aktif
     */
    protected function resolveTemplatePath(array $settings): ?string
    {
        if (!empty($settings['cert_template_peserta'])) {
            $customPath = storage_path('app/public/' . $settings['cert_template_peserta']);
            if (file_exists($customPath)) return $customPath;
        }

        if (file_exists(public_path('images/certificates/template-peserta-official.png'))) {
            return public_path('images/certificates/template-peserta-official.png');
        }

        if (file_exists(public_path('images/certificates/template-peserta-blank.png'))) {
            return public_path('images/certificates/template-peserta-blank.png');
        }

        return null;
    }

    /**
     * Menemukan path tanda tangan digital yang aktif (slot 1)
     */
    protected function resolveSignaturePath(array $settings): ?string
    {
        if (!empty($settings['cert_signature_peserta'])) {
            $customPath = storage_path('app/public/' . $settings['cert_signature_peserta']);
            if (file_exists($customPath)) return $customPath;
        }

        return null;
    }

    /**
     * Menemukan seluruh slot tanda tangan digital aktif (1..4) beserta posisinya
     */
    protected function resolveSignatures(array $settings): array
    {
        $ttdCount = max(0, min(4, (int) ($settings['cert_ttd_count'] ?? (($settings['cert_show_ttd'] ?? '1') == '1' ? 1 : 0))));
        $result = [];

        for ($i = 1; $i <= $ttdCount; $i++) {
            $settingKey = $i === 1 ? 'cert_signature_peserta' : "cert_signature_peserta_{$i}";
            if (empty($settings[$settingKey])) {
                continue;
            }
            $customPath = storage_path('app/public/' . $settings[$settingKey]);
            if (!file_exists($customPath)) {
                continue;
            }

            $prefix = $i === 1 ? 'cert_pos_ttd' : "cert_pos_ttd{$i}";
            $defaultLefts = [1 => 68.0, 2 => 14.0, 3 => 41.0, 4 => 41.0];
            $defaultTops = [1 => 72.0, 2 => 72.0, 3 => 72.0, 4 => 56.0];

            $result[] = [
                'index' => $i,
                'path' => $customPath,
                'top' => (float) ($settings["{$prefix}_top"] ?? $defaultTops[$i]),
                'left' => (float) ($settings["{$prefix}_left"] ?? $defaultLefts[$i]),
                'width' => (float) ($settings["{$prefix}_width"] ?? 18.0),
            ];
        }

        return $result;
    }

    /**
     * Ambil atau terbitkan nomor & token QR sertifikat untuk anggota Panitia
     */
    protected function getOrCreateSertifikatPanitia(PanitiaMember $panitia, string $formatNomor, int &$currentCounter): array
    {
        if (!empty($panitia->nomor_sertifikat) && !empty($panitia->verify_token)) {
            $nomor = $panitia->nomor_sertifikat;
            $token = $panitia->verify_token;
        } else {
            $thisCounter = $currentCounter++;
            $nomor = $this->generateNomorSertifikat($formatNomor, $thisCounter, null);
            $token = 'PAN-' . $panitia->id . '-' . Str::lower(Str::random(12));
            $panitia->update([
                'nomor_sertifikat' => $nomor,
                'nomor_urut' => $thisCounter,
                'verify_token' => $token,
                'diterbitkan_pada' => now()->toDateString(),
            ]);
            Setting::updateOrCreate(['key' => $this->scopeKey('panitia', 'counter')], ['value' => $currentCounter]);
        }

        $verifyUrl = url('/verifikasi-sertifikat/' . $token);
        return [
            'nomor' => $nomor,
            'verifyUrl' => $verifyUrl,
            'qrCode' => QrCodeService::generateDataUri($verifyUrl),
            'token' => $token,
        ];
    }

    /**
     * Ambil atau terbitkan nomor & token QR sertifikat Juara Lomba
     */
    protected function getOrCreateSertifikatJuara(array $attrs, ?ReguProfile $regu, string $formatNomor, int &$currentCounter): array
    {
        $existing = SertifikatJuara::where('mata_lomba_id', $attrs['mata_lomba_id'])
            ->where('regu_profile_id', $attrs['regu_profile_id'])
            ->where('anggota_regu_id', $attrs['anggota_regu_id'])
            ->where('nama_penerima', $attrs['nama_penerima'])
            ->where('peringkat', $attrs['peringkat'])
            ->where('kategori', $attrs['kategori'])
            ->first();

        if ($existing) {
            $nomor = $existing->nomor_sertifikat;
            $token = $existing->verify_token;
        } else {
            $thisCounter = $currentCounter++;
            $nomor = $this->generateNomorSertifikat($formatNomor, $thisCounter, $regu);
            $token = 'JUA-' . ($attrs['mata_lomba_id'] ?? 0) . '-' . Str::lower(Str::random(12));
            SertifikatJuara::create(array_merge($attrs, [
                'nomor_sertifikat' => $nomor,
                'nomor_urut' => $thisCounter,
                'verify_token' => $token,
                'diterbitkan_pada' => now()->toDateString(),
            ]));
            Setting::updateOrCreate(['key' => $this->scopeKey('juara', 'counter')], ['value' => $currentCounter]);
        }

        $verifyUrl = url('/verifikasi-sertifikat/' . $token);
        return [
            'nomor' => $nomor,
            'verifyUrl' => $verifyUrl,
            'qrCode' => QrCodeService::generateDataUri($verifyUrl),
            'token' => $token,
        ];
    }

    /**
     * Petakan kunci logika pengaturan ke key database berdasarkan jenis sertifikat (peserta | panitia | juara)
     */
    private function scopeKey(string $scope, string $logicalKey): string
    {
        if ($scope === 'peserta') {
            return match ($logicalKey) {
                'number_format' => 'cert_number_format_peserta',
                'counter' => 'cert_counter_peserta',
                'template' => 'cert_template_peserta',
                'signature_1' => 'cert_signature_peserta',
                'signature_2' => 'cert_signature_peserta_2',
                'signature_3' => 'cert_signature_peserta_3',
                'signature_4' => 'cert_signature_peserta_4',
                'ttd_label_1' => 'cert_ttd_label_1',
                'ttd_label_2' => 'cert_ttd_label_2',
                'ttd_label_3' => 'cert_ttd_label_3',
                'ttd_label_4' => 'cert_ttd_label_4',
                'show_ttd' => 'cert_show_ttd',
                'ttd_count' => 'cert_ttd_count',
                'show_qr' => 'cert_show_qr',
                'show_ket' => 'cert_show_ket',
                'show_lomba' => 'cert_show_lomba',
                default => 'cert_' . $logicalKey,
            };
        }

        return "cert_{$scope}_{$logicalKey}";
    }

    private function getCertificateSettings(string $scope = 'peserta'): array
    {
        $defaultsByScope = [
            'peserta' => [
                'number_format' => '{NOMOR}/SC-II/SERTIFIKAT/{BULAN}/{TAHUN}',
                'show_ket' => '0',
                'show_lomba' => '0',
                'ttd_label_1' => 'TTD 1 (Ka. Mabigus)',
                'ttd_label_2' => 'TTD 2 (Pembina)',
                'ttd_label_3' => 'TTD 3 (Ketua Panitia)',
                'ttd_label_4' => 'TTD 4',
                'pos_ket_top' => 52.8,
                'pos_ket_size' => 13,
            ],
            'panitia' => [
                'number_format' => '{NOMOR}/SC-II/PANITIA/{BULAN}/{TAHUN}',
                'show_ket' => '1',
                'show_lomba' => '0',
                'ttd_label_1' => 'TTD 1 (Ka. Mabigus)',
                'ttd_label_2' => 'TTD 2 (Pembina)',
                'ttd_label_3' => 'TTD 3 (Ketua Panitia)',
                'ttd_label_4' => 'TTD 4',
                'pos_ket_top' => 52.8,
                'pos_ket_size' => 14,
            ],
            'juara' => [
                'number_format' => '{NOMOR}/SC-II/JUARA/{BULAN}/{TAHUN}',
                'show_ket' => '1',
                'show_lomba' => '1',
                'ttd_label_1' => 'TTD 1 (Ka. Mabigus)',
                'ttd_label_2' => 'TTD 2 (Pembina)',
                'ttd_label_3' => 'TTD 3 (Ketua Panitia)',
                'ttd_label_4' => 'TTD 4',
                'pos_ket_top' => 52.5,
                'pos_ket_size' => 15,
            ],
        ];

        $scDef = $defaultsByScope[$scope] ?? $defaultsByScope['peserta'];

        $logicalDefaults = [
            'number_format' => $scDef['number_format'],
            'counter' => 1,
            'template' => null,
            'signature_1' => null,
            'signature_2' => null,
            'signature_3' => null,
            'signature_4' => null,
            'ttd_label_1' => $scDef['ttd_label_1'],
            'ttd_label_2' => $scDef['ttd_label_2'],
            'ttd_label_3' => $scDef['ttd_label_3'],
            'ttd_label_4' => $scDef['ttd_label_4'],
            'show_ttd' => '1',
            'ttd_count' => null,
            'show_qr' => '1',
            'show_ket' => $scDef['show_ket'],
            'show_lomba' => $scDef['show_lomba'],
            'pos_nomor_top' => 35.2,
            'pos_nomor_left' => 10.0,
            'pos_nomor_width' => 80.0,
            'pos_nomor_align' => 'center',
            'pos_nomor_size' => 11,
            'pos_nama_top' => 43.8,
            'pos_nama_left' => 10.0,
            'pos_nama_width' => 80.0,
            'pos_nama_align' => 'center',
            'pos_nama_size' => 24,
            'pos_ket_top' => $scDef['pos_ket_top'],
            'pos_ket_left' => 10.0,
            'pos_ket_width' => 80.0,
            'pos_ket_align' => 'center',
            'pos_ket_size' => $scDef['pos_ket_size'],
            'pos_lomba_top' => 59.5,
            'pos_lomba_left' => 10.0,
            'pos_lomba_width' => 80.0,
            'pos_lomba_align' => 'center',
            'pos_lomba_size' => 13,
            'pos_ttd_top' => 72.0,
            'pos_ttd_left' => 68.0,
            'pos_ttd_width' => 18.0,
            'pos_ttd2_top' => 72.0,
            'pos_ttd2_left' => 14.0,
            'pos_ttd2_width' => 18.0,
            'pos_ttd3_top' => 72.0,
            'pos_ttd3_left' => 41.0,
            'pos_ttd3_width' => 18.0,
            'pos_ttd4_top' => 56.0,
            'pos_ttd4_left' => 41.0,
            'pos_ttd4_width' => 18.0,
            'pos_qr_top' => 82.0,
            'pos_qr_left' => 88.0,
            'pos_qr_width' => 8.0,
        ];

        $stored = Setting::pluck('value', 'key')->all();
        $resolved = [];
        foreach ($logicalDefaults as $logical => $defaultVal) {
            $dbKey = $this->scopeKey($scope, $logical);
            $val = array_key_exists($dbKey, $stored) ? $stored[$dbKey] : $defaultVal;
            // Expose canonical cert_* keys expected by views & resolveSignatures()
            $canonicalKey = $this->scopeKey('peserta', $logical);
            $resolved[$canonicalKey] = $val;
        }

        if ($resolved['cert_ttd_count'] === null) {
            $resolved['cert_ttd_count'] = ($resolved['cert_show_ttd'] ?? '1') == '1' ? '1' : '0';
        }

        return $resolved;
    }

    private function generateNomorSertifikat(string $format, int $counter, ?ReguProfile $regu): string
    {
        $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $currentMonthRoman = $romans[(int)date('n')] ?? date('m');

        $formattedNumber = str_pad((string)$counter, 3, '0', STR_PAD_LEFT);

        return strtr($format, [
            '{NOMOR}' => $formattedNumber,
            '{TAHUN}' => date('Y'),
            '{BULAN}' => $currentMonthRoman,
            '{REGU}' => $regu ? strtoupper(Str::slug($regu->nama_regu, '')) : 'REGU',
            '{JENIS}' => $regu ? strtoupper($regu->jenis) : 'PUTRA',
        ]);
    }
}
