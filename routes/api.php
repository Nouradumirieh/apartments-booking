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

    
   
   
   
   
    Route::get('/owner/booking-requests', [BookingController::class, 'ownerRequests']);
    Route::post('/bookings/{id}/approve', [BookingController::class, 'approve']);
    Route::post('/bookings/{id}/reject', [BookingController::class, 'reject']);
    Route::post('/reviews', [ReviewController::class, 'store']);
});


Route::middleware(['auth', 'tenant'])->group(function() {
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);
});


/*
Route::middleware(['auth:web', 'admin'])->group(function () {
    Route::get('/apartments/pending', [ApartmentController::class, 'pendingApartments']);
    Route::put('/apartments/{id}/approve', [ApartmentController::class, 'approveApartment']);
    Route::put('/apartments/{id}/reject', [ApartmentController::class, 'rejectApartment']);

    // Users
Route::get('/users', [ AdminController::class, 'allUsers']);
Route::get('/pending-users', [AdminController::class, 'pendingUsers']);
Route::post('/approve/{id}', [AdminController::class, 'approveUser']);
Route::post('/reject/{id}', [AdminController::class, 'rejectUser']);
//Route::post('/log', [AdminController::class, 'login']);
});*/
Route::middleware(['auth', 'tenant'])->group(function () {

    Route::post('/reviews', [ReviewController::class, 'store']);

    
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);

    
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
});


Route::get('/apartments/{apartment}/reviews', [ReviewController::class, 'apartmentReviews']);


Route::post('/bookings/{id}/status', [BookingController::class, 'updateStatus']);


Route::post('/update-fcm-token', function (Request $request) {
    $request->validate([
        'fcm_token' => 'required|string',
        'user_id' => 'required|exists:users,id',
    ]);

    $user = \App\Models\User::find($request->user_id);
    $user->update(['fcm_token' => $request->fcm_token]);

    return response()->json(['message' => 'Token updated successfully']);
});