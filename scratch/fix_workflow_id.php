<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Ijazah;
use App\Models\Workflow;

$activeWorkflow = Workflow::where('is_active', true)->first();
if (!$activeWorkflow) {
    echo "No active workflow found!\n";
    exit;
}

$ijazahs = Ijazah::whereNull('workflow_id')->get();
$count = 0;

foreach ($ijazahs as $ijazah) {
    $ijazah->workflow_id = $activeWorkflow->id;
    $ijazah->save();
    $count++;
}

echo "Fixed $count ijazahs by setting workflow_id to {$activeWorkflow->id}.\n";
