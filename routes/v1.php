<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers', 'middleware' => ['auth:api']], function () {
    //couple invitation
    Route::post('couple-invite', 'Api\CoupleInvitation\CoupleInvitationController@invite');
    Route::post('couple-invite/update', 'Api\CoupleInvitation\CoupleInvitationController@updateInvite');
    Route::get('couple-invite', 'Api\CoupleInvitation\CoupleInvitationController@listInvite');

    //couple
    Route::get('couple', 'Api\Couple\CoupleController@getCurrentCouple');
    Route::post('couple', 'Api\Couple\CoupleController@updateCouple');
});
