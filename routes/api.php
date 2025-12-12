<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\ApplicationController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Login Required)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public: Anyone can see published jobs (Optional choice, but usually better if public)
// If you want to force login to see jobs, move this into the Protected group below.
Route::get('/jobs', [JobPostController::class, 'index']); 


/*
|--------------------------------------------------------------------------
| Protected Routes (Login Token Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Employer Routes ---
    Route::post('/jobs', [JobPostController::class, 'store']);       // Create Job
    Route::put('/jobs/{id}', [JobPostController::class, 'update']);  // Update Job
    Route::get('/jobs/{id}/applications', [JobPostController::class, 'getApplications']); // View CVs

    // --- Freelancer Routes ---
    // We haven't created ApplicationController yet, but here is the route for it
    Route::post('/applications', [ApplicationController::class, 'store']); // Apply (Upload CV)

});