<?php

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Cart\CartItemsController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\FavoriteProductController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('emailVerify', 'emailVerify');
    Route::post('resendOTP', 'resendOTP');
    Route::post('refreshToken', 'refreshToken');
    Route::post('login', 'login');
    Route::post('forgetPassword', 'forgetPassword');
    Route::post('resetPassword', 'resetPassword');
});
Route::middleware(['jwt.verify:api', 'email.verify'])->group(function () {
    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('profile', 'getProfile');
        Route::post('update', 'updateProfile');
        Route::post('logout', 'logout');
        Route::delete('delete', 'deleteAccount');
    });
    Route::prefix('stores')->controller(StoreController::class)->group(function () {
        Route::post('/{store}', 'update');
    });
    Route::prefix('categories')->controller(CategoryController::class)->group(function () {
        Route::post('/{category}', 'update');
    });
    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::post('/{product}', 'update');
        Route::post('/search/name', 'searchByFilters');
    });
    Route::prefix('products/favorites')->controller(FavoriteProductController::class)->group(function () {
        Route::get('/index', 'index');
        Route::post('/store/{product}', 'store')->missing(function () {
            return ResponseHelper::jsonResponse([], 'Product Not Found', 404, false);
        });
        Route::delete('/destroy/{product}', 'destroy')->missing(function () {
            return ResponseHelper::jsonResponse([], 'Product Not Found', 404, false);
        });
    });
    Route::prefix('carts')->controller(CartController::class)->group(function () {
        Route::get('/', 'show');
        Route::post('/', 'store');
        Route::put('/', 'update');
        Route::delete('/', 'destroy');
    });
    Route::apiResource('cart_items', CartItemsController::class);
    Route::apiResource('stores', StoreController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
});
