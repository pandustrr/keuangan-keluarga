<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// API Routes (handling both /api/auth/login and /auth/login for Vercel compatibility)
Route::post('/api/auth/login', [ApiController::class, 'login']);
Route::post('/api/auth/register', [ApiController::class, 'register']);
Route::post('/api/auth/logout', [ApiController::class, 'logout']);
Route::get('/api/auth/session', [ApiController::class, 'getSession']);
Route::post('/api/auth/reset-password', [ApiController::class, 'resetPassword']);

Route::post('/api/db/query', [ApiController::class, 'query']);
Route::post('/api/storage/upload', [ApiController::class, 'uploadPhoto']);

Route::post('/auth/login', [ApiController::class, 'login']);
Route::post('/auth/register', [ApiController::class, 'register']);
Route::post('/auth/logout', [ApiController::class, 'logout']);
Route::get('/auth/session', [ApiController::class, 'getSession']);
Route::post('/auth/reset-password', [ApiController::class, 'resetPassword']);

Route::post('/db/query', [ApiController::class, 'query']);
Route::post('/storage/upload', [ApiController::class, 'uploadPhoto']);

// SPA View Route
Route::get('/', function () {
    return view('app');
});

Route::fallback(function () {
    return view('app');
});
