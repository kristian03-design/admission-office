<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\NewsEvent;

$items = NewsEvent::all();
echo json_encode([
    'count' => $items->count(),
    'items' => $items->toArray()
], JSON_PRETTY_PRINT);
