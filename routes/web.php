<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckoutController;
Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/admin/genealogy', [AdminController::class, 'genealogy'])->name('admin.genealogy');


Route::get('/', [WelcomeController::class, 'index'])->name('web.index');
Route::get('/cart1', [WelcomeController::class, 'showCart1'])->name('cart.show1');
Route::get('/register', [WelcomeController::class, 'register'])->name('user.register');
Route::post('/register', [WelcomeController::class, 'register1'])->name('register');
Route::get('/login', [WelcomeController::class, 'login'])->name('user.login');
Route::post('/login', [WelcomeController::class, 'login1'])->name('login');

Route::get('/logout', [WelcomeController::class, 'logout'])->name('logout');

Route::get('/admin', [AdminController::class, 'admin'])->name('web.index1');


Route::get('/admin/dashboard',[AdminController::class,'admin_dashboard'])->name('admin_dashboard');
Route::get('/user/dashboard',[UserController::class,'user_dashboard'])->name('user_dashboard');




Route::post('/wishlist/toggle', [CartController::class, 'toggleWishlist'])->name('wishlist.toggle');


Route::get('/categories/create', [ProductController::class, 'add_category'])->name('categories.create');
Route::post('/categories', [ProductController::class, 'store_category'])->name('categories.store');

Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeItem'])->name('cart.remove');

Route::get('/admin/dashboard',[AdminController::class,'admin_dashboard'])->name('admin_dashboard');

Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

Route::middleware(['auth', 'role:admin'])->group(function () { 
});

Route::put('/carts/update/{id}', [CartController::class, 'updateQuantity1'])->name('cart.update1');


Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'userList'])->name('admin.users');

Route::get('/admin/users/{id}/orders', [App\Http\Controllers\AdminController::class, 'userOrders'])->name('admin.user.orders');




Route::middleware('auth')->get('/my-orders', [\App\Http\Controllers\OrderController::class, 'userOrders'])->name('user.orders');
route::get('/rank-history', [AdminController::class, 'rankHistory'])->name('admin.rankHistory');

route::get('/Admin/products', [ProductController::class, 'product_list'])->name('product.list');
//  Route::resource('products', ProductController::class);
//     Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
//     use App\Http\Controllers\Admin\ProductController;

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
    // Route::get('{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
});
 Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
  Route::get('{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

