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

            Route::name("{$name}.")->prefix("/{$name}")->controller($controller)->group(function () use ($singularName) {
                Route::get('/trashed', 'trashed')->name('trashed');
                Route::post("/{{$singularName}}/restore", 'restore')->withTrashed()->name('restore');
                Route::delete("/{{$singularName}}/permanently", 'destroyPermanently')->withTrashed()->name('destroy.permanently');
            });
        });
    }
}
