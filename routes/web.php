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
use App\Http\Controllers\projectmanager\ProjectManagerDashboardController;
use App\Http\Controllers\projectmanager\ProjectManagerProjectController;
use App\Http\Controllers\projectmanager\ProjectManagerTaskController;
use App\Http\Controllers\projectmember\projectmemberDashboardController;
use App\Http\Controllers\projectmember\ProjectMemberTaskController;


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
        Route::post('users/bulk/delete', [UserManagementController::class, 'bulkDelete'])->name('users.bulkDelete');
        Route::post('users/{id}/restore', [UserManagementController::class, 'restore'])->name('users.restore');
        Route::patch('users/{id}/toggle-role', [UserManagementController::class, 'toggleRole'])->name('users.toggleRole');
        Route::resource('users', UserManagementController::class);

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
        // combined comment and status update
        Route::post('/comments/store-with-status', [App\Http\Controllers\Admin\CommentController::class, 'storeWithStatus'])
            ->name('comments.storeWithStatus');


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

Route::prefix('projectmanager')->middleware(['auth', \App\Http\Middleware\CheckUserExists::class])->group(function () {
    Route::get('/calendar', [CalendarController::class, 'index'])->name('projectmanager.calendar');
});

Route::prefix('projectmember')->middleware(['auth', \App\Http\Middleware\CheckUserExists::class])->group(function () {
    Route::get('/calendar', [CalendarController::class, 'index'])->name('projectmember.calendar');
});

// ========================= PROJECT MANAGER ROUTES =========================
Route::prefix('projectmanager')
    ->middleware(['auth', \App\Http\Middleware\CheckUserExists::class])
    ->group(function () {

        Route::get('/dashboard', [ProjectManagerDashboardController::class, 'index'])
            ->name('projectmanager.dashboard');

        // Projects (resource)
        Route::resource('projects', ProjectManagerProjectController::class)
            ->names([
                'index' => 'projectmanager.projects.index',
                'create' => 'projectmanager.projects.create',
                'store' => 'projectmanager.projects.store',
                'show' => 'projectmanager.projects.show',
                'edit' => 'projectmanager.projects.edit',
                'update' => 'projectmanager.projects.update',
                // No delete
            ])
            ->except(['destroy']);

        // Tasks (resource)
        Route::resource('tasks', ProjectManagerTaskController::class)
            ->names([
                'index' => 'projectmanager.tasks.index',
                'create' => 'projectmanager.tasks.create',
                'store' => 'projectmanager.tasks.store',
                'show' => 'projectmanager.tasks.show',
                'edit' => 'projectmanager.tasks.edit',
                'update' => 'projectmanager.tasks.update',
                // No delete
            ])
            ->except(['destroy']); //

        // Optional: Comments on tasks
        Route::post('tasks/comments', [ProjectManagerTaskController::class, 'storeComment'])
            ->name('projectmanager.tasks.comments.store');
    });

// ========================= PROJECT MEMBER ROUTES =========================
Route::prefix('projectmember')
    ->middleware(['auth', \App\Http\Middleware\CheckUserExists::class])
    ->group(function () {

        Route::get('/dashboard', [ProjectMemberDashboardController::class, 'index'])
            ->name('projectmember.dashboard');

        // Tasks (only index & show)
        Route::resource('tasks', ProjectMemberTaskController::class)
            ->only(['index', 'show'])
            ->names([
                'index' => 'projectmember.tasks.index',
                'show' => 'projectmember.tasks.show',
            ]);

        // Optional: Comments for member
        Route::post('tasks/comments', [ProjectMemberTaskController::class, 'storeComment'])
            ->name('projectmember.tasks.comments.store');
    });
