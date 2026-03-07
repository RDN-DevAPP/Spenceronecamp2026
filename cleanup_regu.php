<?php

use App\Models\ReguProfile;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$regu = ReguProfile::where('nomor_regu', '-')->get();
foreach ($regu as $r) {
    $next = ReguProfile::where('jenis', $r->jenis)
        ->where('nomor_regu', '!=', '-')
        ->max('nomor_regu') ?? 0;

    $target = $next + 1;
    $r->update(['nomor_regu' => $target]);
    echo "Updated {$r->nama_regu} ({$r->jenis}) to number {$target}\n";
}
