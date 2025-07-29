<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckoutController;

route::get('/rank-history/{userId}', [AdminController::class, 'rankHistory'])->name('admin.rankHistory');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
   
});
