<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "URL(/api): " . url('/api') . "\n";
echo "ASSET(js/api-config.js): " . asset('js/api-config.js') . "\n";
