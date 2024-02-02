<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::post('register', 'Api\Auth\AuthController@register');
    Route::post('login', 'Api\Auth\AuthController@login');
    // Route::post('logout', 'Api\Auth\AuthController@logout');
});

