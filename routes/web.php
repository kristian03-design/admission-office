<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\WelcomeController;

//Landing Page//
Route::get('/welcome-page', [WelcomeController::class, 'index'])->name('home');
Route::get('/', [WelcomeController::class, 'index']); // Also handle the root URL
Route::get('/programs/{id}', [WelcomeController::class, 'showProgram'])->name('programs.show');
Route::get('/about', [WelcomeController::class, 'about'])->name('about');
Route::get('/news-events', [WelcomeController::class, 'newsEvents'])->name('news-events');
Route::get('/news-events/{id}', [WelcomeController::class, 'showNewsEvent'])->name('news-events.show');


//Application Form//
Route::get('/apply', function () {
    return view('apply');
})->name('apply');


// Admin Dashboard//
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin authentication routes for the custom admin login page.
Route::middleware('guest')->group(function () {
    Route::redirect('/admin-login.html', '/admin/login', 301);

    Route::get('/admin/login', function () {
        return view('auth.login');
    })->name('admin.login');

    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
        ->name('admin.login.submit');
});

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

require __DIR__.'/auth.php';
