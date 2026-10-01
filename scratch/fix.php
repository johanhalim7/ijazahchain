<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Ijazah;

$ijazahs = Ijazah::whereNull('current_approver_role')->where('status', '!=', 'Aktif')->get();
$count = 0;

foreach ($ijazahs as $ijazah) {
    $steps = $ijazah->approval_data['workflow_steps'] ?? [];
    $ijazah->current_approver_role = $steps[0] ?? 'akademik';
    $ijazah->save();
    $count++;
}

echo "Fixed $count ijazahs.\n";
