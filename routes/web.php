<?php

use App\Http\Infrastructure\Controllers\FollowController;
use App\Http\Infrastructure\Controllers\UnfollowController;
use App\Http\Infrastructure\Controllers\GetTopsOfTheTopsController;
use App\Http\Infrastructure\Controllers\GetStreamsController;
use App\Http\Infrastructure\Controllers\GetStreamersController;
use App\Http\Infrastructure\Controllers\CreateUsersController;
use App\Http\Infrastructure\Controllers\GetTimelimeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/analytics/topsofthetops', GetTopsOfTheTopsController::class);

Route::get('/analytics/streamers', GetStreamersController::class);

Route::get('/analytics/streams', GetStreamsController::class);

Route::post('/analytics/users', CreateUsersController::class);

Route::post('/analytics/follow', FollowController::class);

Route::delete('/analytics/unfollow', UnfollowController::class);

Route::delete('/analytics/timeline/', GetTimelimeController::class);
