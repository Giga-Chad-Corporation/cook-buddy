<?php

use App\Http\Controllers\FormationController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
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

    Route::get('register', function () {
        return view('auth.register');
    })->name('register');

    Route::get('user/profile', function () {
        return view('user.profile');
    })->name('user.profile');


    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.process');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/formation', [FormationController::class, 'index'])->name('formation');
    Route::get('/formation/cours-a-domicile', [App\Http\Controllers\FormationController::class, 'coursADomicile'])->name('formation.cours-a-domicile');
    Route::get('/formation/lecon-en-ligne', [App\Http\Controllers\FormationController::class, 'leconEnLigne'])->name('formation.lecon-en-ligne');
    Route::get('/formation/ateliers', [App\Http\Controllers\FormationController::class, 'ateliers'])->name('formation.ateliers');
    Route::get('/formation/formations-professionnelles', [App\Http\Controllers\FormationController::class, 'formationsProfessionnelles'])->name('formation.formations-professionnelles');

    Route::get('google-autocomplete', [GoogleController::class, 'google']);
});

