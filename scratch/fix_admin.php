<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Ijazah;

$ijazahs = Ijazah::where('status', 'Pending Upload')->get();
$count = 0;

foreach ($ijazahs as $ijazah) {
    if ($ijazah->current_approver_role !== null) {
        $ijazah->current_approver_role = null;
        $ijazah->save();
        $count++;
    }
}

echo "Fixed $count admin ijazahs.\n";
