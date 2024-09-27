<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return response()->noContent(404);
});
