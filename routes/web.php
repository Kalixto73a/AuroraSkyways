<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeViewController;

Route::get('/',[HomeViewController::class, 'index'])->name('home');
