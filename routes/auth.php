<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::post('check-mail', 'Api\Auth\RegisterController@checkEmail');
    Route::post('register', 'Api\Auth\RegisterController@register');
    Route::post('login', 'Api\Auth\LoginController@login');
    Route::post('email/verify', 'Api\Auth\RegisterController@verifyEmail');
    Route::post('email/resend-verification', 'Api\Auth\RegisterController@resendVerificationEmail');
    Route::post('forgot-password', 'Api\Auth\ForgotPasswordController@forgotPassword');
    Route::post('renew-password', 'Api\Auth\ForgotPasswordController@verifyOTPForgotPassword');
    // Route::post('logout', 'Api\Auth\AuthController@logout');
});

