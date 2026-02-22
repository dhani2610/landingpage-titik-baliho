<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalihoController;

// Halaman Depan
Route::get('/', function () { return view('landing'); })->name('landing');

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// API UNTUK LANDING PAGE (FRONTEND)
// ==========================================
Route::get('/api/balihos-new', [BalihoController::class, 'landingBalihos']);
Route::get('/api/landing-stats', [BalihoController::class, 'landingStats']);
Route::get('/api/active-provinces', [BalihoController::class, 'activeProvinces']);
Route::get('/api/active-kabupaten', [BalihoController::class, 'activeKabupaten']);

Route::get('/convert-base64', [BalihoController::class, 'convertSemuaBase64']);

// ==========================================
// AREA ADMIN
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/admin', function () { return view('admin.dashboard'); })->name('admin.dashboard');

    // API Route untuk AJAX Javascript Admin
    Route::get('/api/balihos', [BalihoController::class, 'index']);
    Route::post('/api/balihos', [BalihoController::class, 'store']);
    Route::put('/api/balihos/{id}', [BalihoController::class, 'update']);
    Route::patch('/api/balihos/{id}/toggle', [BalihoController::class, 'toggleStatus']);
    Route::delete('/api/balihos/{id}', [BalihoController::class, 'destroy']);
});
