<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\UserAuthController;
use App\Http\Controllers\Api\v1\User\UserController;
Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::group(['middleware' => 'auth:api-user', 'prefix' => 'auth/v1'], function ($router) {
    Route::post('/refresh-token', [UserAuthController::class, 'refreshToken']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
    Route::apiResource('/users', UserController::class);
});
