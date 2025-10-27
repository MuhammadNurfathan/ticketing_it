<?php
// routes/api.php

use App\Http\Controllers\API\TicketReportController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

// Pie Chart - Tickets by Category
Route::get('/reports/tickets-by-category', [TicketReportController::class, 'ticketsByCategory']);

// Line Chart - Tickets Done per Month
Route::get('/reports/tickets-done-per-month', [TicketReportController::class, 'ticketsDonePerMonth']);

Route::get('/tickets/statistik', [TicketReportController::class, 'statistik']);
Route::get('/chart-tickets-by-Dev', [TicketReportController::class, 'chartTicketsByDev']);
Route::get('/chart-time-spent-by-dev', [TicketReportController::class, 'chartTimeSpentByDev']);