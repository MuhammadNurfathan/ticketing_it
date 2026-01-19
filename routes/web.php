<?php

use App\Http\Controllers\{
    DepartmentController,
    LocationController,
    PriorityController,
    ProblemCategoryController,
    RoleController,
    StatusController,
    UserController,
    AssetsController,
    FeedbackController,
    ProfileController,
    ProjectController,
    TicketsController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route login & profile
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth/login');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tambahkan name di sini
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


// Admin + Superadmin (role_id 1,2)
Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::resource('locations', LocationController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('problem_categories', ProblemCategoryController::class);
    Route::resource('status', StatusController::class);
    Route::resource('project', ProjectController::class);
    Route::resource('priority', PriorityController::class);
    Route::resource('assets', AssetsController::class);

    Route::post('/DashboardTicketsAdmin/{ticket}/updatestatus', [TicketsController::class, 'updateStatus'])->name('DashboardTicketsAdmin.updateStatus');
    Route::post('/DashboardTicketsAdmin/{ticket}/updateStatusDone', [TicketsController::class, 'updatestatusDone'])->name('DashboardTicketsAdmin.updateStatusDone');

    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');
});

// Admin + Superadmin + User (role_id 1,2,3)
Route::middleware(['auth', 'role:1,2,3'])->group(function () {
    Route::resource('DashboardTicketsAdmin', TicketsController::class)->except(['show']);
    Route::get('/DashboardTicketsUser', [TicketsController::class, 'indexUser'])->name('DashboardTicketsUser.indexUser');
    Route::get('/DashboardTicketsUser/create', [TicketsController::class, 'createUser'])->name('DashboardTicketsUser.createUser');
    Route::get('/DashboardTicketsUser/{ticket_id}', [TicketsController::class, 'editUser'])->name('DashboardTicketsUser.edit');
    Route::get('/feedback/{ticket_id}', [FeedbackController::class, 'form'])->name('feedback.form');
    Route::post('/feedback/save', [FeedbackController::class, 'save'])->name('feedback.save');
    Route::put(
        '/DashboardTicketsUser/update/{id}',
        [TicketsController::class, 'updateUser']
    )->name('DashboardTicketsUser.update');
});

// Khusus Superadmin (role_id 2)
Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::post('/project/{project}/updateStatus', [ProjectController::class, 'updateStatus'])->name('project.updateStatus');
    Route::post('/project/{project}/updateProgress', [ProjectController::class, 'updateProgress'])->name('project.updateProgress');
    Route::post('/project/{projectHeaderId}/pending', [ProjectController::class, 'storePending'])->name('pending.store');
    Route::post('/project/{projectHeaderId}/continue', [ProjectController::class, 'continueProgress'])->name('project.continueProgress');
    Route::get('/projects/{project}/history', [ProjectController::class, 'history'])->name('projects.history');

    Route::get('/reports/ExcecutiveTicketsInsight', function () {
        return view('reports.ExcecutiveTicketsInsight');
    })->name('reports.ExcecutiveTicketsInsight');
    Route::get('/reports/TeamPerformanceTracker', function () {
        return view('reports.TeamPerformanceTracker');
    })->name('reports.TeamPerformanceTracker');
    Route::get('/reports/ProjectMonitoring', function () {
        return view('reports.ProjectMonitoring');
    })->name('reports.ProjectMonitoring');
    Route::get('/reports/ProjectTracking', function () {
        return view('reports.ProjectTracking');
    })->name('reports.ProjectTracking');
});

Route::get('/ticket-files/{filename}', function ($filename) {
    $filename = basename($filename);
    $path = storage_path('app/public/tickets/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    // Pake readfile langsung (paling reliable)
    $type = mime_content_type($path) ?: 'application/octet-stream';

    header('Content-Type: ' . $type);
    header('Content-Length: ' . filesize($path));
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Cache-Control: public, max-age=31536000');

    readfile($path);
    exit;
})
    ->middleware(['auth', 'role:1,2,3'])
    ->name('ticket.file');

require __DIR__ . '/auth.php';
