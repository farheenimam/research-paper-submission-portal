<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewerController;

Route::get('/', function () {
    if (auth()->check()) {
        // Redirect reviewers (role_id 3) to articles page
        if (auth()->user()->role_id == 3) {
            return redirect()->route('reviewer.articles');
        }
        // Redirect other users to recent papers
        return redirect()->route('paper.recent');
    }
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
Route::get('/recent', [PaperController::class, 'recent'])->name('paper.recent');

// Dashboard routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/upload-paper', [DashboardController::class, 'uploadPaper'])->name('dashboard.upload-paper');
    Route::post('/dashboard/upload-paper', [DashboardController::class, 'storePaper'])->name('dashboard.store-paper');
    Route::get('/dashboard/paper/{id}', [DashboardController::class, 'viewPaper'])->name('dashboard.view-paper');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
    
    // Reviewer routes
    Route::get('/reviewer/articles', [ReviewerController::class, 'articles'])->name('reviewer.articles');
    Route::get('/reviewer/review/{id}', [ReviewerController::class, 'review'])->name('reviewer.review');
    Route::put('/reviewer/review/{id}', [ReviewerController::class, 'updateReview'])->name('reviewer.update-review');
});
