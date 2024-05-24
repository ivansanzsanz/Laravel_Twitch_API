<?php

use App\Http\Infrastructure\Controllers\GetTopsOfTheTopsController;
use App\Http\Infrastructure\Controllers\GetStreamsController;
use App\Http\Infrastructure\Controllers\GetStreamersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/analytics/topsofthetops', GetTopsOfTheTopsController::class);

Route::get('/analytics/streamers', GetStreamersController::class);

Route::get('/analytics/streams', GetStreamsController::class);
