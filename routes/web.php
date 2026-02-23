<?php

use App\Http\Controllers\{
    AssetsController,
    DepartmentController,
    FeedbackController,
    LocationController,
    PriorityController,
    ProblemCategoryController,
    ProfileController,
    ProjectController,
    RoleController,
    StatusController,
    TicketsController,
    UserController
};
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', fn () => redirect()->route('login'));
});

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets (akses semua user login: 1,2,3)
    Route::resource('DashboardTicketsAdmin', TicketsController::class)->except(['show']);

    Route::get('/DashboardTicketsUser', [TicketsController::class, 'indexUser'])
        ->name('DashboardTicketsUser.indexUser');

    Route::get('/DashboardTicketsUser/create', [TicketsController::class, 'createUser'])
        ->name('DashboardTicketsUser.createUser');

    Route::get('/DashboardTicketsUser/{ticket_id}', [TicketsController::class, 'editUser'])
        ->whereNumber('ticket_id')
        ->name('DashboardTicketsUser.edit');

    Route::put('/DashboardTicketsUser/update/{id}', [TicketsController::class, 'updateUser'])
        ->name('DashboardTicketsUser.update');

    // Feedback user (akses semua user login)
    Route::get('/feedback/{ticket_id}', [FeedbackController::class, 'form'])->name('feedback.form');
    Route::post('/feedback/save', [FeedbackController::class, 'save'])->name('feedback.save');

    // Admin only (role_id = 1)
    Route::middleware('role:1')->group(function () {
        Route::resource('locations', LocationController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('problem_categories', ProblemCategoryController::class);
        Route::resource('status', StatusController::class);
        Route::resource('project', ProjectController::class);
        Route::resource('priority', PriorityController::class);
        Route::resource('assets', AssetsController::class);

        Route::post('/DashboardTicketsAdmin/{ticket}/updatestatus', [TicketsController::class, 'updateStatus'])
            ->name('DashboardTicketsAdmin.updateStatus');

        Route::post('/DashboardTicketsAdmin/{ticket}/updateStatusDone', [TicketsController::class, 'updatestatusDone'])
            ->name('DashboardTicketsAdmin.updateStatusDone');

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');
    });

    // Manager only (role_id = 2)
    Route::middleware('role:2')->group(function () {
        Route::post('/project/{project}/updateStatus', [ProjectController::class, 'updateStatus'])
            ->name('project.updateStatus');

        Route::post('/project/{project}/updateProgress', [ProjectController::class, 'updateProgress'])
            ->name('project.updateProgress');

        Route::post('/project/{projectHeaderId}/pending', [ProjectController::class, 'storePending'])
            ->name('pending.store');

        Route::post('/project/{projectHeaderId}/continue', [ProjectController::class, 'continueProgress'])
            ->name('project.continueProgress');

        Route::get('/projects/{project}/history', [ProjectController::class, 'history'])
            ->name('projects.history');

        Route::view('/reports/ExcecutiveTicketsInsight', 'reports.ExcecutiveTicketsInsight')
            ->name('reports.ExcecutiveTicketsInsight');

        Route::view('/reports/TeamPerformanceTracker', 'reports.TeamPerformanceTracker')
            ->name('reports.TeamPerformanceTracker');

        Route::view('/reports/ProjectMonitoring', 'reports.ProjectMonitoring')
            ->name('reports.ProjectMonitoring');

        Route::view('/reports/ProjectTracking', 'reports.ProjectTracking')
            ->name('reports.ProjectTracking');
    });

    // Ticket file (akses semua user login: 1,2,3)
    Route::get('/ticket-files/{filename}', function ($filename) {
        $filename = basename($filename);
        $path = storage_path('app/public/tickets/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        $type = mime_content_type($path) ?: 'application/octet-stream';

        header('Content-Type: ' . $type);
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Cache-Control: public, max-age=31536000');

        readfile($path);
        exit;
    })->middleware('role:1,2,3')->name('ticket.file');
});

require __DIR__ . '/auth.php';