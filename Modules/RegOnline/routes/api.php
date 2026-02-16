<?php

use Illuminate\Support\Facades\Route;
use Modules\RegOnline\Http\Controllers\RegOnlineController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::prefix('regonline')->name('regonline.')->group(function () {
        Route::get('/poli', [RegOnlineController::class, 'poli']);
    });
    // Route::apiResource('regonlines', RegOnlineController::class)->names('regonline');
});
