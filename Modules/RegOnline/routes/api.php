<?php

use Illuminate\Support\Facades\Route;
use Modules\RegOnline\Http\Controllers\RegOnlineController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::prefix('regonline')->name('regonline.')->group(function () {
        Route::get('/dashboard', [RegOnlineController::class, 'dashboard']);
        Route::get('/doctor-schedules', [RegOnlineController::class, 'doctorSchedules']);
        Route::get('/bed-capacity', [RegOnlineController::class, 'bedCapacity']);
        Route::get('/registrations', [RegOnlineController::class, 'listRegistrations']);
        Route::post('/registrations', [RegOnlineController::class, 'createRegistration']);
        Route::post('/bmi', [RegOnlineController::class, 'calculateBmi']);
        Route::get('/queue-monitor', [RegOnlineController::class, 'monitorClinicQueues']);
        Route::get('/operation-schedules/patient', [RegOnlineController::class, 'patientOperationSchedules']);
        Route::get('/patient/search', [RegOnlineController::class, 'getPatient']);
        Route::get('/patient/shdk', [RegOnlineController::class, 'shdk']);
    });
});
