<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\CattleTypeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/logbooks/export', [LogbookController::class, 'export'])->name('logbooks.export');
    Route::post('/logbooks/{id}/restore', [LogbookController::class, 'restore'])->name('logbooks.restore');
    Route::resource('logbooks', LogbookController::class);
    Route::resource('routes', RouteController::class)->except(['show']);
    Route::resource('cattle-types', CattleTypeController::class)->except(['show']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/logbooks/{logbook}/print', [LogbookController::class, 'print'])->name('logbooks.print');
});
