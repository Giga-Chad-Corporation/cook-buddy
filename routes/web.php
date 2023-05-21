<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EventsController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/users', [UsersController::class, 'index'])->name('users');
Route::get('/events', [EventsController::class, 'index'])->name('events');
