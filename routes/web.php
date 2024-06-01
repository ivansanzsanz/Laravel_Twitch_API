<?php

use App\Http\Infrastructure\Controllers\FollowStreamerController;
use App\Http\Infrastructure\Controllers\GetTopsOfTheTopsController;
use App\Http\Infrastructure\Controllers\GetStreamsController;
use App\Http\Infrastructure\Controllers\GetStreamersController;
use App\Http\Infrastructure\Controllers\CreateUsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/analytics/topsofthetops', GetTopsOfTheTopsController::class);

Route::get('/analytics/streamers', GetStreamersController::class);

Route::get('/analytics/streams', GetStreamsController::class);

Route::post('/analytics/users', CreateUsersController::class);

Route::post('/analytics/follow', FollowStreamerController::class);
