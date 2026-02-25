<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\GoogleAuthController;
use Modules\Auth\Http\Controllers\NotificationController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/auth/google', [GoogleAuthController::class, 'login']);
Route::post('/register/email-otp', [AuthController::class, 'sendRegisterEmailOtp']);
Route::post('/external/send-push', [NotificationController::class, 'sendPushFromExternal']);
Route::post('/external/send-push-all', [NotificationController::class, 'sendPushToAll']);

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // Route::apiResource('auths', AuthController::class)->names('auth');
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/notifications/history', [NotificationController::class, 'history']);
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::post('/push-subscribe', [NotificationController::class, 'subscribe']);
});
