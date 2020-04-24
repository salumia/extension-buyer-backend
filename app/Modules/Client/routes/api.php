<?php

Route::group([ 'prefix' => 'api'], function()
{
    Route::post('user/updateprofile', 'ClientController@updateProfile');//->middleware('auth:api');
    
    Route::post('user/validateemail', 'ClientController@checkEmailUpdate');//->middleware('auth:api');

    Route::post('user/changepassword', 'ClientController@changePassword');

    Route::post('user/reset-password', 'ClientController@resetCreate');
    
    Route::post('upload', 'ClientController@uploadImage');
});
 
