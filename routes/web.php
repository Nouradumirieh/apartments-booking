<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;

Route::get('/', function () {
    return view('welcome');
});
// ابحث عن هذا السطر وقم بتعديله ليصبح هكذا
Route::get('/login', function() {
    return view('login');
})->name('login'); // غير الاسم من loginadmin إلى login فقط
//Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('loginadmin');
Route::post('/admin/login', [AdminController::class, 'loginadmin'])->name('admin.login');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function(){
        return view('admin');
    })->name('admin.dashboard');

    // إدارة المستخدمين
    Route::get('/pending-users', [AdminController::class, 'pendingUsers']);
    Route::get('/all-users', [AdminController::class, 'allUsers']); // أضفت هذا
    Route::post('/approve/{id}', [AdminController::class, 'approveUser']);
    Route::post('/reject/{id}', [AdminController::class, 'rejectUser']);
     Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser']);
});





