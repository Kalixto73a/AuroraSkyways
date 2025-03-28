<?php

use Illuminate\Http\Request;
use App\Http\Middleware\IsUser;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\FlightsController;
use App\Http\Controllers\Api\PlanesController;
use App\Http\Controllers\Api\BookingController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('singIn');
    Route::post('/login', [AuthController::class, 'login'])->name('logIn');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');

    Route::middleware('auth:api')->group(function () {
        Route::post('/me', [AuthController::class, 'me'])->name('me');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::middleware(['isAdmin'])->group(function () {
        Route::get('/planes', [PlanesController::class, 'all'])->name('allPlanes');
        Route::post('/planes', [PlanesController::class, 'store'])->name('createPlanes');
        Route::get('/plane/{id}', [PlanesController::class, 'show'])->name('planeShow');
        Route::put('/plane/{id}', [PlanesController::class, 'update'])->name('planeUpdate');
        Route::delete('/plane/{id}', [PlanesController::class, 'destroy'])->name('planeDelete');

        Route::post('/flights', [FlightsController::class, 'store'])->name('createFlight');
        Route::put('/flight/{id}', [FlightsController::class, 'update'])->name('flightUpdate');
        Route::delete('/flight/{id}', [FlightsController::class, 'destroy'])->name('flightDelete');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::middleware(['isUser'])->group(function () {
        Route::get('/bookings', [BookingController::class, 'all'])->name('allBookingsFromUser');
        Route::post('/bookings', [BookingController::class, 'store'])->name('createBooking');
        Route::get('/booking/{id}', [BookingController::class, 'show'])->name('bookingShow');
        Route::put('/booking/{id}', [BookingController::class, 'update'])->name('bookingUpdate');
        Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('bookingDelete');
    });
});

Route::get('/flights', [FlightsController::class, 'index'])->name('allFlights');
Route::get('/flight/{id}', [FlightsController::class, 'show'])->name('flightShow');

