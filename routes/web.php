<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()
        ->json(['status'=>'ok'])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');
