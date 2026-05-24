<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Routes d'authentification web
Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [WebAuthController::class, 'webLogin'])->name('login.post');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/map-data', [DashboardController::class, 'mapData'])->name('map-data');
});
