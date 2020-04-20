<?php

Route::group([ 'prefix' => 'api'], function()
{
    Route::post('user/updateprofile', 'ClientController@updateProfile');//->middleware('auth:api');
    
    Route::post('user/validateemail', 'ClientController@checkEmailUpdate');//->middleware('auth:api');
});
 
