<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommentController;
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
    // Route::softDeletes('users', UserController::class);
    Route::apiResource('users', UserController::class);

    Route::get('/nurses/frees', [NurseController::class, 'indexFreeNurses'])->name('frees');
    Route::softDeletes('nurses', NurseController::class);
    Route::apiResource('nurses', NurseController::class);

    Route::softDeletes('clients', ClientController::class);
    Route::apiResource('clients', ClientController::class);

    Route::softDeletes('patients', PatientController::class);
    Route::apiResource('patients', PatientController::class);

    Route::name('patients.')->prefix('/patients/{patient}')->scopeBindings()->group(function () {
        Route::apiResource('conditions', ConditionController::class)->except(['index', 'show']);
    });
});

Route::name('appointments.')->prefix('/appointments')->controller(AppointmentController::class)->group(function () {
    Route::get('/filters', 'indexWithFilters')->name('filters');

    Route::prefix('/{appointment}')->group(function () {
        Route::post('/start', 'start')->name('start');
        Route::post('/cancel', 'cancel')->name('cancel');
        Route::post('/end', 'end')->name('end');
    });
});
Route::apiResource('appointments', AppointmentController::class)->except('destroy');

Route::name('appointments.')->prefix('/appointments/{appointment}')->scopeBindings()->group(function () {
    Route::apiResource('comments', CommentController::class)->except(['index', 'show']);
});

Route::fallback(function () {
    return response()->noContent(404);
});
