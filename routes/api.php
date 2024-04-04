<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\FollowerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(
    function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::middleware(['auth:user'])->group(function () {
            Route::apiResource('tweets', TweetController::class)->only('store');
            Route::post('users/{user}/follow', [FollowerController::class, 'follow'])->name('users.follow');
            Route::post('users/{user}/un-follow', [FollowerController::class, 'unFollow'])->name('users.un_follow');
        });
    }
);
