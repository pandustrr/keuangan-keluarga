<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post('/auth/login', [ApiController::class, 'login']);
Route::post('/auth/register', [ApiController::class, 'register']);
Route::post('/auth/logout', [ApiController::class, 'logout']);
Route::get('/auth/session', [ApiController::class, 'getSession']);
Route::post('/auth/reset-password', [ApiController::class, 'resetPassword']);

Route::post('/db/query', [ApiController::class, 'query']);
Route::post('/storage/upload', [ApiController::class, 'uploadPhoto']);
