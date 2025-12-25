<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    if (auth()->check()) {
        // Hardcoded admin email redirect
        if (auth()->user()->email === 'farheenimam@gmail.com') {
            return redirect()->route('admin.dashboard');
        }
        // Redirect reviewers (role_id 3) to articles page
        if (auth()->user()->role_id == 3) {
            return redirect()->route('reviewer.articles');
        }
        // Redirect readers (role_id 4) to search page
        if (auth()->user()->role_id == 4) {
            return redirect()->route('search');
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
Route::post('/search/save/{id}', [SearchController::class, 'savePaper'])->name('search.save-paper');

// Public paper view
Route::get('/paper/{id}', [PaperController::class, 'view'])->name('paper.view');
Route::get('/recent', [PaperController::class, 'recent'])->name('paper.recent');

// Dashboard routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/upload-paper', [DashboardController::class, 'uploadPaper'])->name('dashboard.upload-paper');
    Route::post('/dashboard/upload-paper', [DashboardController::class, 'storePaper'])->name('dashboard.store-paper');
    Route::get('/dashboard/paper/{id}', [DashboardController::class, 'viewPaper'])->name('dashboard.view-paper');
    Route::get('/dashboard/paper/{id}/edit', [DashboardController::class, 'editPaper'])->name('dashboard.edit-paper');
    Route::put('/dashboard/paper/{id}', [DashboardController::class, 'updatePaper'])->name('dashboard.update-paper');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
    
    // Reviewer routes
    Route::get('/reviewer/articles', [ReviewerController::class, 'articles'])->name('reviewer.articles');
    Route::get('/reviewer/history', [ReviewerController::class, 'history'])->name('reviewer.history');
    Route::get('/reviewer/review/{id}', [ReviewerController::class, 'review'])->name('reviewer.review');
    Route::put('/reviewer/review/{id}', [ReviewerController::class, 'updateReview'])->name('reviewer.update-review');
    
    // Reader routes
    Route::get('/reader/saved-papers', [SearchController::class, 'savedPapers'])->name('reader.saved-papers');
    
    // Admin routes
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.store-user');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
    Route::post('/admin/papers', [AdminController::class, 'storePaper'])->name('admin.store-paper');
    Route::delete('/admin/papers/{id}', [AdminController::class, 'deletePaper'])->name('admin.delete-paper');
    Route::post('/admin/authors', [AdminController::class, 'storeAuthor'])->name('admin.store-author');
    Route::delete('/admin/authors/{id}', [AdminController::class, 'deleteAuthor'])->name('admin.delete-author');
    Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.store-category');
    Route::delete('/admin/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.delete-category');
});
