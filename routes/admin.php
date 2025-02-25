<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard']);
});
