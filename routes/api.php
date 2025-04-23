<?php

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
    Route::apiResource('stores', StoreController::class);
});
