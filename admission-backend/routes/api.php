<?php
// ============================================================
// routes/api.php  –  RESTful route dispatcher
// ============================================================

use App\Controllers\AuthController;
use App\Controllers\ApplicationController;
use App\Controllers\DocumentController;
use App\Controllers\ApplicantController;
use App\Controllers\AdminController;
use App\Middleware\CorsMiddleware;
use App\Helpers\Response;

// Apply CORS first
(new CorsMiddleware())->handle();

// Parse request
$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip base prefix (adjust if API lives at a subdirectory)
$uri = preg_replace('#^/api#', '', $uri);
$uri = rtrim($uri, '/') ?: '/';

// ── Route definitions ─────────────────────────────────────────
// Format: [METHOD, regex_pattern, ControllerClass, action, [param_names...]]

$routes = [

    // Auth
    ['POST', '#^/auth/register$#',         AuthController::class,       'register'],
    ['POST', '#^/auth/login$#',            AuthController::class,       'login'],
    ['POST', '#^/auth/logout$#',           AuthController::class,       'logout'],
    ['POST', '#^/auth/refresh$#',          AuthController::class,       'refresh'],
    ['GET',  '#^/auth/me$#',               AuthController::class,       'me'],
    ['POST', '#^/auth/forgot-password$#',  AuthController::class,       'forgotPassword'],

    // Applicant profile
    ['GET',  '#^/applicant/profile$#',     ApplicantController::class,  'profile'],
    ['PUT',  '#^/applicant/profile$#',     ApplicantController::class,  'updateProfile'],

    // Programs (public)
    ['GET',  '#^/programs$#',              ApplicantController::class,  'programs'],

    // Applications
    ['GET',  '#^/applications$#',          ApplicationController::class,'index'],
    ['POST', '#^/applications$#',          ApplicationController::class,'store'],
    ['GET',  '#^/applications/(\d+)$#',    ApplicationController::class,'show',         ['id']],
    ['PATCH','#^/applications/(\d+)/status$#', ApplicationController::class,'updateStatus', ['id']],
    ['DELETE','#^/applications/(\d+)$#',   ApplicationController::class,'destroy',      ['id']],

    // Documents
    ['GET',  '#^/applications/(\d+)/documents$#',  DocumentController::class,'index',   ['applicationId']],
    ['POST', '#^/applications/(\d+)/documents$#',  DocumentController::class,'upload',  ['applicationId']],
    ['GET',  '#^/documents/(\d+)/download$#',      DocumentController::class,'download',['docId']],
    ['PATCH','#^/documents/(\d+)/verify$#',        DocumentController::class,'verify',  ['docId']],
    ['DELETE','#^/documents/(\d+)$#',              DocumentController::class,'destroy', ['docId']],

    // Admin
    ['GET',  '#^/admin/dashboard$#',        AdminController::class,      'dashboard'],
    ['GET',  '#^/admin/users$#',            AdminController::class,      'users'],
    ['PUT',  '#^/admin/users/(\d+)$#',      AdminController::class,      'updateUser',  ['userId']],
    ['DELETE','#^/admin/users/(\d+)$#',     AdminController::class,      'deleteUser',  ['userId']],
    ['GET',  '#^/admin/programs$#',         AdminController::class,      'programs'],
    ['POST', '#^/admin/programs$#',         AdminController::class,      'createProgram'],
    ['PUT',  '#^/admin/programs/(\d+)$#',   AdminController::class,      'updateProgram',['id']],
    ['GET',  '#^/admin/reports/summary$#',  AdminController::class,      'reportsSummary'],
];

// ── Dispatch ──────────────────────────────────────────────────

$matched = false;

foreach ($routes as $route) {
    [$routeMethod, $pattern, $controllerClass, $action] = $route;
    $paramNames = $route[4] ?? [];

    if ($routeMethod !== $method) continue;
    if (!preg_match($pattern, $uri, $matches)) continue;

    $matched = true;

    // Extract URL params (positional capture groups → named params)
    $args = [];
    foreach ($paramNames as $i => $name) {
        $args[] = (int) $matches[$i + 1];
    }

    $controller = new $controllerClass();
    $controller->$action(...$args);
    break;
}

if (!$matched) {
    // Check if any route matches the URI but with wrong method
    $uriMatched = false;
    foreach ($routes as $route) {
        if (preg_match($route[1], $uri)) {
            $uriMatched = true;
            break;
        }
    }

    if ($uriMatched) {
        Response::error('Method not allowed', 405);
    } else {
        Response::notFound('Route not found');
    }
}
