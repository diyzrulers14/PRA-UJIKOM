<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

// =============================
// PUBLIC ROUTES
// =============================

// Register
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::post('/login', [AuthController::class, 'login']);


// =============================
// PROTECTED ROUTES
// =============================

Route::middleware('auth:sanctum')->group(function () {

    // Profil user yang sedang login
    Route::get('/me', [AuthController::class, 'me']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

});