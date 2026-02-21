<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalihoController;

Route::get('/', function () { return view('landing'); })->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/api/balihos-new', [BalihoController::class, 'index']);

// Area Admin
Route::middleware('auth')->group(function () {
    Route::get('/admin', function () { return view('admin.dashboard'); })->name('admin.dashboard');

    // API Route untuk AJAX Javascript
    Route::get('/api/balihos', [BalihoController::class, 'index']);
    Route::post('/api/balihos', [BalihoController::class, 'store']);
    Route::put('/api/balihos/{id}', [BalihoController::class, 'update']);
    Route::patch('/api/balihos/{id}/toggle', [BalihoController::class, 'toggleStatus']);
    Route::delete('/api/balihos/{id}', [BalihoController::class, 'destroy']);
});
