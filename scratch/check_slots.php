<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\Program;

$programs = Program::select('id', 'name', 'slots_left', 'is_active')->get();
echo "Current Program Slots in Database:\n";
foreach ($programs as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Slots: {$p->slots_left} | Active: " . ($p->is_active ? 'YES' : 'NO') . "\n";
}
