<?php

use App\Http\Middleware\IsUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\SingInController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeViewController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PlanesController;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

Route::get('/',[HomeViewController::class, 'index'])->name('home');
Route::get('/flights', [FlightController::class, 'index'])->name('flights');

Route::middleware(['isAdmin'])->group(function () {
    Route::get('/planes', [PlanesController::class, 'index'])->name('planes');
});

Route::middleware(['isUser'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('/register',[SingInController::class, 'showRegisterForm'])->name('register');