<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NurseController;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->prefix('/auth')->controller(AuthController::class)->group(function () {
    Route::withoutMiddleware('auth:sanctum')->group(function () {
        Route::post('/login', 'login')->name('login');
        Route::post('/signup', 'signup')->name('signup');
    });
    Route::post('/logout', 'logout')->name('logout');
});

Route::softDeletes('nurses', NurseController::class);
Route::apiResource('nurses', NurseController::class);

Route::fallback(function () {
    return response()->noContent(404);
});
