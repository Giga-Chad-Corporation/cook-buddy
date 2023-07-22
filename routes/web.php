<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\APIRegisterController;

Route::middleware(['web'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/email/verify', [APIRegisterController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [APIRegisterController::class, 'verify'])->name('verification.verify');


        Route::get('register', function () {
            return view('auth.register');
        })->name('register');


        Route::get('shop', function () {
            return view('shop');
        })->name('shop');

    Route::get('user/profile', function () {
        return view('user.profile');
    })->name('user.profile');

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.process');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/formation', [FormationController::class, 'index'])->name('formation');
    Route::get('/formation/cours-a-domicile', [ServiceController::class, 'createCoursADomicile'])->name('formation.cours-a-domicile');
    Route::get('/formation/cours-en-ligne', [ServiceController::class, 'createCoursEnLigne'])->name('formation.cours-en-ligne');
    Route::get('/formation/ateliers', [ServiceController::class, 'ateliers'])->name('formation.ateliers');
    Route::get('/formation/formations-professionnelles', [ServiceController::class, 'formationsProfessionnelles'])->name('formation.formations-professionnelles');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::post('/service/user/add', [ServiceController::class, 'addServiceToUser'])->name('service.user.add');

    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/create-checkout-session/{planId}/{type}', [StripeController::class, 'createCheckoutSession'])->name('create-checkout-session');
    Route::get('/payment/success/{planId}', [StripeController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [StripeController::class, 'cancel'])->name('payment.cancel');
    Route::get('subscribe/free/{planId}', [PlanController::class, 'subscribeFree'])->name('subscribe.free');

    Route::get('/shop/food', [FoodController::class, 'index'])->name('shop.food');
    Route::get('/shop/material', [MaterialController::class, 'index'])->name('shop.material');

    Route::get('/events', [EventsController::class, 'index'])->name('events.index');
});
