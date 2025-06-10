<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Apply Laravel best practices:
|   • Use “guest” middleware for unauthenticated routes.
|   • Use “auth” middleware to protect private areas.
|   • Group and namespace routes for clarity.
|   • Use nested resource routing for comments.
|   • Rely on route-model binding and CSRF protection.
|
*/

// Redirect root to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Guest-only routes (registration + login)
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('register', [AuthController::class, 'showRegisterForm'])
        ->name('register');
    Route::post('register', [AuthController::class, 'register']);

    // Login
    Route::get('login', [AuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes (authenticated users only)
Route::middleware('auth')->group(function () {
    // Logout (POST to prevent CSRF)
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Dashboard (single-action controller)
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    // User management
    Route::resource('users', UserController::class);

    // Category management
    Route::resource('categories', CategoryController::class);

    // Task management
    Route::resource('tasks', TaskController::class);

    // Priority management
    Route::resource('priorities', PriorityController::class);

    // Status management
    Route::resource('statuses', StatusController::class);

    // Nested comments under tasks
    // Only “store, edit, update, destroy” as needed
    Route::prefix('tasks/{task}')
         ->name('tasks.comments.')
         ->group(function () {
             // Create a new comment for a task
             Route::post('comments', [CommentController::class, 'store'])
                  ->name('store');

             // Show the edit-form for a comment
             Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])
                  ->name('edit');

             // Update an existing comment
             Route::put('comments/{comment}', [CommentController::class, 'update'])
                  ->name('update');

             // Delete a comment
             Route::delete('comments/{comment}', [CommentController::class, 'destroy'])
                  ->name('destroy');
         });
});
