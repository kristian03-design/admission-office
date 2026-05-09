<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\FacultyStaffController;
use App\Http\Controllers\Api\InterviewController;
use App\Http\Controllers\Api\NewsEventController as ApiNewsEventController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\WelcomeController;

Route::get('/uploaded-storage/{path}', function (string $path) {
    abort_unless(Storage::disk('public')->exists($path), 404);

    return response(Storage::disk('public')->get($path), 200)
        ->header('Content-Type', Storage::disk('public')->mimeType($path) ?: 'application/octet-stream');
})->where('path', '.*');

//Landing Page//
Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::redirect('/welcome-page', '/', 301);
Route::get('/programs/{id}', [WelcomeController::class, 'showProgram'])->name('programs.show');
Route::get('/about', [WelcomeController::class, 'about'])->name('about');
Route::get('/news-events', [WelcomeController::class, 'newsEvents'])->name('news-events');
Route::get('/news-events/{id}', [WelcomeController::class, 'showNewsEvent'])->name('news-events.show');


//Application Form//
Route::get('/apply', function () {
    return view('apply', ['settings' => \App\Models\SystemSetting::all_as_array()]);
})->name('apply');
Route::redirect('/inquire', '/apply', 301);
Route::redirect('/inquiry', '/apply', 301);
Route::redirect('/application', '/apply', 301);
Route::redirect('/admissions/apply', '/apply', 301);

// Compatibility for stale public form assets that call the endpoint without
// the /api or /backend prefix.
Route::post('/applications/submit-public', [ApplicationController::class, 'submitPublic']);
Route::post('/applications/{id}/documents', [ApplicationController::class, 'uploadDocument']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{id}', [ApplicationController::class, 'show']);
    Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    Route::post('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
});


// Admin Dashboard//
Route::get('/dashboard', function (Request $request) {
    $user = $request->user();
    $user->tokens()->where('name', 'admin-dashboard')->delete();

    return view('dashboard', [
        'admissionApiToken' => $user->createToken('admin-dashboard')->plainTextToken,
    ]);
})->middleware(['auth'])->name('dashboard');

// Admin authentication routes for the custom admin login page.
Route::redirect('/admin-login{extension?}', '/admin/login', 301)
    ->where('extension', '(\.html)?');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', function () {
        return view('auth.login');
    })->name('admin.login');

    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
        ->name('admin.login.submit');
});

Route::get('/admin/dashboard', function (Request $request) {
    if (! $request->user()) {
        return redirect()->route('admin.login');
    }

    $user = $request->user();
    $user->tokens()->where('name', 'admin-dashboard')->delete();

    return view('dashboard', [
        'admissionApiToken' => $user->createToken('admin-dashboard')->plainTextToken,
    ]);
})->name('admin.dashboard');

// Compatibility for stale dashboard assets that call /admin/settings instead of
// /api/admin/settings. Keep Sanctum auth so this remains admin-only.
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/settings', [SettingsController::class, 'show']);
    Route::post('/admin/settings', [SettingsController::class, 'update']);
    Route::put('/admin/settings', [SettingsController::class, 'update']);

    Route::patch('/programs/{id}/schedule', [ProgramController::class, 'updateSchedule']);
    Route::post('/programs/{id}/schedule', [ProgramController::class, 'updateSchedule']);
    Route::patch('/programs/{id}/slots-left', [ProgramController::class, 'updateSlotsLeft']);
    Route::post('/programs/{id}/slots-left', [ProgramController::class, 'updateSlotsLeft']);
    Route::patch('/programs/{id}/status', [ProgramController::class, 'updateStatus']);
    Route::post('/programs/{id}/status', [ProgramController::class, 'updateStatus']);

    Route::get('/interviews', [InterviewController::class, 'index']);
    Route::post('/interviews/sync/{programId}', [InterviewController::class, 'sync']);

    Route::get('/announcements', [AnnouncementController::class, 'index']);
    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::post('/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::patch('/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy']);

    Route::get('/admin/news-events', [ApiNewsEventController::class, 'index']);
    Route::post('/admin/news-events', [ApiNewsEventController::class, 'store']);
    Route::post('/admin/news-events/{id}', [ApiNewsEventController::class, 'update']);
    Route::patch('/admin/news-events/{id}', [ApiNewsEventController::class, 'update']);
    Route::delete('/admin/news-events/{id}', [ApiNewsEventController::class, 'destroy']);

    Route::get('/testimonials', [TestimonialController::class, 'index']);
    Route::post('/testimonials', [TestimonialController::class, 'store']);
    Route::post('/testimonials/{id}', [TestimonialController::class, 'update']);
    Route::patch('/testimonials/{id}', [TestimonialController::class, 'update']);
    Route::delete('/testimonials/{id}', [TestimonialController::class, 'destroy']);

    Route::get('/faculty-staff', [FacultyStaffController::class, 'index']);
    Route::post('/faculty-staff', [FacultyStaffController::class, 'store']);
    Route::post('/faculty-staff/{id}', [FacultyStaffController::class, 'update']);
    Route::patch('/faculty-staff/{id}', [FacultyStaffController::class, 'update']);
    Route::delete('/faculty-staff/{id}', [FacultyStaffController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
