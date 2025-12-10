<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {

    
    Route::post('/logout', [AuthController::class, 'logout']);

 
});
   /* Route::post('/profile', [ApiUserController::class, 'profile']);
    Route::put('/profile/update', [UserController::class, 'update']);
    Route::post('/profile/avatar', [UserController::class, 'uploadAvatar']);
    Route::post('/profile/id', [UserController::class, 'uploadID']);*/

Route::get('/admin/pending-users', [AdminController::class, 'pendingUsers']);
Route::post('/admin/approve/{id}', [AdminController::class, 'approveUser']);
Route::post('/admin/reject/{id}', [AdminController::class, 'rejectUser']);



// Routes for user profile management
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/update', [UserController::class, 'update']);
    Route::post('/upload-avatar', [UserController::class, 'uploadAvatar']);
    Route::post('/upload-id', [UserController::class, 'uploadID']);
});
