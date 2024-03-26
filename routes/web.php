<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\topsofthetopsController;
use App\Http\Controllers\usersController;
use App\Http\Controllers\streamsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/analytics/topsofthetops', [topsofthetopsController::class, 'topsOfTheTops']);

Route::get('/analytics/users', [usersController::class, 'users']);

Route::get('/analytics/streams', [streamsController::class, 'streams']);
