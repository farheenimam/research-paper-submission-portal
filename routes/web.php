<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    if (auth()->check()) {
        // Hardcoded admin email redirect
        if (auth()->user()->email === 'farheenimam@gmail.com') {
            return redirect()->route('admin.dashboard');
        }
        // Redirect all users to search page
        return redirect()->route('search');
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
Route::post('/search/save/{id}', [SearchController::class, 'savePaper'])->name('search.save-paper');

// Public paper view
Route::get('/paper/{id}', [PaperController::class, 'view'])->name('paper.view');

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
    
    // Reader routes
    Route::get('/reader/saved-papers', [SearchController::class, 'savedPapers'])->name('reader.saved-papers');
    
    // Admin routes
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
    Route::get('/admin/users/{id}', [AdminController::class, 'viewUser'])->name('admin.view-user');
    Route::put('/admin/users/{id}', [ProfileController::class, 'update'])->name('admin.update-user');

    Route::delete('/admin/papers/{id}', [AdminController::class, 'deletePaper'])->name('admin.delete-paper');
    Route::delete('/admin/authors/{id}', [AdminController::class, 'deleteAuthor'])->name('admin.delete-author');
    Route::delete('/admin/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.delete-category');
    
    // Admin paper review routes
    Route::get('/admin/paper/{id}', [AdminController::class, 'viewPaper'])->name('admin.view-paper');
    Route::put('/admin/paper/{id}/approve', [AdminController::class, 'approvePaper'])->name('admin.approve-paper');
    Route::put('/admin/paper/{id}/reject', [AdminController::class, 'rejectPaper'])->name('admin.reject-paper');
});
