<?php

use Illuminate\Support\Facades\Route;
use Modules\Patient\Http\Controllers\PatientController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::prefix('patients')->name('patients')->group(function () {
        Route::get('/search', [PatientController::class, 'getPatient']);
        Route::get('/shdk', [PatientController::class, 'shdk']);
        Route::get('/family', [PatientController::class, 'index']);
        Route::post('/family', [PatientController::class, 'store']);
        Route::post('/family/{id}/set-default', [PatientController::class, 'setDefault']);
    });
    // Route::apiResource('patients', PatientController::class)->names('patient');
});
