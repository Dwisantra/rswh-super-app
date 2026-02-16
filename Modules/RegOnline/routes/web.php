<?php

use Illuminate\Support\Facades\Route;
use Modules\RegOnline\Http\Controllers\RegOnlineController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('regonlines', RegOnlineController::class)->names('regonline');
});
