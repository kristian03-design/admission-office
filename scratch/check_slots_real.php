<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Program;

echo "--- PROGRAM SLOTS ---\n";
$programs = Program::all();
foreach ($programs as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Slots Left: {$p->slots_left}\n";
}
