<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\PatientController;
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

Route::softDeletes('patients', PatientController::class);
Route::apiResource('patients', PatientController::class);

Route::name('patients.conditions.')->prefix('/patients/{patient}/conditions')->controller(ConditionController::class)->scopeBindings()->group(function () {
    Route::post('/', 'store')->name('store');
    Route::match(['put', 'patch'], '/{condition}', 'update')->name('update');
    Route::delete('/{condition}', 'destroy')->name('destroy');
});

Route::fallback(function () {
    return response()->noContent(404);
});
