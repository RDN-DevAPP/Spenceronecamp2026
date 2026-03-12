<?php

use App\Models\User;
use App\Models\ReguProfile;
use App\Models\AnggotaRegu;
use Illuminate\Http\Request;
use App\Http\Controllers\ReguRegistrationController;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Helper to simulate registration
function simulateRegistration($name, $username)
{
    try {
        $controller = new ReguRegistrationController();
        $request = new Request([
            'nama_regu' => $name,
            'jenis' => 'putra',
            'username' => $username,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'jumlah_anggota' => 8,
            'anggota' => array_fill(0, 8, ['nama' => 'Test Member', 'tingkatan_tku' => 'ramu']),
        ]);

        // Mock some validation just in case, but store() has its own validation
        // We actually want to run the real store() method
        echo "Registering $name...\n";
        $response = $controller->store($request);

        if ($response->isRedirect() && !str_contains($response->headers->get('Location'), 'error')) {
            echo "Success: $name registered.\n";
        } else {
            // If manual redirect with error flash
            $error = session('error');
            echo "Failed: $error\n";
        }
    } catch (\Exception $e) {
        echo "Error registering $name: " . $e->getMessage() . "\n";
    }
}

// 1. Current state check
echo "Current Putra teams:\n";
$putra = ReguProfile::where('jenis', 'putra')->get();
foreach ($putra as $p) {
    echo "- {$p->nama_regu}: #{$p->nomor_regu}\n";
}

// 2. Simulate new registration
simulateRegistration('Elang Perkasa', 'elang_perkasa_' . time());

// 3. Final state check
echo "\nFinal Putra teams:\n";
$putra = ReguProfile::where('jenis', 'putra')->get();
foreach ($putra as $p) {
    echo "- {$p->nama_regu}: #{$p->nomor_regu}\n";
}
