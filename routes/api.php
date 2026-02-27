<?php
// routes/api.php

use App\Http\Controllers\API\ProjectReportController;
use App\Http\Controllers\API\TicketReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Manager Only (role_id = 2)
|--------------------------------------------------------------------------
|
| Semua route di sini hanya bisa diakses manager (role_id = 2)
| Menggunakan Sanctum + RoleMiddleware
|
*/

    Route::get('/reports/tickets-by-category', [TicketReportController::class, 'ticketsByCategory']);
    Route::get('/reports/tickets-done-per-month', [TicketReportController::class, 'ticketsDonePerMonth']);
    Route::get('/tickets/statistik', [TicketReportController::class, 'statistik']);
    Route::get('/chart-tickets-by-dev', [TicketReportController::class, 'chartTicketsByDev']);
    Route::get('/chart-time-spent-by-dev', [TicketReportController::class, 'chartTimeSpentByDev']);
    Route::get('/tickets-by-support', [TicketReportController::class, 'ticketsBySupport']);
    Route::get('/tickets/preview', [TicketReportController::class, 'preview']);
    Route::get('/tickets/export', [TicketReportController::class, 'export']);
    Route::get('/chart-time-spent-by-department', [TicketReportController::class, 'chartTimeSpentByDepartment']);

    /*
    |--------------------------------------------------------------------------
    | Project Reports / Project Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/ProjectQueue', [ProjectReportController::class, 'ProjectQueue']);
    Route::get('/ProjectMonitorGraph', [ProjectReportController::class, 'gantChart']);
    Route::get('/SummaryProject', [ProjectReportController::class, 'summary']);
    Route::get('/projects-by-developer', [ProjectReportController::class, 'projectByDeveloper']);
    Route::get('/projects/preview', [ProjectReportController::class, 'previewProject']);
    Route::get('/projects/export', [ProjectReportController::class, 'exportProject']);

    // Bisa tambahin route manager khusus update status/progress
    Route::post('/project/{project}/updateStatus', [ProjectReportController::class, 'updateStatus']);
    Route::post('/project/{project}/updateProgress', [ProjectReportController::class, 'updateProgress']);
    Route::post('/project/{projectHeaderId}/pending', [ProjectReportController::class, 'storePending']);
    Route::post('/project/{projectHeaderId}/continue', [ProjectReportController::class, 'continueProgress']);
    Route::get('/projects/{project}/history', [ProjectReportController::class, 'history']);
