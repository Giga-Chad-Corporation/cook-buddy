<?php

use App\Http\Controllers\API\APILoginController;
use App\Http\Controllers\LoginController;
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

Route::post('/register', [RegisterController::class, 'register'])->name('api.register');
Route::post('/login', [APILoginController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getAuthenticatedUser'])->name('api.user');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});



