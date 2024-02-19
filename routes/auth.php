<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::post('register', 'Api\Auth\AuthController@register');
    Route::post('login', 'Api\Auth\AuthController@login');
    Route::post('email/verify', 'Api\Auth\AuthController@verifyEmail');
    Route::post('email/resend-verification', 'Api\Auth\AuthController@resendVerificationEmail');
    // Route::post('logout', 'Api\Auth\AuthController@logout');
});

