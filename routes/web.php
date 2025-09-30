<?php

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('cart/add', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::get('cart', [CustomerController::class, 'getCart'])->name('cart.get');
    Route::delete('cart/remove', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
    Route::put('cart/update', [CustomerController::class, 'updateCart'])->name('cart.update');
});

require __DIR__ . '/auth.php';


/* home page */
Route::get('home', [CustomerController::class, 'index'])->name('index');

/* about page */
Route::get('about', [CustomerController::class, 'about'])->name('about');

/* menu page */
Route::get('menu', [CustomerController::class, 'menu'])->name('menu');
Route::get('menu/paginate', [CustomerController::class, 'paginateMenu'])->name('menu.paginate');

/* gallery page */
Route::get('gallery', [CustomerController::class, 'gallery'])->name('gallery');

/* contact page */
Route::get('contact', [CustomerController::class, 'contact'])->name('contact');
