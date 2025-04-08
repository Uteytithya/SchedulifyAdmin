<?php

use App\Http\Controllers\Web\Auth\TimetableController;
use App\Http\Controllers\Web\Auth\UserController;
use App\Http\Controllers\Web\Auth\RoomController;
use App\Http\Controllers\Web\Auth\StudentsGroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\CourseController;
use App\Http\Controllers\Web\Auth\AdminAuthController;
/*///////////////////////////////////////////
*
*           PUBLIC API
*
*/ /////////////////////////////////////////

Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AdminAuthController::class, 'login'])->name('login_post');


/*///////////////////////////////////////////
*
*           PRIVATE VIEW
*
*/ //////////////////////////////////////////

Route::group(['middleware' => 'auth:admin', 'prefix' => 'auth'], function ($router) {
    Route::get('dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    // Room Management Routes
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms_index'); // List rooms
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms_create'); // Show create form
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms_store'); // Store new room
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms_edit'); // Show edit form
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms_update'); // Update room
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms_destroy'); // Delete room
    Route::get('/rooms/search', [RoomController::class, 'search'])->name('rooms_search');
//    working the student group
    Route::get('/student-groups', [StudentsGroupController::class, 'index'])->name('student-groups_index');
    Route::get('/student-groups/create', [StudentsGroupController::class, 'create'])->name('student-groups_create');
    Route::post('/student-groups', [StudentsGroupController::class, 'store'])->name('student-groups_store');
    Route::get('/student-groups/{group}/edit', [StudentsGroupController::class, 'edit'])->name('student-groups_edit');
    Route::put('/student-groups/{group}', [StudentsGroupController::class, 'update'])->name('student-groups_update');
    Route::delete('/student-groups/{group}', [StudentsGroupController::class, 'destroy'])->name('student-groups_destroy');
    Route::get('/search-groups', [StudentsGroupController::class, 'search'])->name('student-groups_search');

    Route::get('course', [CourseController::class, 'index'])->name('course');
    Route::get('course/create', [CourseController::class, 'create'])->name('course_create');
    Route::post('course/create', [CourseController::class, 'store'])->name('course_create_post');
    Route::get('course/edit/{course}', [CourseController::class, 'edit'])->name('course_edit');
    Route::put('course/edit/{course}', [CourseController::class, 'update'])->name('course_edit_post');
    Route::delete('course/edit/{course}', [CourseController::class, 'destroy'])->name('course_delete_post');

    Route::get("user", [UserController::class, 'index'])->name('users.index');
    Route::get("user/create", [UserController::class, 'create'])->name('users.create');
    Route::post("user/create", [UserController::class, 'store'])->name('users.store');
    Route::get("user/edit/{user}", [UserController::class, 'edit'])->name('users.edit');
    Route::put("user/edit/{user}", [UserController::class, 'update'])->name('users.update');
    Route::delete("user/edit/{user}", [UserController::class, 'destroy'])->name('users.delete');
    Route::get("user/search", [UserController:: class, 'search'])->name('users.search');
    Route::get("user/{user}/edit", [UserController::class, 'edit'])->name('users.edit');


    // Timetable Management Routes
    Route::get('/timetables', [TimetableController::class, 'index'])->name('timetables_index');
    Route::get('/timetables/create', [TimetableController::class, 'create'])->name('timetables_create');
    Route::post('/timetables', [TimetableController::class, 'store'])->name('timetables_store');
    Route::get('/timetables/{timetable}/edit', [TimetableController::class, 'edit'])->name('timetables_edit');
    Route::put('/timetables/{timetable}', [TimetableController::class, 'update'])->name('timetables_update');
    Route::delete('/timetables/{timetable}', [TimetableController::class, 'destroy'])->name('timetables_destroy');
    Route::get('/timetables/search', [TimetableController::class, 'search'])->name('timetables_search');
});
