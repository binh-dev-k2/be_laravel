<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers', 'middleware' => ['auth:api']], function () {
    //couple invitation
    Route::post('couple/invite', 'Api\CoupleInvitation\CoupleInvitationController@invite');
    Route::post('couple/update-invite', 'Api\CoupleInvitation\CoupleInvitationController@updateInvite');

    //couple
    Route::get('couple', 'Api\Couple\CoupleController@getCurrentCouple');
    Route::post('couple/update', 'Api\Couple\CoupleController@updateCouple');
});
