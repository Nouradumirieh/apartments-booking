<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;

Route::get('/', function () {
    return view('welcome');
});

// مسارات تسجيل الدخول
Route::get('/login', function() {
    return view('login');
})->name('login');

Route::post('/admin/login', [AdminController::class, 'loginadmin'])->name('admin.login');

// مجموعة مسارات الأدمن المحمية
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->group(function () {

    // لوحة التحكم
    Route::get('/dashboard', function(){
        return view('admin');
    })->name('admin.dashboard');

    Route::post('/logout', [AdminController::class, 'logoutadmin'])->name('admin.logout');

    // إدارة المستخدمين
    Route::get('/pending-users', [AdminController::class, 'pendingUsers']);
    Route::get('/all-users', [AdminController::class, 'allUsers']);
    Route::post('/approve/{id}', [AdminController::class, 'approveUser']);
    Route::post('/reject/{id}', [AdminController::class, 'rejectUser']);
    Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser']);

    // إدارة الشقق
    Route::get('/pending-apartments-page', function() {
    return view('pending-apartments'); // تأكد من إزالة أي نقاط زائدة في النهاية
})->name('admin.pending-apartments');

    Route::get('/pending-apartments', [AdminController::class, 'pendingApartments']);
    Route::post('/approve-apartment/{id}', [AdminController::class, 'approveApartment']);
    Route::post('/reject-apartment/{id}', [AdminController::class, 'rejectApartment']);
});