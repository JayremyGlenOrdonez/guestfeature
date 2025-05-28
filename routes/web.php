<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\CartController;

// Public-facing shop routes
Route::controller(ClientController::class)->group(function () {
    Route::get('/', 'index')->name('clientHome');
    Route::get('/products', 'products')->name('clientProducts');
    // Note: It's good practice to use a slug for product titles in URLs for SEO
    Route::get('/product/{title_slug}', 'productDetail')->name('clientProductDetail'); // Changed from {title} to {title_slug}
    Route::get('/categories', 'category')->name('clientCategory');
    // Note: It's good practice to use a slug for category names in URLs for SEO
    Route::get('/category/{name_slug}', 'categoryProducts')->name('clientCategoryProducts'); // Changed from {name} to {name_slug}
    Route::get('/about', 'about')->name('clientAbout');
    Route::post('/search', 'searchProduct')->name('clientSearch');

    // Checkout - PROTECTED by authentication
    // User must be logged in to access these routes
    Route::get('/checkout', 'checkout')->middleware('auth')->name('clientCheckout'); // ADDED middleware('auth')
    Route::post('/checkout/save', 'checkoutSave')->middleware('auth')->name('clientCheckoutSave'); // ADDED middleware('auth')

    // UPDATED: Success page - Removed {order_code} from URL as it's not prominently displayed for guests
    Route::get('/checkout/success', 'successOrder')->name('clientSuccessOrder'); // Changed route name and removed parameter

    // REMOVED: Order tracking routes as per your request (no more guest order code)
    // Route::get('/order/check', 'checkOrder')->name('clientCheckOrder');
    // Route::post('/order/status', 'checkOrderStatus')->name('clientCheckOrderStatus');
 });

// Cart actions (these remain public for guests to build their cart)
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'carts')->name('clientCart');
    Route::post('/cart/add', 'addToCart')->name('clientAddToCart');
    Route::post('/cart/update', 'updateCart')->name('clientUpdateCart');
    Route::post('/cart/delete', 'deleteCart')->name('clientDeleteCart');
});
