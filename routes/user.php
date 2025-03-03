<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\UserAuthController;


/*///////////////////////////////////////////
*
*           PUBLIC API
* // http://127.0.0.1:8000/admin/login
*///////////////////////////////////////////
Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);

/*///////////////////////////////////////////
*
*           PRIVATE API
*
*///////////////////////////////////////////
Route::group(['middleware' => 'auth:api', 'prefix' => 'auth/v1'], function ($router) {
    Route::post('/refresh-token', [UserAuthController::class, 'refreshToken']);
    Route::post('/logout', [UserAuthController::class, 'logout']);

    Route::get('/test', function (Request $request) {
        return response()->json(['message' => 'Hello World!']);
    });
});


