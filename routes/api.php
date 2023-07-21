<?php

use App\Http\Controllers\API\APILoginController;
use App\Http\Controllers\API\APIRegisterController;
use App\Http\Controllers\API\APIUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProviderTypeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    Route::post('/register', [APIRegisterController::class, 'register'])->name('api.register');
    Route::post('login', [APILoginController::class, 'login'])->name('api.login');
    Route::post('email',[\App\Http\Controllers\MailController ::class,'sendEmail'])->name('api.sendEmail');
    Route::get('/user/services', [APIUserController::class, 'getServices'])->name('api.user.services');


    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [APILoginController::class, 'logout'])->name('api.logout');
        Route::get('/user/profile', [APIUserController::class, 'showProfile'])->name('api.user.profile');
        Route::patch('user/profile/update', [APIUserController::class, 'updateProfile'])->name('api.user.profile.update');
        Route::post('user/profile/picture', [APIUserController::class, 'updateProfilePicture'])->name('api.user.profile.picture');

    });

    Route::get('/provider-types', [ProviderTypeController::class, 'index'])->name('api.providerTypes');
});


