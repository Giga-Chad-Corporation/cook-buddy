<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LivestreamController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ShopController;
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

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/cmadlog', [AdminController::class, 'login'])->name('admin.login');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users', [AdminController::class, 'createUser'])->name('admin.users.store');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
    Route::put('/users/{users}', [AdminController::class, 'updateUser'])->name('admin.users.update');

    Route::get('/admin/types', [AdminController::class, 'types'])->name('admin.types');
    Route::post('/admin/types', [AdminController::class, 'createType'])->name('admin.types.store');
    Route::delete('/admin/types/{id}', [AdminController::class, 'deleteType'])->name('admin.types.destroy');
    Route::put('/types/{types}', [AdminController::class, 'updateType'])->name('admin.types.update');


    Route::get('/admin/tags', [AdminController::class, 'tags'])->name('admin.tags');
    Route::post('/admin/tags', [AdminController::class, 'createTag'])->name('admin.tags.store');
    Route::delete('/admin/tags/{id}', [AdminController::class, 'deleteTag'])->name('admin.tags.destroy');
    Route::put('/tag/{tag}', [AdminController::class, 'updateTag'])->name('admin.tag.update');

    Route::get('/admin/items', [AdminController::class, 'articles'])->name('admin.items');
    Route::post('/admin/items', [AdminController::class, 'createArticles'])->name('admin.items.store');
    Route::delete('/admin/items/{id}', [AdminController::class, 'deleteArticles'])->name('admin.items.destroy');
    Route::put('/items/{item}', [AdminController::class, 'updateArticles'])->name('admin.items.update');

    Route::get('/formation', [FormationController::class, 'index'])->name('formation');
    Route::get('/formation/cours-a-domicile', [ServiceController::class, 'createCoursADomicile'])->name('formation.cours-a-domicile');
    Route::get('/formation/cours-en-ligne', [ServiceController::class, 'createCoursEnLigne'])->name('formation.cours-en-ligne');
    Route::get('/formation/ateliers', [ServiceController::class, 'ateliers'])->name('formation.ateliers');
    Route::get('/formation/formations-professionnelles', [ServiceController::class, 'formationsProfessionnelles'])->name('formation.formations-professionnelles');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::post('/service/user/add', [ServiceController::class, 'addServiceToUser'])->name('service.user.add');
    Route::get('/get-available-providers', [ServiceController::class, 'getAvailableProviders'])->name('getAvailableProviders');
    Route::get('/get-rooms', [RoomController::class,'getRooms'])->name('getRooms');
    Route::get('/get-room-details', [RoomController::class,'getRoomDetails'])->name('getRoomDetails');
    Route::get('/get-available-rooms', [RoomController::class,'getAvailableRooms'])->name('getAvailableRooms');

    Route::get('/reservation', [ReservationController::class, 'create'])->name('reservation.create');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');

    Route::get('/livestream/authorize', [LivestreamController::class, 'authorizeYouTube'])->name('livestream.authorize');
    Route::get('/livestream/create', [LivestreamController::class, 'createYouTubeLivestream'])->name('livestream.create');

    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/create-checkout-session/{planId}/{type}', [StripeController::class, 'createCheckoutSession'])->name('create-checkout-session');
    Route::get('/payment/success/{planId}', [StripeController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [StripeController::class, 'cancel'])->name('payment.cancel');
    Route::get('subscribe/free/{planId}', [PlanController::class, 'subscribeFree'])->name('subscribe.free');

    Route::get('/shop/food', [FoodController::class, 'index'])->name('shop.food');
    Route::get('/shop/material', [MaterialController::class, 'index'])->name('shop.material');
    Route::post('/add-to-cart/{id}', [ShopController::class, 'addToCart'])->name('add.to.cart');
    Route::get('/cart', [ShopController::class, 'showCart'])->name('cart.show');
    Route::get('/item/{id}', [ItemController::class,'show'])->name('item.show');
    Route::delete('/cart/remove/{itemId}', [ShopController::class, 'removeFromCart'])->name('cart.remove');

    Route::get('/provider/availability', [ProviderController::class, 'regions'])->name('provider.availability');
    Route::put('provider/availability', [ProviderController::class, 'updateAvailability'])->name('provider.availability.update');
    Route::put('/provider/availability/{id}/edit', [ProviderController::class, 'editAvailability'])->name('provider.availability.edit');
    Route::delete('/provider/availability/{id}/delete', [ProviderController::class, 'deleteAvailability'])->name('provider.availability.delete');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('cart.checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/redirect-to-stripe', [CheckoutController::class, 'redirectToStripe'])->name('checkout.redirectToStripe');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');
    Route::get('/orders/{order}',[OrderController::class, 'show'] )->name('orders.show');


    Route::get('/events', [EventsController::class, 'index'])->name('events.index');
});
