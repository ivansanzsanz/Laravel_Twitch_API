<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetTopsOfTheTopsController;
use App\Http\Controllers\GetUsersController;
use App\Http\Controllers\GetStreamsController;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/analytics/topsofthetops', TopsOfTheTopsController::class);

Route::get('/analytics/users', GetUsersController::class);

Route::get('/analytics/streams', GetStreamsController::class);
