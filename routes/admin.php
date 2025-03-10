
<?php

use App\Http\Controllers\Web\Auth\RoomController;
use App\Http\Controllers\Web\Auth\StudentsGroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\AdminAuthController;

/*///////////////////////////////////////////
*
*           PUBLIC API
*
*/ //////////////////////////////////////////

Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AdminAuthController::class, 'login'])->name('login_post');
Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

/*///////////////////////////////////////////
*
*           PRIVATE VIEW
*
*/ //////////////////////////////////////////

Route::group(['middleware' => 'auth:admin', 'prefix' => 'auth'], function ($router) {
    Route::get('dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    // Room Management Routes
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index'); // List rooms
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create'); // Show create form
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store'); // Store new room
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit'); // Show edit form
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update'); // Update room
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy'); // Delete room
//    working the student group
    Route::get('/student-groups', [StudentsGroupController::class, 'index'])->name('student-groups.index');
    Route::get('/student-groups/create', [StudentsGroupController::class, 'create'])->name('student-groups.create');
    Route::post('/student-groups', [StudentsGroupController::class, 'store'])->name('student-groups.store');
    Route::get('/student-groups/{group}/edit', [StudentsGroupController::class, 'edit'])->name('student-groups.edit');
    Route::put('/student-groups/{group}', [StudentsGroupController::class, 'update'])->name('student-groups.update');
    Route::delete('/student-groups/{group}', [StudentsGroupController::class, 'destroy'])->name('student-groups.destroy');
    Route::get('/search-groups', [StudentsGroupController::class, 'search'])->name('student-groups.search');
});
