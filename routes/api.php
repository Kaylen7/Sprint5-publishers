<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;


Route::middleware('auth:api')->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'store', 'edit']);
    Route::resource('projects', ProjectController::class)->except(['create', 'edit']);
    Route::get('users/{id}/projects', [UserController::class, 'showProjects']);
});

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware('auth:api')->group(function(){
    Route::post('/logout', [AuthenticationController::class, 'logout']);
});

