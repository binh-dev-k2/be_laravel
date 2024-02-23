<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers', 'middleware' => ['auth:api']], function () {
    Route::post('/couple/invite', 'Api\Couple\CoupleController@invite');
});
