<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Program;
use App\Models\Application;

echo "--- PROGRAMS ---\n";
$programs = Program::all();
foreach ($programs as $p) {
    echo "ID: {$p->id} | Name: {$p->name}\n";
}

echo "\n--- APPLICATIONS ---\n";
$apps = Application::all();
foreach ($apps as $a) {
    echo "ID: {$a->id} | Program ID: " . ($a->program_id ?? 'NULL') . " | Program Name: " . ($a->program_name ?? 'N/A') . " | Ref: {$a->application_no}\n";
}

echo "\n--- WITH COUNT ---\n";
$programsWithCount = Program::withCount('applications')->get();
foreach ($programsWithCount as $p) {
    echo "Program: {$p->name} | App Count: {$p->applications_count}\n";
}
