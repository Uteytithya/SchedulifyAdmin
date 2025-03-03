
<?php

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
});
