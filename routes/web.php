<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\WelcomeController;

//Landing Page//
Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::redirect('/welcome-page', '/', 301);
Route::get('/programs/{id}', [WelcomeController::class, 'showProgram'])->name('programs.show');
Route::get('/about', [WelcomeController::class, 'about'])->name('about');
Route::get('/news-events', [WelcomeController::class, 'newsEvents'])->name('news-events');
Route::get('/news-events/{id}', [WelcomeController::class, 'showNewsEvent'])->name('news-events.show');


//Application Form//
Route::get('/apply', function () {
    return view('apply');
})->name('apply');
Route::redirect('/inquire', '/apply', 301);
Route::redirect('/inquiry', '/apply', 301);
Route::redirect('/application', '/apply', 301);
Route::redirect('/admissions/apply', '/apply', 301);


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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
