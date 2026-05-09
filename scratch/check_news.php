<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\NewsEvent;

echo "=== News & Events Count ===\n";
echo "Total Count: " . NewsEvent::count() . "\n";
echo "Active Count: " . NewsEvent::where('is_active', true)->count() . "\n";

foreach (NewsEvent::all() as $item) {
    echo "ID: {$item->id} | Title: {$item->title} | Active: " . ($item->is_active ? 'Yes' : 'No') . "\n";
}
