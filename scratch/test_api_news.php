<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\Api\NewsEventController;
use Illuminate\Http\Request;

$controller = new NewsEventController();
$response = $controller->index();
$data = json_decode($response->getContent(), true);
dd([
    'data_count' => count($data['data'] ?? []),
    'raw_content' => $response->getContent()
]);
