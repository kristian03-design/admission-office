<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InterviewController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\NewsEventController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/programs', [ProgramController::class, 'index']);
Route::get('/settings', [SettingsController::class, 'publicShow']);
Route::post('/applications/submit-public', [ApplicationController::class, 'submitPublic']);
Route::post('/applications/{id}/documents', [ApplicationController::class, 'uploadDocument']);
Route::get('/news-events', [NewsEventController::class, 'publicIndex']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/password', [AuthController::class, 'updatePassword']);
    
    // Application management
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{id}', [ApplicationController::class, 'show']);
    Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);

    // Program management
    Route::patch('/programs/{id}/schedule', [ProgramController::class, 'updateSchedule']);
    Route::patch('/programs/{id}/slots-left', [ProgramController::class, 'updateSlotsLeft']);
    Route::post('/programs/{id}/slots-left', [ProgramController::class, 'updateSlotsLeft']);

    // Admin dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

    // System Settings
    Route::get('/admin/settings', [SettingsController::class, 'show']);
    Route::post('/admin/settings', [SettingsController::class, 'update']);
    Route::put('/admin/settings', [SettingsController::class, 'update']);

    // Interview Scheduling
    Route::get('/interviews', [InterviewController::class, 'index']);
    Route::post('/interviews/sync/{programId}', [InterviewController::class, 'sync']);

    // Announcements
    Route::get('/announcements', [\App\Http\Controllers\Api\AnnouncementController::class, 'index']);
    Route::post('/announcements', [\App\Http\Controllers\Api\AnnouncementController::class, 'store']);
    Route::patch('/announcements/{id}', [\App\Http\Controllers\Api\AnnouncementController::class, 'update']);
    Route::delete('/announcements/{id}', [\App\Http\Controllers\Api\AnnouncementController::class, 'destroy']);

    // News & Events
    Route::get('/admin/news-events', [NewsEventController::class, 'index']);
    Route::post('/admin/news-events', [NewsEventController::class, 'store']);
    Route::patch('/admin/news-events/{id}', [NewsEventController::class, 'update']);
    Route::delete('/admin/news-events/{id}', [NewsEventController::class, 'destroy']);

    // Testimonials
    Route::get('/testimonials', [\App\Http\Controllers\Api\TestimonialController::class, 'index']);
    Route::post('/testimonials', [\App\Http\Controllers\Api\TestimonialController::class, 'store']);
    Route::patch('/testimonials/{id}', [\App\Http\Controllers\Api\TestimonialController::class, 'update']);
    Route::delete('/testimonials/{id}', [\App\Http\Controllers\Api\TestimonialController::class, 'destroy']);

    // Faculty & Staff
    Route::get('/faculty-staff', [\App\Http\Controllers\Api\FacultyStaffController::class, 'index']);
    Route::post('/faculty-staff', [\App\Http\Controllers\Api\FacultyStaffController::class, 'store']);
    Route::patch('/faculty-staff/{id}', [\App\Http\Controllers\Api\FacultyStaffController::class, 'update']);
    Route::delete('/faculty-staff/{id}', [\App\Http\Controllers\Api\FacultyStaffController::class, 'destroy']);

    // Inquiries (Admin view)
    Route::get('/admin/inquiries', [\App\Http\Controllers\Api\InquiryController::class, 'index']);
    Route::delete('/admin/inquiries/{id}', [\App\Http\Controllers\Api\InquiryController::class, 'destroy']);

    // Public Cache Control
    Route::post('/admin/clear-cache', function() {
        \Illuminate\Support\Facades\Cache::forget('welcome_page_data');
        return response()->json(['message' => 'Cache cleared.']);
    });
});

// Public Inquiry submission
Route::post('/contact', [\App\Http\Controllers\Api\InquiryController::class, 'store']);
