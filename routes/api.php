<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, AdminController, ApartmentController, 
    BookingController, CityController, ProvinceController, 
    ReviewController, UserController
};


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/apartments', [ApartmentController::class, 'index']); 
Route::get('/apartments/{id}', [ApartmentController::class, 'show']); 
Route::get('/provinces', [ProvinceController::class, 'index']);
Route::get('/provinces/{province_id}/cities', [CityController::class, 'getByProvince']);

Route::middleware('auth:sanctum')->group(function () {
    
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/update', [UserController::class, 'update']);
    Route::post('/upload-avatar', [UserController::class, 'uploadAvatar']);
    Route::post('/upload-id', [UserController::class, 'uploadID']);


    Route::post('/apartments', [ApartmentController::class, 'store']); 
    Route::put('/apartments/{id}', [ApartmentController::class, 'update']);
    Route::delete('/apartments/{id}', [ApartmentController::class, 'destroy']);

    
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/my', [BookingController::class, 'myBookings']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
    Route::get('/owner/booking-requests', [BookingController::class, 'ownerRequests']);
    Route::post('/bookings/{id}/approve', [BookingController::class, 'approve']);
    Route::post('/bookings/{id}/reject', [BookingController::class, 'reject']);
    Route::post('/reviews', [ReviewController::class, 'store']);
});

Route::get('/admin/pending-users', [AdminController::class, 'pendingUsers']);
Route::post('/admin/approve/{id}', [AdminController::class, 'approveUser']);
Route::post('/admin/reject/{id}', [AdminController::class, 'rejectUser']);
Route::get('/users', [ AdminController::class, 'allUsers']);