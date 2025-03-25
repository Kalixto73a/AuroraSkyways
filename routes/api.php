<?php

use Illuminate\Http\Request;
use App\Http\Middleware\IsUser;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\BookingController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('singIn');
    Route::post('/login', [AuthController::class, 'login'])->name('logIn');

    // Solo usuarios autenticados pueden acceder a estas rutas
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::post('/me', [AuthController::class, 'me'])->name('me');
    });
});

// Rutas para Administradores
Route::middleware(['auth:api', IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Bienvenido Admin']);
    });
});

Route::get('/bookings', [BookingController::class, 'all'])->name('allBookings')->middleware('isUser');
Route::post('/bookings', [BookingController::class, 'store'])->name('createBooking')->middleware('isUser');
Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookingShow')->middleware('isUser');
Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookingUpdate')->middleware('isUser');
Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookingDelete')->middleware('isUser');