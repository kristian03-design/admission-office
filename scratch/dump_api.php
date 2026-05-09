<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\ProgramController;
use Illuminate\Http\Request;

$controller = new ProgramController();
$response = $controller->index(new Request());
echo $response->getContent();
