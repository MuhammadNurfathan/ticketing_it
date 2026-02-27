<?php

use App\Http\Controllers\{
    AssetsController,
    CategoryController,
    DepartmentController,
    FeedbackController,
    LocationController,
    PriorityController,
    ProfileController,
    ProjectController,
    RoleController,
    StatusController,
    TicketsController,
    UserController
};
use App\Models\Category;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', fn () => redirect()->route('login'));
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Tickets Routes
    |--------------------------------------------------------------------------
    */
    // Admin Tickets (role_id = 1)
    Route::middleware('role:1')->group(function () {
        Route::resource('DashboardTicketsAdmin', TicketsController::class)->except(['show']);

        Route::post('/DashboardTicketsAdmin/{ticket}/updatestatus', [TicketsController::class, 'updateStatus'])
            ->name('DashboardTicketsAdmin.updateStatus');

        Route::post('/DashboardTicketsAdmin/{ticket}/updateStatusDone', [TicketsController::class, 'updatestatusDone'])
            ->name('DashboardTicketsAdmin.updateStatusDone');

                    Route::prefix('project')->name('project.')->group(function () {
            Route::post('/{project}/updateStatus', [ProjectController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/{project}/updateProgress', [ProjectController::class, 'updateProgress'])->name('updateProgress');
            Route::post('/{projectHeaderId}/pending', [ProjectController::class, 'storePending'])->name('pending.store');
            Route::post('/{projectHeaderId}/continue', [ProjectController::class, 'continueProgress'])->name('continueProgress');
            Route::put('/{project}/done', [ProjectController::class, 'done'])->name('done');
        });
        

        Route::get('/projects/{project}/history', [ProjectController::class, 'history'])->name('projects.history');
    });

    // User Tickets (role_id = 1,2,3)
    Route::middleware('role:1,2,3')->prefix('DashboardTicketsUser')->name('DashboardTicketsUser.')->group(function () {
        Route::get('/', [TicketsController::class, 'indexUser'])->name('index');
        Route::get('/create', [TicketsController::class, 'createUser'])->name('create');
        Route::get('/{ticket_id}', [TicketsController::class, 'editUser'])->whereNumber('ticket_id')->name('edit');
        Route::put('/update/{id}', [TicketsController::class, 'updateUser'])->name('update');
    });

    // Feedback (akses semua user login)
    Route::middleware('role:1,2,3')->group(function () {
        Route::get('/feedback/{ticket_id}', [FeedbackController::class, 'form'])->name('feedback.form');
        Route::post('/feedback/save', [FeedbackController::class, 'save'])->name('feedback.save');
    });

    Route::middleware('role:1,2')->group(function () {
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Only Routes (role_id = 1)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:1')->group(function () {
        Route::resources([
            'locations'         => LocationController::class,
            'departments'       => DepartmentController::class,
            'users'             => UserController::class,
            'roles'             => RoleController::class,
            'categories'        => CategoryController::class,
            'status'            => StatusController::class,
            'project'           => ProjectController::class,
            'priority'          => PriorityController::class,
            'assets'            => AssetsController::class,
        ]);


    });

    /*
    |--------------------------------------------------------------------------
    | Manager Only Routes (role_id = 2)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:2')->group(function () {


        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::view('/ExcecutiveTicketsInsight', 'reports.ExcecutiveTicketsInsight')->name('ExcecutiveTicketsInsight');
            Route::view('/TeamPerformanceTracker', 'reports.TeamPerformanceTracker')->name('TeamPerformanceTracker');
            Route::view('/ProjectMonitoring', 'reports.ProjectMonitoring')->name('ProjectMonitoring');
            Route::view('/ProjectTracking', 'reports.ProjectTracking')->name('ProjectTracking');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Ticket File Download (role_id = 1,2,3)
    |--------------------------------------------------------------------------
    */
    Route::get('/ticket-files/{filename}', function ($filename) {
        $path = storage_path('app/public/tickets/' . basename($filename));

        if (!file_exists($path)) abort(404);

        return response()->file($path);
    })->middleware('role:1,2,3')->name('ticket.file');
});

require __DIR__ . '/auth.php';