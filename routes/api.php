<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;



/* Users  */
Route::group(['middleware' => 'api'], function () {
    Route::get('/users', [UsersController::class, 'get'])->name('api/users');
});
