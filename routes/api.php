<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;

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

// Rutas para Usuarios Comunes
Route::middleware(['auth:api', IsUser::class])->group(function () {
    Route::get('/user/profile', function () {
        return response()->json(['message' => 'Bienvenido Usuario']);
    });
});