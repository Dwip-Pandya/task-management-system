<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\TaskManagementController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ChartsController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\TaskController as UserTaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\projectmanager\ProjectManagerDashboardController;
use App\Http\Controllers\projectmanager\ProjectManagerProjectController;
use App\Http\Controllers\projectmanager\ProjectManagerTaskController;
use App\Http\Controllers\projectmember\ProjectMemberDashboardController;
use App\Http\Controllers\projectmember\ProjectMemberTaskController;
use App\Http\Controllers\NotificationController;

// Middlewares
use App\Http\Middleware\CheckUserExists;
use App\Http\Middleware\ForceChangePassword;
use App\Http\Middleware\CheckDeactivatedUser;
use App\Http\Middleware\CheckModulePermission;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'showLogin'])->name('login.form');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'googlelogin'])->name('auth.google');
Route::get('/auth/google-callback', [GoogleController::class, 'googleauthentication'])->name('auth.google-callback');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware([CheckDeactivatedUser::class, CheckUserExists::class, ForceChangePassword::class])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // User management
        Route::get('users', [UserManagementController::class, 'index'])
            ->middleware([CheckModulePermission::class . ':user management,view'])
            ->name('users.index');

        Route::get('users/create', [UserManagementController::class, 'create'])
            ->middleware([CheckModulePermission::class . ':user management,add'])
            ->name('users.create');

        Route::post('users', [UserManagementController::class, 'store'])
            ->middleware([CheckModulePermission::class . ':user management,add'])
            ->name('users.store');

        Route::get('users/{user}/edit', [UserManagementController::class, 'edit'])
            ->middleware([CheckModulePermission::class . ':user management,edit'])
            ->name('users.edit');

        Route::match(['put', 'patch'], 'users/{user}', [UserManagementController::class, 'update'])
            ->middleware([CheckModulePermission::class . ':user management,edit'])
            ->name('users.update');

        Route::delete('users/{user}', [UserManagementController::class, 'destroy'])
            ->middleware([CheckModulePermission::class . ':user management,delete'])
            ->name('users.destroy');

        Route::post('users/bulk-action', [UserManagementController::class, 'bulkAction'])
            ->middleware([CheckModulePermission::class . ':user management,edit'])
            ->name('users.bulkAction');

        Route::post('users/{user}/restore', [UserManagementController::class, 'restore'])
            ->name('users.restore');

        Route::post('users/{user}/update-permissions', [UserManagementController::class, 'rolePermissions'])
            ->middleware([CheckModulePermission::class . ':user management,edit'])
            ->name('users.updatePermissions');

        Route::post('users/update-password', [UserManagementController::class, 'updatePassword'])
            ->name('users.updatePassword');

        Route::post('users/{user}/switch-to', [UserManagementController::class, 'switchToUser'])
            ->name('users.switchTo');

        Route::post('users/switch-back', [UserManagementController::class, 'switchBack'])
            ->name('users.switchBack');

        Route::get('users/{id}/role-permissions', [UserManagementController::class, 'rolePermissions'])->middleware([CheckModulePermission::class . ':user management,edit'])->name('admin.users.rolePermissions');

        // Tasks
        Route::get('tasks', [TaskManagementController::class, 'index'])
            ->middleware([CheckModulePermission::class . ':task management,view'])
            ->name('tasks.index');

        Route::get('tasks/create', [TaskManagementController::class, 'create'])
            ->middleware([CheckModulePermission::class . ':task management,add'])
            ->name('tasks.create');

        Route::post('tasks', [TaskManagementController::class, 'store'])
            ->middleware([CheckModulePermission::class . ':task management,add'])
            ->name('tasks.store');

        Route::get('tasks/{task}', [TaskManagementController::class, 'show'])
            ->middleware([CheckModulePermission::class . ':task management,view'])
            ->name('tasks.show');

        Route::get('tasks/{task}/edit', [TaskManagementController::class, 'edit'])
            ->middleware([CheckModulePermission::class . ':task management,edit'])
            ->name('tasks.edit');

        Route::match(['put', 'patch'], 'tasks/{task}', [TaskManagementController::class, 'update'])
            ->middleware([CheckModulePermission::class . ':task management,edit'])
            ->name('tasks.update');

        Route::delete('tasks/{task}', [TaskManagementController::class, 'destroy'])
            ->middleware([CheckModulePermission::class . ':task management,delete'])
            ->name('tasks.destroy');

        Route::post('tasks/{id}/update-status', [TaskManagementController::class, 'updateStatus'])->middleware([CheckModulePermission::class . ':task management,edit'])->name('tasks.updateStatus');
        Route::post('tasks/{id}/update-priority', [TaskManagementController::class, 'updatePriority'])->middleware([CheckModulePermission::class . ':task management,edit'])->name('tasks.updatePriority');
        Route::post('tasks/{id}/update-assigned', [TaskManagementController::class, 'updateAssigned'])->middleware([CheckModulePermission::class . ':task management,edit'])->name('tasks.updateAssigned');

        // Comments
        Route::get('comments', [AdminCommentController::class, 'index'])->middleware([CheckModulePermission::class . ':comment management,view'])->name('admin.comments.index');
        Route::post('comments/store', [AdminCommentController::class, 'store'])->middleware([CheckModulePermission::class . ':comment management,add'])->name('comments.store');
        Route::post('comments/update/{comment_id}', [AdminCommentController::class, 'update'])->middleware([CheckModulePermission::class . ':comment management,edit'])->name('comments.update');
        Route::delete('comments/delete/{comment_id}', [AdminCommentController::class, 'destroy'])->middleware([CheckModulePermission::class . ':comment management,delete'])->name('comments.destroy');
        Route::post('/comments/store-with-status', [AdminCommentController::class, 'storeWithStatus'])->middleware([CheckModulePermission::class . ':comment management,add'])->name('comments.storeWithStatus');

        // Projects
        Route::get('projects', [ProjectController::class, 'index'])
            // ->middleware([CheckModulePermission::class . ':project management,view'])
            ->name('projects.index');

        Route::get('projects/create', [ProjectController::class, 'create'])
            ->middleware([CheckModulePermission::class . ':project management,add'])
            ->name('projects.create');

        Route::post('projects', [ProjectController::class, 'store'])
            ->middleware([CheckModulePermission::class . ':project management,add'])
            ->name('projects.store');

        Route::get('projects/{project}', [ProjectController::class, 'show'])
            ->middleware([CheckModulePermission::class . ':project management,view'])
            ->name('projects.show');

        Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])
            ->middleware([CheckModulePermission::class . ':project management,edit'])
            ->name('projects.edit');

        Route::match(['put', 'patch'], 'projects/{project}', [ProjectController::class, 'update'])
            ->middleware([CheckModulePermission::class . ':project management,edit'])
            ->name('projects.update');

        Route::delete('projects/{project}', [ProjectController::class, 'destroy'])
            ->middleware([CheckModulePermission::class . ':project management,delete'])
            ->name('projects.destroy');

        // Admin Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
        Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('admin.notifications.destroy');
    });


/*
|--------------------------------------------------------------------------
| PROJECT MANAGER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('projectmanager')
    ->middleware([CheckDeactivatedUser::class, CheckUserExists::class])
    ->group(function () {

        Route::get('/dashboard', [ProjectManagerDashboardController::class, 'index'])->name('projectmanager.dashboard');

        Route::resource('projects', ProjectManagerProjectController::class)
            ->except(['destroy'])
            ->names('projectmanager.projects');

        Route::resource('tasks', ProjectManagerTaskController::class)
            ->except(['destroy'])
            ->names('projectmanager.tasks');

        Route::post('tasks/comments', [ProjectManagerTaskController::class, 'storeComment'])->name('projectmanager.tasks.comments.store');

        // ✅ Project Manager Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('projectmanager.notifications.index');
        Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('projectmanager.notifications.destroy');
    });


/*
|--------------------------------------------------------------------------
| PROJECT MEMBER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('projectmember')
    ->middleware([CheckDeactivatedUser::class, CheckUserExists::class])
    ->group(function () {

        Route::get('/dashboard', [ProjectMemberDashboardController::class, 'index'])->name('projectmember.dashboard');

        Route::resource('tasks', ProjectMemberTaskController::class)
            ->only(['index', 'show'])
            ->names('projectmember.tasks');

        Route::post('tasks/comments', [ProjectMemberTaskController::class, 'storeComment'])->name('projectmember.tasks.comments.store');

        // ✅ Project Member Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('projectmember.notifications.index');
        Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('projectmember.notifications.destroy');
    });


/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('user')
    ->middleware([CheckDeactivatedUser::class, CheckUserExists::class])
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

        Route::resource('tasks', UserTaskController::class)->names('user.tasks');

        // ✅ User Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('user.notifications.index');
        Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('user.notifications.destroy');
    });


/*
|--------------------------------------------------------------------------
| CALENDAR ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/admin/calendar', [CalendarController::class, 'index'])->middleware([CheckModulePermission::class . ':calendar viewing,view'])
    ->name('admin.calendar');

Route::get('/user/calendar', [CalendarController::class, 'index'])->middleware([CheckModulePermission::class . ':calendar viewing,view'])
    ->name('user.calendar');

Route::get('/projectmanager/calendar', [CalendarController::class, 'index'])->middleware([CheckModulePermission::class . ':calendar viewing,view'])
    ->name('projectmanager.calendar');

Route::get('/projectmember/calendar', [CalendarController::class, 'index'])->middleware([CheckModulePermission::class . ':calendar viewing,view'])
    ->name('projectmember.calendar');

Route::get('/calendar/events', [CalendarController::class, 'events'])->middleware([CheckModulePermission::class . ':calendar viewing,view'])
    ->name('calendar.events');


/*
|--------------------------------------------------------------------------
| ANALYTICS CHART ROUTES
|--------------------------------------------------------------------------
*/
// Admin
Route::prefix('admin/charts')
    ->name('admin.charts.')
    ->middleware([CheckModulePermission::class . ':view chart analytics,view'])
    ->group(function () {
        Route::get('/', [ChartsController::class, 'index'])->name('index');
        Route::get('/status', [ChartsController::class, 'getTaskStatusData'])->name('status');
        Route::get('/priority', [ChartsController::class, 'getTasksByPriority'])->name('priority');
        Route::get('/projects', [ChartsController::class, 'getTasksPerProject'])->name('projects');
        Route::get('/users', [ChartsController::class, 'getTasksPerUser'])->name('users');
        Route::get('/monthly', [ChartsController::class, 'getTasksCompletedOverTime'])->name('monthly');
    });

// Project Manager
Route::prefix('projectmanager/charts')
    ->name('projectmanager.charts.')
    ->middleware([CheckModulePermission::class . ':view chart analytics,view'])
    ->group(function () {
        Route::get('/', [ChartsController::class, 'index'])->name('index');
        Route::get('/status', [ChartsController::class, 'getTaskStatusData'])->name('status');
        Route::get('/priority', [ChartsController::class, 'getTasksByPriority'])->name('priority');
        Route::get('/projects', [ChartsController::class, 'getTasksPerProject'])->name('projects');
        Route::get('/users', [ChartsController::class, 'getTasksPerUser'])->name('users');
        Route::get('/monthly', [ChartsController::class, 'getTasksCompletedOverTime'])->name('monthly');
    });

// Project Member
Route::prefix('projectmember/charts')
    ->name('projectmember.charts.')
    ->middleware([CheckModulePermission::class . ':view chart analytics,view'])
    ->group(function () {
        Route::get('/', [ChartsController::class, 'index'])->name('index');
        Route::get('/status', [ChartsController::class, 'getTaskStatusData'])->name('status');
        Route::get('/priority', [ChartsController::class, 'getTasksByPriority'])->name('priority');
        Route::get('/projects', [ChartsController::class, 'getTasksPerProject'])->name('projects');
        Route::get('/users', [ChartsController::class, 'getTasksPerUser'])->name('users');
        Route::get('/monthly', [ChartsController::class, 'getTasksCompletedOverTime'])->name('monthly');
    });

// Regular User
Route::prefix('user/charts')
    ->name('user.charts.')
    ->middleware([CheckModulePermission::class . ':view chart analytics,view'])
    ->group(function () {
        Route::get('/', [ChartsController::class, 'index'])->name('index');
        Route::get('/status', [ChartsController::class, 'getTaskStatusData'])->name('status');
        Route::get('/priority', [ChartsController::class, 'getTasksByPriority'])->name('priority');
        Route::get('/projects', [ChartsController::class, 'getTasksPerProject'])->name('projects');
        Route::get('/users', [ChartsController::class, 'getTasksPerUser'])->name('users');
        Route::get('/monthly', [ChartsController::class, 'getTasksCompletedOverTime'])->name('monthly');
    });

/*
|--------------------------------------------------------------------------
| REPORT ROUTES
|--------------------------------------------------------------------------
*/

// ------------------------- ADMIN -------------------------
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware([CheckModulePermission::class . ':report generation,view'])
        ->name('admin.reports.index');

    Route::get('/reports/export/{format}', [ReportController::class, 'export'])
        ->middleware([CheckModulePermission::class . ':report generation,view'])
        ->name('admin.reports.export');
});

// ------------------------- PROJECT MANAGER -------------------------
Route::prefix('projectmanager')->middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware([CheckModulePermission::class . ':report generation,view'])
        ->name('projectmanager.reports.index');

    Route::get('/reports/export/{format}', [ReportController::class, 'export'])
        ->middleware([CheckModulePermission::class . ':report generation,view'])
        ->name('projectmanager.reports.export');
});

// ------------------------- PROJECT MEMBER -------------------------
Route::prefix('projectmember')->middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware([CheckModulePermission::class . ':report generation,view'])
        ->name('projectmember.reports.index');

    Route::get('/reports/export/{format}', [ReportController::class, 'export'])
        ->middleware([CheckModulePermission::class . ':report generation,view'])
        ->name('projectmember.reports.export');
});

// ------------------------- USER -------------------------
Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware([CheckModulePermission::class . ':report generation,view'])
        ->name('user.reports.index');

    Route::get('/reports/export/{format}', [ReportController::class, 'export'])
        ->middleware([CheckModulePermission::class . ':report generation,view'])
        ->name('user.reports.export');
});

/*
|--------------------------------------------------------------------------
| LOG TEST
|--------------------------------------------------------------------------
*/
Route::get('/test-logs', function () {
    Log::debug('Debug log at ' . Carbon::now());
    Log::info('Info log at ' . Carbon::now());
    Log::error('Error log at ' . Carbon::now());
    return 'Logs written! Check storage/logs folder.';
});
