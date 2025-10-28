<?php
// routes/api.php

use App\Http\Controllers\api\ProjectReportController;
use App\Http\Controllers\API\TicketReportController;
use Illuminate\Support\Facades\Route;

// Pie Chart - Tickets by Category
Route::get('/reports/tickets-by-category', [TicketReportController::class, 'ticketsByCategory']);

// Line Chart - Tickets Done per Month
Route::get('/reports/tickets-done-per-month', [TicketReportController::class, 'ticketsDonePerMonth']);

Route::get('/tickets/statistik', [TicketReportController::class, 'statistik']);
Route::get('/chart-tickets-by-dev', [TicketReportController::class, 'chartTicketsByDev']);
Route::get('/chart-time-spent-by-dev', [TicketReportController::class, 'chartTimeSpentByDev']);
Route::get('/tickets-by-support', [TicketReportController::class, 'ticketsBySupport']);
Route::get('/tickets/preview', [TicketReportController::class, 'preview']);
Route::get('/tickets/export', [TicketReportController::class, 'export']);
Route::get('/ProjectQueue', [ProjectReportController::class, 'ProjectQueue']);
Route::get('/ProjectMonitorGraph', [ProjectReportController::class, 'gantChart']);
