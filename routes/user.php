<?php

use App\Http\Controllers\Api\v1\Auth\CourseController;
use App\Http\Controllers\Api\v1\Auth\RoomController;
use App\Http\Controllers\Api\v1\Auth\ScheduleSessionController;
use App\Http\Controllers\Api\v1\Auth\SessionRequestController;
use App\Http\Controllers\Api\v1\Auth\StudentsGroupController;
use App\Http\Controllers\Api\v1\Auth\TimetableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\UserAuthController;
use App\Http\Controllers\Api\v1\Auth\LecturerAvailabilityController;

/*///////////////////////////////////////////
*
*           PUBLIC API
* // http://127.0.0.1:8000//login
*///////////////////////////////////////////
Route::post('/login', [UserAuthController::class, 'login'])->name('login');

/*///////////////////////////////////////////
*
*           PRIVATE API
*
*///////////////////////////////////////////
Route::group(['middleware' => 'auth:api', 'prefix' => 'auth/v1'], function ($router) {
    Route::post('/refresh-token', [UserAuthController::class, 'refreshToken']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
    Route::get('/user', [UserAuthController::class, 'getUserData']);
    Route::get('/user/sessions', [UserAuthController::class, 'getUserSessions']);

    Route::get('/timetable', [TimetableController::class, 'index']);
    Route::get('/timetable/{id}', [TimetableController::class, 'show']);

    Route::post('request', [SessionRequestController::class, 'store']);
    Route::get('request/{user_id}', [SessionRequestController::class, 'show']);

    Route::get('student-groups', [StudentsGroupController::class, 'index']);

    Route::get('/session',[ScheduleSessionController::class,'index']);
    Route::get('/room',[RoomController::class,'index']);
    Route::get('/course',[CourseController::class,'index']);

    Route::apiResource('lecturer', LecturerAvailabilityController::class);
});


