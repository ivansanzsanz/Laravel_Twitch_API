<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopsOfTheTopsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StreamsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/analytics/topsofthetops', [TopsOfTheTopsController::class, 'topsOfTheTops']);

Route::get('/analytics/users', [UsersController::class, 'users']);

Route::get('/analytics/streams', [StreamsController::class, 'streams']);
