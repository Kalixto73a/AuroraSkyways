<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeViewController;

Route::get('/',[HomeViewController::class, 'index'])->name('home');
Route::get('/flights', [FlightController::class, 'index'])->name('flights');
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');