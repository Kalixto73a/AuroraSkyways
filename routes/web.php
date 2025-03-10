<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeViewController;
use App\Http\Middleware\IsUser;

Route::get('/',[HomeViewController::class, 'index'])->name('home');
Route::get('/flights', [FlightController::class, 'index'])->name('flights');
Route::middleware([IsUser::class])->group(function () {    
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
});
