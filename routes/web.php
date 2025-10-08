<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\TaskManagementController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\TaskController as UserTaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ReportController;


Route::get('/', function () {
    return view('welcome');
});


// Manual Authentication
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'googlelogin'])->name('auth.google');
Route::get('/auth/google-callback', [GoogleController::class, 'googleauthentication'])->name('auth.google-callback');


// ========================= ADMIN ROUTES =========================
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\CheckUserExists::class, \App\Http\Middleware\ForceChangePassword::class])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Update default password
    Route::post('/update-password', [UserManagementController::class, 'updatePassword'])->name('admin.updatePassword');

    // User management
    Route::resource('users', UserManagementController::class);
    Route::post('users/bulk-delete', [UserManagementController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::patch('users/{id}/toggle-role', [UserManagementController::class, 'toggleRole'])->name('users.toggleRole');
    Route::post('users/{id}/restore', [UserManagementController::class, 'restore'])->name('users.restore');

    // Task management
    Route::resource('tasks', TaskManagementController::class);
    Route::post('tasks/{id}/update-status', [TaskManagementController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::post('tasks/{id}/update-priority', [TaskManagementController::class, 'updatePriority'])->name('tasks.updatePriority');
    Route::post('tasks/{id}/update-assigned', [TaskManagementController::class, 'updateAssigned'])->name('tasks.updateAssigned');

    // Comments
    Route::get('comments', [AdminCommentController::class, 'index'])->name('admin.comments.index');
    Route::post('comments/store', [AdminCommentController::class, 'store'])->name('comments.store');
    Route::post('comments/update/{comment_id}', [AdminCommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/delete/{comment_id}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('comments/{task_id}', [AdminCommentController::class, 'getComments'])->name('comments.get');

    // Projects
    Route::resource('projects', ProjectController::class)
        ->parameters(['projects' => 'project']);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
});


// Export Reports
Route::get('admin/reports/export/{format}', [App\Http\Controllers\Admin\ReportController::class, 'export'])
    ->name('admin.reports.export');




// ========================= USER ROUTES =========================
// User Routes
Route::prefix('user')->middleware(['auth', \App\Http\Middleware\CheckUserExists::class])->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // My Tasks
    Route::get('tasks', [UserTaskController::class, 'index'])->name('user.tasks.index');
    Route::get('tasks/{id}', [UserTaskController::class, 'show'])->name('user.tasks.show');
    Route::get('tasks/{id}/edit', [UserTaskController::class, 'edit'])->name('user.tasks.edit');
    Route::put('tasks/{id}', [UserTaskController::class, 'update'])->name('user.tasks.update');


    // AJAX for status update
    Route::post('tasks/{id}/update-status', [UserTaskController::class, 'updateStatus'])->name('user.tasks.updateStatus');
});



// ========================= CALENDAR ROUTES =========================
Route::get('/admin/calendar', [CalendarController::class, 'index'])->name('admin.calendar');
Route::get('/user/calendar', [CalendarController::class, 'index'])->name('user.calendar');

// AJAX for events
Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
