<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\CartController;

// Public-facing shop routes
Route::controller(ClientController::class)->group(function () {
    Route::get('/', 'index')->name('clientHome');
    Route::get('/products', 'products')->name('clientProducts');
    Route::get('/product/{title_slug}', 'productDetail')->name('clientProductDetail'); 
    Route::get('/categories', 'category')->name('clientCategory');
    Route::get('/category/{name_slug}', 'categoryProducts')->name('clientCategoryProducts'); 
    Route::get('/about', 'about')->name('clientAbout');
    Route::post('/search', 'searchProduct')->name('clientSearch');


 });

// Cart actions (these remain public for guests to build their cart)
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'carts')->name('clientCart');
    Route::post('/cart/add', 'addToCart')->name('clientAddToCart');
    Route::post('/cart/update', 'updateCart')->name('clientUpdateCart');
    Route::post('/cart/delete', 'deleteCart')->name('clientDeleteCart');
});
