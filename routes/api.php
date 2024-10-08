<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdminUserMiddleware;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->prefix('/auth')->controller(AuthController::class)->group(function () {
    Route::withoutMiddleware('auth:sanctum')->group(function () {
        Route::post('/login', 'login')->name('login');
        Route::post('/signup', 'signup')->name('signup');
        Route::post('/register/{user}', 'register')->name('register');
    });
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(IsAdminUserMiddleware::class)->group(function () {
    Route::softDeletes('users', UserController::class);
    Route::apiResource('users', UserController::class);

    Route::softDeletes('nurses', NurseController::class);
    Route::apiResource('nurses', NurseController::class);

    Route::softDeletes('clients', ClientController::class);
    Route::apiResource('clients', ClientController::class);

    Route::softDeletes('patients', PatientController::class);
    Route::apiResource('patients', PatientController::class);

    Route::name('patients.conditions.')->prefix('/patients/{patient}/conditions')->controller(ConditionController::class)->scopeBindings()->group(function () {
        Route::post('/', 'store')->name('store');
        Route::match(['put', 'patch'], '/{condition}', 'update')->name('update');
        Route::delete('/{condition}', 'destroy')->name('destroy');
    });
});

Route::apiResource('appointments', AppointmentController::class);

Route::fallback(function () {
    return response()->noContent(404);
});
