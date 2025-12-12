<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
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


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/my', [BookingController::class, 'myBookings']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
});
Route::get('/apartments', [ApartmentController::class, 'index']); 
Route::get('/apartments/{id}', [ApartmentController::class, 'show']); 

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store']);
});
