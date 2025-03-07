
<?php

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
Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');


/*///////////////////////////////////////////
*
*           PRIVATE VIEW
*
*/ //////////////////////////////////////////

Route::group(['middleware' => 'auth:admin', 'prefix' => 'auth'], function ($router) {
    Route::get('dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');

    Route::get('course', [CourseController::class, 'show'])->name('course');
    Route::get('course/create', [CourseController::class, 'showCreate'])->name('course_create');
    Route::post('course/create', [CourseController::class, 'create'])->name('course_create_post');
    Route::get('course/edit/{course}', [CourseController::class, 'edit'])->name('course_edit');
    Route::put('course/edit/{course}', [CourseController::class, 'update'])->name('course_edit_post');
    Route::delete('course/edit/{course}', [CourseController::class, 'destroy'])->name('course_delete_post');
});
