<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LocationController; // ← TAMBAHKAN INI
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\ProblemCategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetsController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/DashboardTicket', function () {
    return view('DashboardTicket');
})->middleware(['auth', 'verified'])->name('DashboardTicket');

// useless routes
// Just to demo sidebar dropdown links active states.
Route::get('/buttons/text', function () {
    return view('buttons-showcase.text');
})->middleware(['auth'])->name('buttons.text');

Route::get('/buttons/icon', function () {
    return view('buttons-showcase.icon');
})->middleware(['auth'])->name('buttons.icon');

Route::get('/buttons/text-icon', function () {
    return view('buttons-showcase.text-icon');
})->middleware(['auth'])->name('buttons.text-icon');


// HAPUS ROUTE INI (atau comment)
// Route::get('/location',function(){
//     return view('location/index');
// })->middleware(['auth','verified'])->name('location');

Route::get('/department',function(){
    return view('department/index');
})->middleware(['auth','verified'])->name('department');

// Location CRUD Routes - PINDAHKAN KE DALAM MIDDLEWARE AUTH
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('locations', LocationController::class);
});

// Department CRUD Routes - PINDAHKAN KE DALAM MIDDLEWARE AUTH
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('departments', DepartmentController::class);
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('roles', RoleController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('problem_categories', ProblemCategoryController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('status', StatusController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('priority', PriorityController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('assets', AssetsController::class);
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketsController;

Route::get('/DashboardTicket', [TicketsController::class, 'index'])->name('DashboardTicket');

require __DIR__ . '/auth.php';