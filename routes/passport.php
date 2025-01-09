<?php

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\TransientTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'throttle:60, 1'])->group(function(){
    Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);
    Route::post('/oauth/token/refresh', [AccessTokenController::class, 'refreshToken']);
});