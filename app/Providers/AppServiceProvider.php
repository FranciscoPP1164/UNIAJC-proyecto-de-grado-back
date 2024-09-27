<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Route::macro('softDeletes', function (string $name, $controller) {
            $singularName = substr($name, 0, -1);

            Route::name("{$name}.")->prefix("/{$name}/{{$singularName}}")->controller($controller)->group(function () {
                Route::post('/restore', 'restore')->withTrashed()->name('restore');
                Route::delete('/permanently', 'deletePermanently')->withTrashed()->name('delete.permanently');
            });

        });
    }
}
