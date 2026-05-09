<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\NewsEventController;
use Illuminate\Http\Request;

$controller = new NewsEventController();
$response = $controller->index();
echo $response->getContent();
