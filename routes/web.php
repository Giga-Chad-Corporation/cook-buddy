<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['web'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/user/profile', [UserController::class, 'show'])->name('user.profile');
    Route::put('/user/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/user/profile/picture', [UserController::class, 'updateProfilePicture'])->name('user.profile.picture');

    Route::get('register', function () {
        return view('auth.register');
    })->name('register');

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
