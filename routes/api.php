<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->prefix('/auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/signup', 'signup')->name('signup');
    Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
});
