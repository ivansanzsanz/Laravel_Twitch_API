<?php

use App\Http\Infrastructure\Controllers\GetTopsOfTheTopsController;
use App\Http\Infrastructure\Controllers\GetStreamsController;
use App\Http\Infrastructure\Controllers\GetUsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/analytics/topsofthetops', TopsOfTheTopsController::class);

Route::get('/analytics/users', GetUsersController::class);

Route::get('/analytics/streams', GetStreamsController::class);
