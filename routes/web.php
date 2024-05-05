<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopsOfTheTopsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StreamsController;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/analytics/topsofthetops', TopsOfTheTopsController::class);

Route::get('/analytics/users', UsersController::class);

Route::get('/analytics/streams', StreamsController::class);
