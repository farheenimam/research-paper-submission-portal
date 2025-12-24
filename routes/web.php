<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PaperController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');
Route::get('/register', [AuthController::class, 'registration'])->name('registration');
Route::post('/register', [AuthController::class, 'registrationPost'])->name('signupPost');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Search routes
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/api/search-suggestions', [SearchController::class, 'searchApi'])->name('search.api');

// Public paper view
Route::get('/paper/{id}', [PaperController::class, 'view'])->name('paper.view');

// Dashboard routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/upload-paper', [DashboardController::class, 'uploadPaper'])->name('dashboard.upload-paper');
    Route::post('/dashboard/upload-paper', [DashboardController::class, 'storePaper'])->name('dashboard.store-paper');
    Route::get('/dashboard/paper/{id}', [DashboardController::class, 'viewPaper'])->name('dashboard.view-paper');
});
